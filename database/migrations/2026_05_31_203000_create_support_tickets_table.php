<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('support_tickets')) {
            return;
        }

        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('ticket_no')->unique();
            $table->string('subject');
            $table->string('category')->nullable();
            $table->string('status')->default('open')->index();
            $table->string('priority')->default('normal')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        //
    }
};
