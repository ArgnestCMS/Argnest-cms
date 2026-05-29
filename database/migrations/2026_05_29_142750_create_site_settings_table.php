<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Argnest');
            $table->string('site_slogan')->nullable();
            $table->text('site_description')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp')->nullable();
            $table->text('address')->nullable();
            $table->text('google_maps_url')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('x_url')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keywords')->nullable();
            $table->longText('kvkk_text')->nullable();
            $table->longText('privacy_policy')->nullable();
            $table->longText('cookie_policy')->nullable();
            $table->longText('information_security_policy')->nullable();
            $table->text('footer_text')->nullable();
            $table->string('copyright_text')->nullable();
            $table->timestamps();
        });

        DB::table('site_settings')->insert([
            'site_name' => 'Argnest',
            'site_slogan' => 'İşletmeniz İçin Modern Dijital Çözümler',
            'email' => 'info@argnest.com.tr',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
