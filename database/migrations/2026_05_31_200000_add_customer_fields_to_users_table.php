<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('company_name')->nullable()->after('phone');
            $table->string('role')->default(User::ROLE_CUSTOMER)->after('password')->index();
            $table->boolean('is_active')->default(true)->after('role');
        });

        DB::table('users')->update([
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropColumn(['phone', 'company_name', 'role', 'is_active']);
        });
    }
};
