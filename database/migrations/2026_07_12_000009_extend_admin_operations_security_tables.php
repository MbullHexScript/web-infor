<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->extendMedia();
        $this->extendSeoMetadata();
        $this->extendRedirects();
        $this->extendAuditLog();
        $this->extendUsers();

        Schema::create('media_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained('media')->cascadeOnDelete();
            $table->string('mediable_type');
            $table->unsignedBigInteger('mediable_id');
            $table->string('field_name');
            $table->timestamps();
            $table->unique(['media_id', 'mediable_type', 'mediable_id', 'field_name'], 'media_usage_unique');
            $table->index(['mediable_type', 'mediable_id']);
        });

        Schema::create('media_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained('media')->cascadeOnDelete();
            $table->unsignedInteger('version_number');
            $table->string('path');
            $table->string('checksum')->index();
            $table->unsignedBigInteger('file_size');
            $table->timestamps();
            $table->unique(['media_id', 'version_number']);
        });

        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_title_id')->nullable();
            $table->string('site_title_en')->nullable();
            $table->text('default_meta_description_id')->nullable();
            $table->text('default_meta_description_en')->nullable();
            $table->string('title_separator')->default('|');
            $table->foreignId('default_og_image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->boolean('robots_index')->default(true);
            $table->boolean('robots_follow')->default(true);
            $table->string('google_site_verification')->nullable();
            $table->string('bing_site_verification')->nullable();
            $table->json('social_profiles_json')->nullable();
            $table->json('organization_schema_json')->nullable();
            $table->boolean('sitemap_enabled')->default(true);
            $table->boolean('news_sitemap_enabled')->default(false);
            $table->timestamps();
        });

        Schema::create('navigation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('navigation_items')->nullOnDelete();
            $table->string('group')->default('main')->index();
            $table->string('label_id');
            $table->string('label_en')->nullable();
            $table->string('url');
            $table->string('icon')->nullable();
            $table->boolean('is_external')->default(false);
            $table->boolean('open_in_new_tab')->default(false);
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('backup_records', function (Blueprint $table) {
            $table->id();
            $table->string('backup_type')->default('manual')->index();
            $table->string('status')->default('pending')->index();
            $table->string('disk')->default('local');
            $table->string('path')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('checksum')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backup_records');
        Schema::dropIfExists('navigation_items');
        Schema::dropIfExists('seo_settings');
        Schema::dropIfExists('media_versions');
        Schema::dropIfExists('media_usages');
    }

    private function extendMedia(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $this->string($table, 'original_name', 'id');
            $this->string($table, 'stored_name', 'original_name');
            $this->string($table, 'extension', 'mime_type', 20);
            $this->unsignedBigInteger($table, 'file_size', 'extension');
            $this->string($table, 'alt_text_id', 'height');
            $this->string($table, 'alt_text_en', 'alt_text_id');
            $this->text($table, 'caption_id', 'title_en');
            $this->text($table, 'caption_en', 'caption_id');
            $this->string($table, 'source_url', 'caption_en');
            $this->string($table, 'source_name', 'source_url');
            $this->text($table, 'copyright_note', 'source_name');
            $this->foreignId($table, 'uploaded_by', 'copyright_note', 'users');
            $this->string($table, 'optimization_status', 'uploaded_by')->default('pending');
            $this->softDeletes($table);
        });
    }

    private function extendSeoMetadata(): void
    {
        Schema::table('seo_metadata', function (Blueprint $table) {
            $this->string($table, 'seoable_type', 'id');
            $this->unsignedBigInteger($table, 'seoable_id', 'seoable_type');
            $this->string($table, 'meta_title_id', 'seoable_id');
            $this->string($table, 'meta_title_en', 'meta_title_id');
            $this->text($table, 'meta_description_id', 'meta_title_en');
            $this->text($table, 'meta_description_en', 'meta_description_id');
            $this->string($table, 'canonical_url', 'meta_description_en');
            $this->string($table, 'og_title_id', 'canonical_url');
            $this->string($table, 'og_title_en', 'og_title_id');
            $this->text($table, 'og_description_id', 'og_title_en');
            $this->text($table, 'og_description_en', 'og_description_id');
            $this->foreignId($table, 'og_image_id', 'og_description_en', 'media');
            $this->string($table, 'robots', 'og_image_id');
            $this->json($table, 'structured_data_json', 'robots');
            $table->unique(['seoable_type', 'seoable_id'], 'seo_metadata_seoable_unique');
        });
    }

    private function extendRedirects(): void
    {
        Schema::table('legacy_redirects', function (Blueprint $table) {
            $this->string($table, 'old_path', 'id');
            $this->string($table, 'new_path', 'old_path');
            $this->unsignedSmallInteger($table, 'redirect_code', 'new_path')->default(301);
            $this->timestamp($table, 'last_hit_at', 'hit_count');
            $this->boolean($table, 'is_active', 'last_hit_at')->default(true);
            $this->text($table, 'note', 'is_active');
            $this->softDeletes($table);
        });
    }

    private function extendAuditLog(): void
    {
        Schema::table('admin_activity_logs', function (Blueprint $table) {
            $this->foreignId($table, 'admin_id', 'id', 'users');
            $this->string($table, 'action', 'admin_id');
            $this->string($table, 'module', 'action');
            $this->string($table, 'record_type', 'module');
            $this->unsignedBigInteger($table, 'record_id', 'record_type');
            $this->text($table, 'description', 'record_id');
            $this->json($table, 'old_values_json', 'description');
            $this->json($table, 'new_values_json', 'old_values_json');
            $this->string($table, 'ip_address', 'new_values_json', 45);
            $this->text($table, 'user_agent', 'ip_address');
            $this->string($table, 'request_id', 'user_agent');
        });
    }

    private function extendUsers(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $this->json($table, 'two_factor_recovery_codes', 'two_factor_confirmed_at');
            $this->timestamp($table, 'password_changed_at', 'last_login_at');
        });
    }

    private function string(Blueprint $table, string $column, string $after, int $length = 255): mixed
    {
        if (! Schema::hasColumn($table->getTable(), $column)) {
            return $table->string($column, $length)->nullable()->after($after);
        }

        return new class
        {
            public function default(mixed $value): void {}
        };
    }

    private function text(Blueprint $table, string $column, string $after): void
    {
        if (! Schema::hasColumn($table->getTable(), $column)) {
            $table->text($column)->nullable()->after($after);
        }
    }

    private function json(Blueprint $table, string $column, string $after): void
    {
        if (! Schema::hasColumn($table->getTable(), $column)) {
            $table->json($column)->nullable()->after($after);
        }
    }

    private function unsignedBigInteger(Blueprint $table, string $column, string $after): void
    {
        if (! Schema::hasColumn($table->getTable(), $column)) {
            $table->unsignedBigInteger($column)->nullable()->after($after);
        }
    }

    private function unsignedSmallInteger(Blueprint $table, string $column, string $after): mixed
    {
        if (! Schema::hasColumn($table->getTable(), $column)) {
            return $table->unsignedSmallInteger($column)->after($after);
        }

        return new class
        {
            public function default(mixed $value): void {}
        };
    }

    private function boolean(Blueprint $table, string $column, string $after): mixed
    {
        if (! Schema::hasColumn($table->getTable(), $column)) {
            return $table->boolean($column)->after($after);
        }

        return new class
        {
            public function default(mixed $value): void {}
        };
    }

    private function timestamp(Blueprint $table, string $column, string $after): void
    {
        if (! Schema::hasColumn($table->getTable(), $column)) {
            $table->timestamp($column)->nullable()->after($after);
        }
    }

    private function softDeletes(Blueprint $table): void
    {
        if (! Schema::hasColumn($table->getTable(), 'deleted_at')) {
            $table->softDeletes();
        }
    }

    private function foreignId(Blueprint $table, string $column, string $after, string $references): void
    {
        if (! Schema::hasColumn($table->getTable(), $column)) {
            $table->foreignId($column)->nullable()->after($after)->constrained($references)->nullOnDelete();
        }
    }
};
