<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach ($this->tables() as $tableName) {
            Schema::create($tableName, function (Blueprint $table) use ($tableName) {
                $table->id();
                $table->string('title_id')->nullable();
                $table->string('title_en')->nullable();
                $table->string('slug')->nullable()->index();
                $table->string('status')->default('draft')->index();
                $table->string('verification_status')->default('pending')->index();
                $table->json('metadata')->nullable();
                $table->timestamps();

                if (in_array($tableName, $this->softDeleteTables(), true)) {
                    $table->softDeletes();
                }
            });
        }
    }

    public function down(): void
    {
        foreach (array_reverse($this->tables()) as $tableName) {
            Schema::dropIfExists($tableName);
        }
    }

    /**
     * @return array<int, string>
     */
    private function tables(): array
    {
        return [
            'admin_activity_logs',
            'pages',
            'hero_slides',
            'lecturers',
            'management_positions',
            'staff',
            'curricula',
            'courses',
            'course_prerequisites',
            'specializations',
            'graduate_profiles',
            'rps_documents',
            'academic_calendars',
            'class_schedules',
            'news_categories',
            'news',
            'announcements',
            'events',
            'research_projects',
            'community_services',
            'publications',
            'digital_products',
            'intellectual_properties',
            'expertise_groups',
            'achievements',
            'scholarships',
            'competitions',
            'alumni_stories',
            'partners',
            'facilities',
            'albums',
            'album_media',
            'media',
            'document_categories',
            'documents',
            'social_links',
            'faqs',
            'contact_messages',
            'seo_metadata',
        ];
    }

    /**
     * @return array<int, string>
     */
    private function softDeleteTables(): array
    {
        return [
            'pages',
            'lecturers',
            'staff',
            'courses',
            'rps_documents',
            'news',
            'announcements',
            'events',
            'media',
            'documents',
            'contact_messages',
        ];
    }
};
