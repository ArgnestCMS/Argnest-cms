<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('identity_number', 11)->nullable()->after('company_name');
            $table->string('registration_ip')->nullable()->after('identity_number');
            $table->timestamp('last_login_at')->nullable()->after('registration_ip');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'identity_number',
                'registration_ip',
                'last_login_at',
                'last_login_ip',
            ]);
        });
    }
};
