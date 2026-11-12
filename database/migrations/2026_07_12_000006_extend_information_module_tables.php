<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news_categories', function (Blueprint $table) {
            $table->string('name_id')->nullable()->after('id');
            $table->string('name_en')->nullable()->after('name_id');
            $table->string('slug_id')->nullable()->after('slug');
            $table->string('slug_en')->nullable()->after('slug_id');
            $table->text('description_id')->nullable()->after('slug_en');
            $table->text('description_en')->nullable()->after('description_id');
            $table->string('color_token')->nullable()->after('description_en');
            $table->string('icon')->nullable()->after('color_token');
            $table->unsignedSmallInteger('sort_order')->default(0)->after('icon');
            $table->boolean('is_active')->default(true)->after('sort_order');
            $table->softDeletes();
            $table->unique('slug_id');
            $table->unique('slug_en');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')->constrained('news_categories')->nullOnDelete();
            $table->string('slug_id')->nullable()->after('slug');
            $table->string('slug_en')->nullable()->after('slug_id');
            $table->text('excerpt_id')->nullable()->after('title_en');
            $table->text('excerpt_en')->nullable()->after('excerpt_id');
            $table->longText('content_id')->nullable()->after('excerpt_en');
            $table->longText('content_en')->nullable()->after('content_id');
            $table->string('featured_image_id')->nullable()->after('content_en');
            $table->string('author_name')->nullable()->after('featured_image_id');
            $table->string('source_name')->nullable()->after('author_name');
            $table->string('source_url')->nullable()->after('source_name');
            $table->string('translation_status')->default('not_available')->after('verification_status')->index();
            $table->boolean('is_featured')->default(false)->after('translation_status')->index();
            $table->boolean('is_pinned')->default(false)->after('is_featured')->index();
            $table->unsignedBigInteger('view_count')->default(0)->after('is_pinned');
            $table->timestamp('published_at')->nullable()->after('view_count')->index();
            $table->timestamp('scheduled_at')->nullable()->after('published_at')->index();
            $table->unique('slug_id');
            $table->unique('slug_en');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->string('slug_id')->nullable()->after('slug');
            $table->string('slug_en')->nullable()->after('slug_id');
            $table->longText('content_id')->nullable()->after('title_en');
            $table->longText('content_en')->nullable()->after('content_id');
            $table->text('excerpt_id')->nullable()->after('content_en');
            $table->text('excerpt_en')->nullable()->after('excerpt_id');
            $table->string('priority')->default('normal')->after('excerpt_en')->index();
            $table->string('attachment_id')->nullable()->after('priority');
            $table->string('external_url')->nullable()->after('attachment_id');
            $table->timestamp('starts_at')->nullable()->after('external_url')->index();
            $table->timestamp('expires_at')->nullable()->after('starts_at')->index();
            $table->string('translation_status')->default('not_available')->after('verification_status')->index();
            $table->boolean('is_featured')->default(false)->after('translation_status')->index();
            $table->boolean('is_pinned')->default(false)->after('is_featured')->index();
            $table->timestamp('published_at')->nullable()->after('is_pinned')->index();
            $table->unique('slug_id');
            $table->unique('slug_en');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->string('slug_id')->nullable()->after('slug');
            $table->string('slug_en')->nullable()->after('slug_id');
            $table->longText('description_id')->nullable()->after('title_en');
            $table->longText('description_en')->nullable()->after('description_id');
            $table->timestamp('start_at')->nullable()->after('description_en')->index();
            $table->timestamp('end_at')->nullable()->after('start_at')->index();
            $table->string('timezone')->default('Asia/Jakarta')->after('end_at');
            $table->string('location_id')->nullable()->after('timezone');
            $table->string('location_en')->nullable()->after('location_id');
            $table->string('organizer_id')->nullable()->after('location_en');
            $table->string('organizer_en')->nullable()->after('organizer_id');
            $table->string('poster_id')->nullable()->after('organizer_en');
            $table->string('registration_url')->nullable()->after('poster_id');
            $table->string('meeting_url')->nullable()->after('registration_url');
            $table->string('status_override')->nullable()->after('meeting_url')->index();
            $table->boolean('is_featured')->default(false)->after('status_override')->index();
            $table->boolean('is_public')->default(true)->after('is_featured')->index();
            $table->string('translation_status')->default('not_available')->after('verification_status')->index();
            $table->timestamp('published_at')->nullable()->after('translation_status')->index();
            $table->unique('slug_id');
            $table->unique('slug_en');
        });

        Schema::table('albums', function (Blueprint $table) {
            $table->string('slug_id')->nullable()->after('slug');
            $table->string('slug_en')->nullable()->after('slug_id');
            $table->text('description_id')->nullable()->after('title_en');
            $table->text('description_en')->nullable()->after('description_id');
            $table->string('cover_image_id')->nullable()->after('description_en');
            $table->date('event_date')->nullable()->after('cover_image_id')->index();
            $table->string('category')->nullable()->after('event_date')->index();
            $table->boolean('is_public')->default(false)->after('category')->index();
            $table->boolean('is_featured')->default(false)->after('is_public')->index();
            $table->string('translation_status')->default('not_available')->after('verification_status')->index();
            $table->softDeletes();
            $table->unique('slug_id');
            $table->unique('slug_en');
        });

        Schema::table('album_media', function (Blueprint $table) {
            $table->foreignId('album_id')->nullable()->after('id')->constrained('albums')->cascadeOnDelete();
            $table->foreignId('media_id')->nullable()->after('album_id')->constrained('media')->nullOnDelete();
            $table->text('caption_id')->nullable()->after('media_id');
            $table->text('caption_en')->nullable()->after('caption_id');
            $table->unsignedSmallInteger('sort_order')->default(0)->after('caption_en');
        });

        Schema::table('media', function (Blueprint $table) {
            $table->string('file_name')->nullable()->after('slug');
            $table->string('disk')->default('public')->after('file_name');
            $table->string('path')->nullable()->after('disk');
            $table->string('mime_type')->nullable()->after('path');
            $table->unsignedBigInteger('size')->nullable()->after('mime_type');
            $table->unsignedInteger('width')->nullable()->after('size');
            $table->unsignedInteger('height')->nullable()->after('width');
            $table->string('alt_id')->nullable()->after('height');
            $table->string('alt_en')->nullable()->after('alt_id');
            $table->string('external_url')->nullable()->after('alt_en');
            $table->string('provider')->nullable()->after('external_url');
            $table->string('checksum')->nullable()->after('provider')->index();
            $table->boolean('is_public')->default(false)->after('checksum')->index();
        });

        Schema::table('document_categories', function (Blueprint $table) {
            $table->string('name_id')->nullable()->after('id');
            $table->string('name_en')->nullable()->after('name_id');
            $table->string('slug_id')->nullable()->after('slug');
            $table->string('slug_en')->nullable()->after('slug_id');
            $table->text('description_id')->nullable()->after('slug_en');
            $table->text('description_en')->nullable()->after('description_id');
            $table->unsignedSmallInteger('sort_order')->default(0)->after('description_en');
            $table->boolean('is_active')->default(true)->after('sort_order');
            $table->softDeletes();
            $table->unique('slug_id');
            $table->unique('slug_en');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')->constrained('document_categories')->nullOnDelete();
            $table->string('slug_id')->nullable()->after('slug');
            $table->string('slug_en')->nullable()->after('slug_id');
            $table->text('description_id')->nullable()->after('title_en');
            $table->text('description_en')->nullable()->after('description_id');
            $table->string('file_id')->nullable()->after('description_en');
            $table->string('file_name')->nullable()->after('file_id');
            $table->string('mime_type')->nullable()->after('file_name');
            $table->unsignedBigInteger('file_size')->nullable()->after('mime_type');
            $table->string('file_type')->nullable()->after('file_size')->index();
            $table->string('version')->nullable()->after('file_type');
            $table->unsignedSmallInteger('year')->nullable()->after('version')->index();
            $table->date('effective_date')->nullable()->after('year');
            $table->string('document_status')->default('current')->after('effective_date')->index();
            $table->boolean('is_public')->default(false)->after('document_status')->index();
            $table->unsignedBigInteger('download_count')->default(0)->after('is_public');
            $table->string('checksum')->nullable()->after('download_count')->index();
            $table->string('translation_status')->default('not_available')->after('verification_status')->index();
            $table->timestamp('published_at')->nullable()->after('translation_status')->index();
            $table->unique('slug_id');
            $table->unique('slug_en');
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->string('category')->nullable()->after('id')->index();
            $table->string('question_id')->nullable()->after('category');
            $table->string('question_en')->nullable()->after('question_id');
            $table->longText('answer_id')->nullable()->after('question_en');
            $table->longText('answer_en')->nullable()->after('answer_id');
            $table->unsignedSmallInteger('sort_order')->default(0)->after('answer_en');
            $table->boolean('is_active')->default(true)->after('sort_order')->index();
            $table->boolean('is_featured')->default(false)->after('is_active')->index();
            $table->string('translation_status')->default('not_available')->after('verification_status')->index();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        foreach ([
            'faqs', 'documents', 'document_categories', 'media', 'album_media', 'albums',
            'events', 'announcements', 'news', 'news_categories',
        ] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                foreach ($this->columns($tableName) as $column) {
                    if (Schema::hasColumn($tableName, $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }

    /**
     * @return array<int, string>
     */
    private function columns(string $tableName): array
    {
        return match ($tableName) {
            'news_categories' => ['name_id', 'name_en', 'slug_id', 'slug_en', 'description_id', 'description_en', 'color_token', 'icon', 'sort_order', 'is_active', 'deleted_at'],
            'news' => ['category_id', 'slug_id', 'slug_en', 'excerpt_id', 'excerpt_en', 'content_id', 'content_en', 'featured_image_id', 'author_name', 'source_name', 'source_url', 'translation_status', 'is_featured', 'is_pinned', 'view_count', 'published_at', 'scheduled_at'],
            'announcements' => ['slug_id', 'slug_en', 'content_id', 'content_en', 'excerpt_id', 'excerpt_en', 'priority', 'attachment_id', 'external_url', 'starts_at', 'expires_at', 'translation_status', 'is_featured', 'is_pinned', 'published_at'],
            'events' => ['slug_id', 'slug_en', 'description_id', 'description_en', 'start_at', 'end_at', 'timezone', 'location_id', 'location_en', 'organizer_id', 'organizer_en', 'poster_id', 'registration_url', 'meeting_url', 'status_override', 'is_featured', 'is_public', 'translation_status', 'published_at'],
            'albums' => ['slug_id', 'slug_en', 'description_id', 'description_en', 'cover_image_id', 'event_date', 'category', 'is_public', 'is_featured', 'translation_status', 'deleted_at'],
            'album_media' => ['album_id', 'media_id', 'caption_id', 'caption_en', 'sort_order'],
            'media' => ['file_name', 'disk', 'path', 'mime_type', 'size', 'width', 'height', 'alt_id', 'alt_en', 'external_url', 'provider', 'checksum', 'is_public'],
            'document_categories' => ['name_id', 'name_en', 'slug_id', 'slug_en', 'description_id', 'description_en', 'sort_order', 'is_active', 'deleted_at'],
            'documents' => ['category_id', 'slug_id', 'slug_en', 'description_id', 'description_en', 'file_id', 'file_name', 'mime_type', 'file_size', 'file_type', 'version', 'year', 'effective_date', 'document_status', 'is_public', 'download_count', 'checksum', 'translation_status', 'published_at'],
            'faqs' => ['category', 'question_id', 'question_en', 'answer_id', 'answer_en', 'sort_order', 'is_active', 'is_featured', 'translation_status', 'deleted_at'],
            default => [],
        };
    }
};
