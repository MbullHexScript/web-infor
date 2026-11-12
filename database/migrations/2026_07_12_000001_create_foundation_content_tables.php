<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->string('type')->default('text');
            $table->string('group')->default('general')->index();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });

        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description_id')->nullable();
            $table->text('description_en')->nullable();
            $table->string('url')->nullable();
            $table->string('verification_status')->default('pending')->index();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->string('label_id')->unique();
            $table->string('label_en')->nullable();
            $table->string('value')->nullable();
            $table->string('verification_status')->default('pending')->index();
            $table->boolean('is_active')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('quick_links', function (Blueprint $table) {
            $table->id();
            $table->string('label_id')->unique();
            $table->string('label_en');
            $table->string('url');
            $table->string('icon')->nullable();
            $table->boolean('is_external')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('title_id');
            $table->string('title_en')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('legacy_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('source_path')->unique();
            $table->string('target_path');
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->unsignedBigInteger('hit_count')->default(0);
            $table->timestamps();
        });

        Schema::create('content_migrations', function (Blueprint $table) {
            $table->id();
            $table->string('source_url');
            $table->string('content_type');
            $table->string('target_path')->nullable();
            $table->string('verification_status')->default('pending')->index();
            $table->string('translation_status')->default('pending');
            $table->string('media_migration_status')->default('pending');
            $table->text('revision_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_migrations');
        Schema::dropIfExists('legacy_redirects');
        Schema::dropIfExists('homepage_sections');
        Schema::dropIfExists('quick_links');
        Schema::dropIfExists('statistics');
        Schema::dropIfExists('journals');
        Schema::dropIfExists('site_settings');
    }
};
