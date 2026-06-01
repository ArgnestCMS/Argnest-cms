<?php

namespace App\Services;

use App\Models\AdminActivityLog;
use App\Models\SystemBackup;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDO;
use Throwable;
use ZipArchive;

class SystemBackupService
{
    public function createFullBackup(?User $admin = null): SystemBackup
    {
        $backup = SystemBackup::query()->create([
            'file_name' => $this->fileName(),
            'file_path' => '',
            'file_size' => 0,
            'type' => SystemBackup::TYPE_FULL,
            'status' => SystemBackup::STATUS_PROCESSING,
            'created_by' => $admin?->id,
        ]);

        $backup->forceFill([
            'file_path' => 'backups/' . $backup->file_name,
        ])->save();

        try {
            $this->buildArchive($backup);

            $backup->forceFill([
                'file_size' => File::size($backup->absolutePath()),
                'status' => SystemBackup::STATUS_COMPLETED,
                'completed_at' => now(),
                'error_message' => null,
            ])->save();

            app(AdminActivityLogger::class)->log(
                AdminActivityLog::ACTION_BACKUP_CREATED,
                'Tam sistem yedegi olusturuldu: ' . $backup->file_name,
                $admin,
            );
        } catch (Throwable $exception) {
            if (File::exists($backup->absolutePath())) {
                File::delete($backup->absolutePath());
            }

            $backup->forceFill([
                'status' => SystemBackup::STATUS_FAILED,
                'error_message' => $exception->getMessage(),
            ])->save();

            app(AdminActivityLogger::class)->log(
                AdminActivityLog::ACTION_BACKUP_FAILED,
                'Tam sistem yedegi basarisiz oldu: ' . $exception->getMessage(),
                $admin,
            );
        }

        return $backup->refresh();
    }

    private function buildArchive(SystemBackup $backup): void
    {
        if (! class_exists(ZipArchive::class)) {
            throw new \RuntimeException('ZIP eklentisi etkin degil. PHP zip extension aktif olmali.');
        }

        $backupDirectory = storage_path('app/backups');
        $tempDirectory = storage_path('app/backups/tmp/' . pathinfo($backup->file_name, PATHINFO_FILENAME));

        File::ensureDirectoryExists($backupDirectory);
        File::ensureDirectoryExists($tempDirectory);

        $databaseDumpPath = $tempDirectory . DIRECTORY_SEPARATOR . 'database.sql';
        $this->writeDatabaseDump($databaseDumpPath);

        $zip = new ZipArchive();

        if ($zip->open($backup->absolutePath(), ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Yedek ZIP dosyasi olusturulamadi.');
        }

        $zip->addFile($databaseDumpPath, 'database.sql');

        foreach ($this->backupSources() as $source) {
            if (! File::exists($source['path'])) {
                continue;
            }

            $this->addDirectoryToZip($zip, $source['path'], $source['prefix']);
        }

        $zip->close();
        File::deleteDirectory($tempDirectory);
    }

    private function writeDatabaseDump(string $path): void
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();

        $dump = match ($driver) {
            'mysql', 'mariadb' => $this->mysqlDump(),
            'sqlite' => $this->sqliteDump(),
            default => throw new \RuntimeException('Bu veritabani surucusu icin yedekleme desteklenmiyor: ' . $driver),
        };

        File::put($path, $dump);
    }

    private function mysqlDump(): string
    {
        $pdo = DB::connection()->getPdo();
        $database = DB::connection()->getDatabaseName();
        $tables = DB::select('SHOW FULL TABLES WHERE Table_type = ?', ['BASE TABLE']);
        $tableKey = 'Tables_in_' . $database;
        $lines = [
            '-- Argnest database backup',
            '-- Created at: ' . now()->toDateTimeString(),
            'SET FOREIGN_KEY_CHECKS=0;',
            '',
        ];

        foreach ($tables as $tableRow) {
            $table = $tableRow->{$tableKey} ?? array_values((array) $tableRow)[0] ?? null;

            if (! $table) {
                continue;
            }

            $create = DB::selectOne('SHOW CREATE TABLE `' . str_replace('`', '``', $table) . '`');
            $createStatement = $create->{'Create Table'} ?? null;

            if ($createStatement) {
                $lines[] = 'DROP TABLE IF EXISTS `' . str_replace('`', '``', $table) . '`;';
                $lines[] = $createStatement . ';';
                $lines[] = '';
            }

            DB::table($table)
                ->orderByRaw('1')
                ->chunk(500, function ($rows) use (&$lines, $pdo, $table): void {
                    foreach ($rows as $row) {
                        $columns = array_keys((array) $row);
                        $values = array_map(
                            fn ($value): string => $value === null ? 'NULL' : $pdo->quote((string) $value),
                            array_values((array) $row),
                        );

                        $lines[] = 'INSERT INTO `' . str_replace('`', '``', $table) . '` (`'
                            . implode('`, `', array_map(fn (string $column): string => str_replace('`', '``', $column), $columns))
                            . '`) VALUES (' . implode(', ', $values) . ');';
                    }
                });

            $lines[] = '';
        }

        $lines[] = 'SET FOREIGN_KEY_CHECKS=1;';
        $lines[] = '';

        return implode(PHP_EOL, $lines);
    }

    private function sqliteDump(): string
    {
        $pdo = DB::connection()->getPdo();
        $tables = DB::select("SELECT name, sql FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%' ORDER BY name");
        $lines = [
            '-- Argnest database backup',
            '-- Created at: ' . now()->toDateTimeString(),
            'PRAGMA foreign_keys=OFF;',
            'BEGIN TRANSACTION;',
            '',
        ];

        foreach ($tables as $tableRow) {
            $table = $tableRow->name;

            if ($tableRow->sql) {
                $lines[] = 'DROP TABLE IF EXISTS "' . str_replace('"', '""', $table) . '";';
                $lines[] = $tableRow->sql . ';';
            }

            DB::table($table)
                ->orderByRaw('1')
                ->chunk(500, function ($rows) use (&$lines, $pdo, $table): void {
                    foreach ($rows as $row) {
                        $columns = array_keys((array) $row);
                        $values = array_map(
                            fn ($value): string => $value === null ? 'NULL' : $pdo->quote((string) $value),
                            array_values((array) $row),
                        );

                        $lines[] = 'INSERT INTO "' . str_replace('"', '""', $table) . '" ("'
                            . implode('", "', array_map(fn (string $column): string => str_replace('"', '""', $column), $columns))
                            . '") VALUES (' . implode(', ', $values) . ');';
                    }
                });

            $lines[] = '';
        }

        $lines[] = 'COMMIT;';
        $lines[] = 'PRAGMA foreign_keys=ON;';
        $lines[] = '';

        return implode(PHP_EOL, $lines);
    }

    private function backupSources(): array
    {
        return [
            ['path' => storage_path('app/public'), 'prefix' => 'storage/app/public'],
            ['path' => storage_path('app/private'), 'prefix' => 'storage/app/private'],
            ['path' => public_path('uploads'), 'prefix' => 'public/uploads'],
            ['path' => public_path('images'), 'prefix' => 'public/images'],
        ];
    }

    private function addDirectoryToZip(ZipArchive $zip, string $directory, string $prefix): void
    {
        $files = File::allFiles($directory, true);

        foreach ($files as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $relativePath = trim(str_replace('\\', '/', $file->getRelativePathname()), '/');
            $zip->addFile($file->getRealPath(), trim($prefix, '/') . '/' . $relativePath);
        }
    }

    private function fileName(): string
    {
        return 'full-backup-' . now()->format('Y-m-d-H-i') . '.zip';
    }
}
