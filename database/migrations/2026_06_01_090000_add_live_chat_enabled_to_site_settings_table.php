<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->boolean('live_chat_enabled')
                ->default(false)
                ->after('customer_email_verification_enabled');
        });

        if (! Schema::hasTable('permissions')) {
            return;
        }

        $now = now();
        $permissionId = DB::table('permissions')->where('key', 'live_chat_manage')->value('id');

        if (! $permissionId) {
            $permissionId = DB::table('permissions')->insertGetId([
                'name' => 'Canli Destek Yonetimi',
                'key' => 'live_chat_manage',
                'group' => 'Destek',
                'description' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        if (! Schema::hasTable('roles') || ! Schema::hasTable('role_permissions')) {
            return;
        }

        $managerRoleId = DB::table('roles')->where('slug', 'yonetici')->value('id');

        if (! $managerRoleId) {
            return;
        }

        $exists = DB::table('role_permissions')
            ->where('role_id', $managerRoleId)
            ->where('permission_id', $permissionId)
            ->exists();

        if (! $exists) {
            DB::table('role_permissions')->insert([
                'role_id' => $managerRoleId,
                'permission_id' => $permissionId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('live_chat_enabled');
        });
    }
};
