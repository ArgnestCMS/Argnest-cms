<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key')->unique();
            $table->string('group')->index();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['role_id', 'permission_id']);
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'role_id']);
        });

        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'permission_id']);
        });

        $now = now();

        $roles = collect([
            ['name' => 'Yönetici', 'description' => 'Tüm yönetim yetkilerine sahip sistem rolü.', 'is_system' => true],
            ['name' => 'Operasyon', 'description' => null, 'is_system' => false],
            ['name' => 'Teknik Destek', 'description' => null, 'is_system' => false],
            ['name' => 'Muhasebe', 'description' => null, 'is_system' => false],
            ['name' => 'Satış', 'description' => null, 'is_system' => false],
            ['name' => 'Editör', 'description' => null, 'is_system' => false],
        ])->map(fn (array $role): array => [
            ...$role,
            'slug' => Str::slug($role['name']),
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        DB::table('roles')->insert($roles);

        $permissions = [
            ['name' => 'Müşteri Görüntüleme', 'key' => 'customer_view', 'group' => 'Müşteriler'],
            ['name' => 'Müşteri Düzenleme', 'key' => 'customer_edit', 'group' => 'Müşteriler'],
            ['name' => 'Destek Görüntüleme', 'key' => 'support_view', 'group' => 'Destek'],
            ['name' => 'Destek Yanıtlama', 'key' => 'support_reply', 'group' => 'Destek'],
            ['name' => 'Hizmet Görüntüleme', 'key' => 'service_view', 'group' => 'Hizmetler'],
            ['name' => 'Hizmet Düzenleme', 'key' => 'service_edit', 'group' => 'Hizmetler'],
            ['name' => 'Dosya Görüntüleme', 'key' => 'file_view', 'group' => 'Dosyalar'],
            ['name' => 'Dosya Yükleme', 'key' => 'file_upload', 'group' => 'Dosyalar'],
            ['name' => 'Bildirim Yönetimi', 'key' => 'notification_manage', 'group' => 'Bildirimler'],
            ['name' => 'Mail Ayarları Yönetimi', 'key' => 'mail_settings_manage', 'group' => 'Ayarlar'],
            ['name' => 'Site Ayarları Yönetimi', 'key' => 'site_settings_manage', 'group' => 'Ayarlar'],
            ['name' => 'Yedek Yönetimi', 'key' => 'backup_manage', 'group' => 'Sistem'],
            ['name' => 'Admin Yönetimi', 'key' => 'admin_manage', 'group' => 'Yönetim'],
            ['name' => 'Rol Yönetimi', 'key' => 'role_manage', 'group' => 'Yönetim'],
            ['name' => 'Yetki Yönetimi', 'key' => 'permission_manage', 'group' => 'Yönetim'],
        ];

        DB::table('permissions')->insert(collect($permissions)->map(fn (array $permission): array => [
            ...$permission,
            'description' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all());

        $managerRoleId = DB::table('roles')->where('slug', 'yonetici')->value('id');
        $permissionIds = DB::table('permissions')->pluck('id');

        DB::table('role_permissions')->insert($permissionIds->map(fn (int $permissionId): array => [
            'role_id' => $managerRoleId,
            'permission_id' => $permissionId,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all());

        $adminIds = DB::table('users')
            ->where('role', User::ROLE_ADMIN)
            ->pluck('id');

        if ($adminIds->isNotEmpty()) {
            DB::table('user_roles')->insert($adminIds->map(fn (int $userId): array => [
                'user_id' => $userId,
                'role_id' => $managerRoleId,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all());
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
