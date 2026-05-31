<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('service_name');
            $table->string('domain_name')->nullable();
            $table->string('hosting_package')->nullable();
            $table->string('server_ip')->nullable();
            $table->string('server_panel')->nullable();
            $table->string('username')->nullable();
            $table->string('password_hint')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_services');
    }
};
