<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_name')->nullable();
            $table->string('visitor_email')->nullable();
            $table->string('visitor_phone')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('status')->default('open')->index();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('live_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_chat_session_id')->constrained()->cascadeOnDelete();
            $table->string('sender_type')->index();
            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('message');
            $table->timestamp('created_at')->nullable();
        });

        if (! Schema::hasTable('permissions')) {
            return;
        }

        $now = now();
        $permissionIds = collect([
            ['name' => 'Canli Destek Goruntuleme', 'key' => 'live_chat_view', 'group' => 'Destek'],
            ['name' => 'Canli Destek Yanitlama', 'key' => 'live_chat_reply', 'group' => 'Destek'],
            ['name' => 'Canli Destek Kapatma', 'key' => 'live_chat_close', 'group' => 'Destek'],
        ])->map(function (array $permission) use ($now): int {
            $existingId = DB::table('permissions')->where('key', $permission['key'])->value('id');

            if ($existingId) {
                return (int) $existingId;
            }

            return (int) DB::table('permissions')->insertGetId([
                ...$permission,
                'description' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        if (! Schema::hasTable('roles') || ! Schema::hasTable('role_permissions')) {
            return;
        }

        $managerRoleId = DB::table('roles')->where('slug', 'yonetici')->value('id');

        if (! $managerRoleId) {
            return;
        }

        foreach ($permissionIds as $permissionId) {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('live_chat_messages');
        Schema::dropIfExists('live_chat_sessions');
    }
};
