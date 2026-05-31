<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('smtp_host')->nullable()->after('copyright_text');
            $table->unsignedInteger('smtp_port')->nullable()->after('smtp_host');
            $table->string('smtp_username')->nullable()->after('smtp_port');
            $table->text('smtp_password')->nullable()->after('smtp_username');
            $table->string('smtp_encryption')->nullable()->after('smtp_password');
            $table->string('mail_from_address')->nullable()->after('smtp_encryption');
            $table->string('mail_from_name')->nullable()->after('mail_from_address');
            $table->string('admin_notification_email')->nullable()->after('mail_from_name');
            $table->string('support_notification_email')->nullable()->after('admin_notification_email');
            $table->string('sales_notification_email')->nullable()->after('support_notification_email');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'smtp_host',
                'smtp_port',
                'smtp_username',
                'smtp_password',
                'smtp_encryption',
                'mail_from_address',
                'mail_from_name',
                'admin_notification_email',
                'support_notification_email',
                'sales_notification_email',
            ]);
        });
    }
};
