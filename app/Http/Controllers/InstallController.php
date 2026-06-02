<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\SystemBackupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;
use PDO;
use Throwable;
use ZipArchive;

class InstallController extends Controller
{
    private const TYPE_CLEAN = 'clean';

    private const TYPE_FULL_ZIP = 'full_zip';

    private const TYPE_SQL = 'sql';

    public function index(Request $request): View
    {
        return view('install.index', [
            'checks' => $this->systemChecks(),
            'selectedType' => $request->session()->get('install.type', self::TYPE_CLEAN),
        ]);
    }

    public function storeType(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:' . implode(',', [self::TYPE_CLEAN, self::TYPE_FULL_ZIP, self::TYPE_SQL])],
        ]);

        $request->session()->put('install.type', $validated['type']);

        return redirect()->route('install.database');
    }

    public function database(Request $request): View
    {
        return view('install.database', [
            'database' => $request->session()->get('install.database', [
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => env('DB_DATABASE', ''),
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
            ]),
        ]);
    }

    public function testDatabase(Request $request): RedirectResponse
    {
        $validated = $this->validateDatabase($request);

        try {
            $this->pdoFromConfig($validated);
        } catch (Throwable $exception) {
            return back()
                ->withInput()
                ->with('error', 'Veritabani baglantisi basarisiz: ' . $exception->getMessage());
        }

        $request->session()->put('install.database', $validated);

        return back()
            ->withInput($validated)
            ->with('success', 'Veritabani baglantisi basarili.');
    }

    public function storeDatabase(Request $request): RedirectResponse
    {
        $validated = $this->validateDatabase($request);

        try {
            $this->pdoFromConfig($validated);
        } catch (Throwable $exception) {
            return back()
                ->withInput()
                ->with('error', 'Veritabani baglantisi basarisiz: ' . $exception->getMessage());
        }

        $request->session()->put('install.database', $validated);
        $this->writeDatabaseEnv($validated);

        return $this->installType($request) === self::TYPE_CLEAN
            ? redirect()->route('install.site-admin')
            : redirect()->route('install.restore');
    }

    public function restore(Request $request): View|RedirectResponse
    {
        if ($this->installType($request) === self::TYPE_CLEAN) {
            return redirect()->route('install.site-admin');
        }

        return view('install.restore', [
            'type' => $this->installType($request),
        ]);
    }

    public function storeRestore(Request $request): RedirectResponse
    {
        $type = $this->installType($request);

        if ($type === self::TYPE_FULL_ZIP) {
            $validated = $request->validate([
                'backup_file' => ['required', 'file', 'mimes:zip', 'max:512000'],
            ]);

            $file = $validated['backup_file'];

            if (! Str::startsWith($file->getClientOriginalName(), 'full-backup-')) {
                return back()->with('error', 'Full ZIP yedek dosyasi full-backup- ile baslamali.');
            }

            $extension = 'zip';
        } else {
            $validated = $request->validate([
                'backup_file' => ['required', 'file', 'extensions:sql,txt', 'max:512000'],
            ]);

            $file = $validated['backup_file'];
            $extension = 'sql';
        }

        $directory = storage_path('app/installer');
        File::ensureDirectoryExists($directory);

        $path = $file->move($directory, 'restore-' . Str::random(12) . '.' . $extension)->getRealPath();
        $request->session()->put('install.restore_path', $path);

        return redirect()->route('install.run');
    }

    public function siteAdmin(Request $request): View|RedirectResponse
    {
        if ($this->installType($request) !== self::TYPE_CLEAN) {
            return redirect()->route('install.restore');
        }

        return view('install.site-admin', [
            'data' => $request->session()->get('install.site_admin', [
                'site_name' => 'Argnest',
                'site_url' => env('APP_URL', url('/')),
                'admin_name' => '',
                'admin_email' => '',
            ]),
        ]);
    }

    public function storeSiteAdmin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_url' => ['required', 'url', 'max:255'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255'],
            'admin_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $validated['admin_email'] = Str::lower(trim($validated['admin_email']));

        $request->session()->put('install.site_admin', $validated);
        $this->writeEnvValue('APP_URL', $validated['site_url']);

        return redirect()->route('install.run');
    }

    public function run(Request $request): View
    {
        return view('install.run', [
            'type' => $this->installType($request),
        ]);
    }

    public function process(Request $request): RedirectResponse
    {
        try {
            $type = $this->installType($request);
            $this->applyDatabaseConfig($request->session()->get('install.database'));

            if ($type === self::TYPE_CLEAN) {
                $this->processCleanInstall($request);
            } elseif ($type === self::TYPE_FULL_ZIP) {
                $this->processFullZipRestore($request);
            } else {
                $this->processSqlRestore($request);
            }

            $this->lockInstallation();
            $request->session()->forget('install');

            return redirect()->route('install.completed');
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('error', 'Kurulum tamamlanamadi: ' . $exception->getMessage());
        }
    }

    public function completed(): View
    {
        return view('install.completed');
    }

    private function processCleanInstall(Request $request): void
    {
        $siteAdmin = $request->session()->get('install.site_admin');

        if (! $siteAdmin) {
            throw new \RuntimeException('Site ve admin bilgileri eksik.');
        }

        Artisan::call('migrate', ['--force' => true]);

        foreach ([
            \Database\Seeders\ServiceSeeder::class,
            \Database\Seeders\ProductSeeder::class,
            \Database\Seeders\PortfolioSeeder::class,
            \Database\Seeders\BlogSeeder::class,
            \Database\Seeders\HeroButtonSeeder::class,
        ] as $seeder) {
            Artisan::call('db:seed', ['--class' => $seeder, '--force' => true]);
        }

        SiteSetting::query()->updateOrCreate(
            ['id' => SiteSetting::query()->value('id') ?: 1],
            [
                'site_name' => $siteAdmin['site_name'],
                'email' => $siteAdmin['admin_email'],
            ],
        );

        $admin = User::query()->updateOrCreate(
            ['email' => $siteAdmin['admin_email']],
            [
                'name' => $siteAdmin['admin_name'],
                'password' => Hash::make($siteAdmin['admin_password']),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        $this->attachAdminRoleAndPermissions($admin);
        $this->assertAdminCanLogin($siteAdmin['admin_email'], $siteAdmin['admin_password']);
    }

    private function processFullZipRestore(Request $request): void
    {
        $path = $this->restorePath($request);
        $this->createPreRestoreBackupIfPossible();

        $extractPath = storage_path('app/installer/full-restore-' . Str::random(8));
        File::ensureDirectoryExists($extractPath);

        $zip = new ZipArchive();

        if ($zip->open($path) !== true) {
            throw new \RuntimeException('ZIP yedek dosyasi acilamadi.');
        }

        $databaseSqlName = $this->databaseSqlNameInZip($zip);

        if ($databaseSqlName === null) {
            $zip->close();
            throw new \RuntimeException('ZIP içinde database.sql bulunamadı');
        }

        $zip->extractTo($extractPath);
        $zip->close();

        $databaseSqlPath = $extractPath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $databaseSqlName);
        $this->importSqlFile($databaseSqlPath);
        $this->restoreExtractedFiles($extractPath);

        File::deleteDirectory($extractPath);
    }

    private function processSqlRestore(Request $request): void
    {
        $path = $this->restorePath($request);
        $this->createPreRestoreBackupIfPossible();
        $this->importSqlFile($path);
    }

    private function createPreRestoreBackupIfPossible(): void
    {
        try {
            DB::connection()->getPdo();

            if (! Schema::hasTable('migrations') || ! Schema::hasTable('system_backups')) {
                Log::warning('Pre-restore backup atlandi: migrations veya system_backups tablosu bulunamadi.');

                return;
            }

            $backup = app(SystemBackupService::class)->createFullBackup();
            $target = storage_path('app/backups/pre-restore-backup.zip');

            if ($backup->status !== 'completed' || ! File::exists($backup->absolutePath())) {
                Log::warning('Pre-restore backup olusturulamadi, restore devam ediyor.', [
                    'error' => $backup->error_message,
                ]);

                return;
            }

            File::copy($backup->absolutePath(), $target);
        } catch (Throwable $exception) {
            Log::warning('Pre-restore backup alinamadi, restore devam ediyor.', [
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function importSqlFile(string $path): void
    {
        if (! File::exists($path)) {
            throw new \RuntimeException('SQL dosyasi bulunamadi.');
        }

        try {
            $sql = File::get($path);
            DB::unprepared($sql);
        } catch (Throwable $exception) {
            throw new \RuntimeException('Veritabanı yedeği içe aktarılamadı: ' . $exception->getMessage(), 0, $exception);
        }
    }

    private function restoreExtractedFiles(string $extractPath): void
    {
        $map = [
            'storage/app/public' => storage_path('app/public'),
            'storage/app/private' => storage_path('app/private'),
            'storage/public' => storage_path('app/public'),
            'storage/private' => storage_path('app/private'),
            'public/uploads' => public_path('uploads'),
            'public/images' => public_path('images'),
        ];

        foreach ($map as $relative => $target) {
            $source = $extractPath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);

            if (! File::exists($source)) {
                continue;
            }

            File::ensureDirectoryExists($target);
            File::copyDirectory($source, $target);
        }
    }

    private function attachAdminRoleAndPermissions(User $admin): void
    {
        $role = Role::query()->firstOrCreate(
            ['slug' => 'yonetici'],
            [
                'name' => 'Yonetici',
                'description' => 'Tum yonetim yetkilerine sahip sistem rolu.',
                'is_system' => true,
            ],
        );

        $permissions = [
            ['name' => 'Musteri Goruntuleme', 'key' => 'customer_view', 'group' => 'Musteriler'],
            ['name' => 'Musteri Duzenleme', 'key' => 'customer_edit', 'group' => 'Musteriler'],
            ['name' => 'Destek Goruntuleme', 'key' => 'support_view', 'group' => 'Destek'],
            ['name' => 'Destek Yanitlama', 'key' => 'support_reply', 'group' => 'Destek'],
            ['name' => 'Hizmet Goruntuleme', 'key' => 'service_view', 'group' => 'Hizmetler'],
            ['name' => 'Hizmet Duzenleme', 'key' => 'service_edit', 'group' => 'Hizmetler'],
            ['name' => 'Dosya Goruntuleme', 'key' => 'file_view', 'group' => 'Dosyalar'],
            ['name' => 'Dosya Yukleme', 'key' => 'file_upload', 'group' => 'Dosyalar'],
            ['name' => 'Bildirim Yonetimi', 'key' => 'notification_manage', 'group' => 'Bildirimler'],
            ['name' => 'Mail Ayarlari Yonetimi', 'key' => 'mail_settings_manage', 'group' => 'Ayarlar'],
            ['name' => 'Site Ayarlari Yonetimi', 'key' => 'site_settings_manage', 'group' => 'Ayarlar'],
            ['name' => 'Yedek Yonetimi', 'key' => 'backup_manage', 'group' => 'Sistem'],
            ['name' => 'Yedek Olusturma', 'key' => 'backup_create', 'group' => 'Sistem'],
            ['name' => 'Yedek Indirme', 'key' => 'backup_download', 'group' => 'Sistem'],
            ['name' => 'Yedek Silme', 'key' => 'backup_delete', 'group' => 'Sistem'],
            ['name' => 'Guvenlik Loglari Goruntuleme', 'key' => 'security_logs_view', 'group' => 'Sistem'],
            ['name' => 'Admin Yonetimi', 'key' => 'admin_manage', 'group' => 'Yonetim'],
            ['name' => 'Rol Yonetimi', 'key' => 'role_manage', 'group' => 'Yonetim'],
            ['name' => 'Yetki Yonetimi', 'key' => 'permission_manage', 'group' => 'Yonetim'],
            ['name' => 'Canli Destek Goruntuleme', 'key' => 'live_chat_view', 'group' => 'Destek'],
            ['name' => 'Canli Destek Yanitlama', 'key' => 'live_chat_reply', 'group' => 'Destek'],
            ['name' => 'Canli Destek Kapatma', 'key' => 'live_chat_close', 'group' => 'Destek'],
            ['name' => 'Canli Destek Yonetimi', 'key' => 'live_chat_manage', 'group' => 'Destek'],
        ];

        collect($permissions)->each(function (array $permission): void {
            Permission::query()->firstOrCreate(
                ['key' => $permission['key']],
                [
                    'name' => $permission['name'],
                    'group' => $permission['group'],
                    'description' => null,
                ],
            );
        });

        $permissionIds = Permission::query()->pluck('id');

        $role->permissions()->syncWithoutDetaching($permissionIds->all());
        $admin->roles()->syncWithoutDetaching([$role->id]);
        $admin->permissions()->syncWithoutDetaching($permissionIds->all());
    }

    private function assertAdminCanLogin(string $email, string $password): void
    {
        if (! User::query()->where('email', $email)->exists()) {
            throw new \RuntimeException('Admin kullanıcı oluşturulamadı');
        }

        $admin = User::query()->where('email', $email)->first();

        if (! $admin || ! Hash::check($password, $admin->password)) {
            throw new \RuntimeException('Şifre hash doğrulaması başarısız');
        }

        if ($admin->role !== User::ROLE_ADMIN || ! $admin->is_active || $admin->email_verified_at === null) {
            throw new \RuntimeException('Admin kullanıcı oluşturulamadı');
        }

        if (! $admin->roles()->where('slug', 'yonetici')->exists()) {
            throw new \RuntimeException('Admin kullanıcı oluşturulamadı');
        }

        $permissionCount = Permission::query()->count();
        $adminPermissionCount = $admin->permissions()->count();
        $adminRolePermissionCount = $admin->roles()
            ->where('slug', 'yonetici')
            ->withCount('permissions')
            ->first()
            ?->permissions_count ?? 0;

        if ($adminPermissionCount < $permissionCount || $adminRolePermissionCount < $permissionCount) {
            throw new \RuntimeException('Admin kullanıcı oluşturulamadı');
        }
    }

    private function databaseSqlNameInZip(ZipArchive $zip): ?string
    {
        for ($index = 0; $index < $zip->numFiles; $index++) {
            $name = $zip->getNameIndex($index);

            if ($name !== false && Str::lower(basename(str_replace('\\', '/', $name))) === 'database.sql') {
                return $name;
            }
        }

        return null;
    }

    private function validateDatabase(Request $request): array
    {
        return $request->validate([
            'host' => ['required', 'string', 'max:255'],
            'port' => ['required', 'integer', 'min:1', 'max:65535'],
            'database' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function pdoFromConfig(array $config): PDO
    {
        $dsn = 'mysql:host=' . $config['host'] . ';port=' . $config['port'] . ';dbname=' . $config['database'] . ';charset=utf8mb4';

        return new PDO($dsn, $config['username'], $config['password'] ?? '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    private function writeDatabaseEnv(array $database): void
    {
        $this->writeEnvValue('DB_CONNECTION', 'mysql');
        $this->writeEnvValue('DB_HOST', $database['host']);
        $this->writeEnvValue('DB_PORT', (string) $database['port']);
        $this->writeEnvValue('DB_DATABASE', $database['database']);
        $this->writeEnvValue('DB_USERNAME', $database['username']);
        $this->writeEnvValue('DB_PASSWORD', $database['password'] ?? '');
    }

    private function applyDatabaseConfig(?array $database): void
    {
        if (! $database) {
            return;
        }

        config([
            'database.default' => 'mysql',
            'database.connections.mysql.host' => $database['host'],
            'database.connections.mysql.port' => $database['port'],
            'database.connections.mysql.database' => $database['database'],
            'database.connections.mysql.username' => $database['username'],
            'database.connections.mysql.password' => $database['password'] ?? '',
        ]);

        DB::purge('mysql');
        DB::reconnect('mysql');
    }

    private function writeEnvValue(string $key, string $value): void
    {
        $path = base_path('.env');

        if (! File::exists($path)) {
            File::put($path, '');
        }

        $content = File::get($path);
        $line = $key . '=' . $this->envValue($value);

        if (preg_match('/^' . preg_quote($key, '/') . '=.*$/m', $content)) {
            $content = preg_replace('/^' . preg_quote($key, '/') . '=.*$/m', $line, $content);
        } else {
            $content = rtrim($content) . PHP_EOL . $line . PHP_EOL;
        }

        File::put($path, $content);
    }

    private function envValue(string $value): string
    {
        if ($value === '') {
            return '';
        }

        return preg_match('/\s|#|"|\'/', $value)
            ? '"' . str_replace('"', '\"', $value) . '"'
            : $value;
    }

    private function lockInstallation(): void
    {
        $this->writeEnvValue('APP_INSTALLED', 'true');
        File::ensureDirectoryExists(storage_path('framework'));
        File::put(storage_path('framework/argnest-installed.lock'), 'Installed at ' . now()->toDateTimeString());
    }

    private function systemChecks(): array
    {
        return [
            ['label' => 'PHP 8.2+', 'ok' => version_compare(PHP_VERSION, '8.2.0', '>=')],
            ['label' => 'PDO', 'ok' => extension_loaded('pdo')],
            ['label' => 'ZIP', 'ok' => extension_loaded('zip')],
            ['label' => 'Fileinfo', 'ok' => extension_loaded('fileinfo')],
            ['label' => 'OpenSSL', 'ok' => extension_loaded('openssl')],
            ['label' => 'storage yazilabilir', 'ok' => is_writable(storage_path())],
            ['label' => 'bootstrap/cache yazilabilir', 'ok' => is_writable(base_path('bootstrap/cache'))],
            ['label' => '.env mevcut', 'ok' => File::exists(base_path('.env'))],
        ];
    }

    private function installType(Request $request): string
    {
        return $request->session()->get('install.type', self::TYPE_CLEAN);
    }

    private function restorePath(Request $request): string
    {
        $path = $request->session()->get('install.restore_path');

        if (! $path || ! File::exists($path)) {
            throw new \RuntimeException('Geri yukleme dosyasi bulunamadi.');
        }

        return $path;
    }
}
