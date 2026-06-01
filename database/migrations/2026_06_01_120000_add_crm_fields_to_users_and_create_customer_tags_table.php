<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->nullable()->after('is_active')->index();
            $table->timestamp('last_contact_at')->nullable()->after('last_login_ip');
            $table->longText('admin_notes')->nullable()->after('last_contact_at');
        });

        Schema::create('customer_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_tag_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_tag_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['customer_tag_id', 'user_id']);
        });

        $now = now();
        DB::table('customer_tags')->insert([
            ['name' => 'VIP', 'color' => '#f59e0b', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kurumsal', 'color' => '#2563eb', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hosting', 'color' => '#0ea5e9', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Yazilim', 'color' => '#7c3aed', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'SEO', 'color' => '#16a34a', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Oncelikli Destek', 'color' => '#dc2626', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_tag_user');
        Schema::dropIfExists('customer_tags');

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn(['status', 'last_contact_at', 'admin_notes']);
        });
    }
};
