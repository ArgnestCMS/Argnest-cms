<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action')->index();
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });

        $now = now();
        $permissionId = DB::table('permissions')->where('key', 'security_logs_view')->value('id');

        if (! $permissionId) {
            $permissionId = DB::table('permissions')->insertGetId([
                'name' => 'Guvenlik Loglari Goruntuleme',
                'key' => 'security_logs_view',
                'group' => 'Sistem',
                'description' => 'Admin guvenlik ve islem loglarini goruntuleme yetkisi.',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $managerRoleId = DB::table('roles')->where('slug', 'yonetici')->value('id');

        if ($managerRoleId) {
            DB::table('role_permissions')->insertOrIgnore([
                'role_id' => $managerRoleId,
                'permission_id' => $permissionId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        $permissionId = DB::table('permissions')->where('key', 'security_logs_view')->value('id');

        if ($permissionId) {
            DB::table('role_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('user_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('permissions')->where('id', $permissionId)->delete();
        }

        Schema::dropIfExists('admin_activity_logs');
    }
};
