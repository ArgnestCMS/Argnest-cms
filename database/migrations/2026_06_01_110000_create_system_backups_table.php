<?php

use App\Models\SystemBackup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_backups', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('type')->default(SystemBackup::TYPE_FULL);
            $table->string('status')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });

        $now = now();
        $permissions = [
            ['name' => 'Yedek Olusturma', 'key' => 'backup_create'],
            ['name' => 'Yedek Indirme', 'key' => 'backup_download'],
            ['name' => 'Yedek Silme', 'key' => 'backup_delete'],
        ];
        $managerRoleId = DB::table('roles')->where('slug', 'yonetici')->value('id');

        foreach ($permissions as $permission) {
            $permissionId = DB::table('permissions')->where('key', $permission['key'])->value('id');

            if (! $permissionId) {
                $permissionId = DB::table('permissions')->insertGetId([
                    ...$permission,
                    'group' => 'Sistem',
                    'description' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            if ($managerRoleId) {
                DB::table('role_permissions')->insertOrIgnore([
                    'role_id' => $managerRoleId,
                    'permission_id' => $permissionId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        $permissionIds = DB::table('permissions')
            ->whereIn('key', ['backup_create', 'backup_download', 'backup_delete'])
            ->pluck('id');

        if ($permissionIds->isNotEmpty()) {
            DB::table('role_permissions')->whereIn('permission_id', $permissionIds)->delete();
            DB::table('user_permissions')->whereIn('permission_id', $permissionIds)->delete();
            DB::table('permissions')->whereIn('id', $permissionIds)->delete();
        }

        Schema::dropIfExists('system_backups');
    }
};
