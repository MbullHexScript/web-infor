<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homepage_sections', function (Blueprint $table) {
            $table->text('subtitle_id')->nullable()->after('title_en');
            $table->text('subtitle_en')->nullable()->after('subtitle_id');
            $table->string('background_style')->default('default')->after('subtitle_en');
            $table->json('settings')->nullable()->after('background_style');
        });

        Schema::table('quick_links', function (Blueprint $table) {
            $table->string('key')->nullable()->unique()->after('id');
        });

        Schema::table('statistics', function (Blueprint $table) {
            $table->string('key')->nullable()->unique()->after('id');
            $table->string('suffix')->nullable()->after('value');
            $table->text('source_note')->nullable()->after('suffix');
        });

        Schema::table('journals', function (Blueprint $table) {
            $table->string('full_name')->nullable()->after('name');
            $table->string('tagline')->nullable()->after('full_name');
            $table->text('scope_id')->nullable()->after('tagline');
            $table->text('scope_en')->nullable()->after('scope_id');
            $table->string('ojs_url')->nullable()->after('url');
            $table->string('status_label')->default('preparation')->after('verification_status');
        });
    }

    public function down(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'tagline', 'scope_id', 'scope_en', 'ojs_url', 'status_label']);
        });

        Schema::table('statistics', function (Blueprint $table) {
            $table->dropColumn(['key', 'suffix', 'source_note']);
        });

        Schema::table('quick_links', function (Blueprint $table) {
            $table->dropColumn('key');
        });

        Schema::table('homepage_sections', function (Blueprint $table) {
            $table->dropColumn(['subtitle_id', 'subtitle_en', 'background_style', 'settings']);
        });
    }
};
