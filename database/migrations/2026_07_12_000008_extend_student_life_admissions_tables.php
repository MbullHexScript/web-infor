<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            $table->string('slug_id')->nullable()->unique()->after('slug');
            $table->string('slug_en')->nullable()->unique()->after('slug_id');
            $table->string('achievement_type')->default('student')->after('title_en')->index();
            $table->string('recipient_name')->nullable()->after('achievement_type');
            $table->json('student_identifiers_json')->nullable()->after('recipient_name');
            $table->foreignId('lecturer_id')->nullable()->after('student_identifiers_json')->constrained('lecturers')->nullOnDelete();
            $table->json('team_members_json')->nullable()->after('lecturer_id');
            $table->string('level')->nullable()->after('team_members_json')->index();
            $table->string('organizer')->nullable()->after('level');
            $table->unsignedSmallInteger('year')->nullable()->after('organizer')->index();
            $table->date('event_date')->nullable()->after('year');
            $table->string('location_id')->nullable()->after('event_date');
            $table->string('location_en')->nullable()->after('location_id');
            $table->string('rank_or_award_id')->nullable()->after('location_en');
            $table->string('rank_or_award_en')->nullable()->after('rank_or_award_id');
            $table->longText('description_id')->nullable()->after('rank_or_award_en');
            $table->longText('description_en')->nullable()->after('description_id');
            $table->string('photo_id')->nullable()->after('description_en');
            $table->string('certificate_id')->nullable()->after('photo_id');
            $table->boolean('certificate_is_public')->default(false)->after('certificate_id');
            $table->string('source_url')->nullable()->after('certificate_is_public');
            $table->string('translation_status')->default('not_available')->after('verification_status')->index();
            $table->boolean('is_featured')->default(false)->after('translation_status')->index();
            $table->boolean('is_public')->default(false)->after('is_featured')->index();
            $table->timestamp('published_at')->nullable()->after('is_public')->index();
            $table->softDeletes();
        });

        Schema::table('scholarships', function (Blueprint $table) {
            $table->string('name_id')->nullable()->after('id');
            $table->string('name_en')->nullable()->after('name_id');
            $table->string('slug_id')->nullable()->unique()->after('slug');
            $table->string('slug_en')->nullable()->unique()->after('slug_id');
            $table->string('provider_id')->nullable()->after('slug_en');
            $table->string('provider_en')->nullable()->after('provider_id');
            $table->longText('description_id')->nullable()->after('provider_en');
            $table->longText('description_en')->nullable()->after('description_id');
            $table->longText('requirements_id')->nullable()->after('description_en');
            $table->longText('requirements_en')->nullable()->after('requirements_id');
            $table->longText('benefits_id')->nullable()->after('requirements_en');
            $table->longText('benefits_en')->nullable()->after('benefits_id');
            $table->string('registration_url')->nullable()->after('benefits_en');
            $table->string('contact_info')->nullable()->after('registration_url');
            $table->timestamp('starts_at')->nullable()->after('contact_info')->index();
            $table->timestamp('ends_at')->nullable()->after('starts_at')->index();
            $table->string('translation_status')->default('not_available')->after('verification_status')->index();
            $table->boolean('is_featured')->default(false)->after('translation_status')->index();
            $table->boolean('is_public')->default(false)->after('is_featured')->index();
            $table->softDeletes();
        });

        Schema::table('competitions', function (Blueprint $table) {
            $table->string('slug_id')->nullable()->unique()->after('slug');
            $table->string('slug_en')->nullable()->unique()->after('slug_id');
            $table->longText('description_id')->nullable()->after('title_en');
            $table->longText('description_en')->nullable()->after('description_id');
            $table->string('organizer')->nullable()->after('description_en');
            $table->string('level')->nullable()->after('organizer')->index();
            $table->string('competition_type')->nullable()->after('level')->index();
            $table->string('registration_url')->nullable()->after('competition_type');
            $table->timestamp('starts_at')->nullable()->after('registration_url')->index();
            $table->timestamp('ends_at')->nullable()->after('starts_at')->index();
            $table->date('event_date')->nullable()->after('ends_at');
            $table->string('location_id')->nullable()->after('event_date');
            $table->string('location_en')->nullable()->after('location_id');
            $table->string('poster_id')->nullable()->after('location_en');
            $table->string('translation_status')->default('not_available')->after('verification_status')->index();
            $table->boolean('is_featured')->default(false)->after('translation_status')->index();
            $table->boolean('is_public')->default(false)->after('is_featured')->index();
            $table->softDeletes();
        });

        Schema::table('alumni_stories', function (Blueprint $table) {
            $table->string('alumni_name')->nullable()->after('id');
            $table->unsignedSmallInteger('graduation_year')->nullable()->after('slug')->index();
            $table->string('study_period')->nullable()->after('graduation_year');
            $table->string('current_position_id')->nullable()->after('study_period');
            $table->string('current_position_en')->nullable()->after('current_position_id');
            $table->string('company')->nullable()->after('current_position_en');
            $table->string('industry_id')->nullable()->after('company');
            $table->string('industry_en')->nullable()->after('industry_id');
            $table->longText('story_id')->nullable()->after('industry_en');
            $table->longText('story_en')->nullable()->after('story_id');
            $table->text('quote_id')->nullable()->after('story_en');
            $table->text('quote_en')->nullable()->after('quote_id');
            $table->string('photo_id')->nullable()->after('quote_en');
            $table->string('linkedin_url')->nullable()->after('photo_id');
            $table->string('personal_website_url')->nullable()->after('linkedin_url');
            $table->boolean('consent_verified')->default(false)->after('personal_website_url')->index();
            $table->string('translation_status')->default('not_available')->after('verification_status')->index();
            $table->boolean('is_featured')->default(false)->after('translation_status')->index();
            $table->boolean('is_public')->default(false)->after('is_featured')->index();
            $table->softDeletes();
        });

        Schema::table('facilities', function (Blueprint $table) {
            $table->string('name_id')->nullable()->after('id');
            $table->string('name_en')->nullable()->after('name_id');
            $table->string('slug_id')->nullable()->unique()->after('slug');
            $table->string('slug_en')->nullable()->unique()->after('slug_id');
            $table->string('category')->nullable()->after('slug_en')->index();
            $table->longText('description_id')->nullable()->after('category');
            $table->longText('description_en')->nullable()->after('description_id');
            $table->string('location_id')->nullable()->after('description_en');
            $table->string('location_en')->nullable()->after('location_id');
            $table->unsignedInteger('capacity')->nullable()->after('location_en');
            $table->longText('features_id')->nullable()->after('capacity');
            $table->longText('features_en')->nullable()->after('features_id');
            $table->string('featured_image_id')->nullable()->after('features_en');
            $table->foreignId('album_id')->nullable()->after('featured_image_id')->constrained('albums')->nullOnDelete();
            $table->string('translation_status')->default('not_available')->after('verification_status')->index();
            $table->boolean('is_featured')->default(false)->after('translation_status')->index();
            $table->boolean('is_public')->default(false)->after('is_featured')->index();
            $table->unsignedSmallInteger('sort_order')->default(0)->after('is_public');
            $table->softDeletes();
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            $table->string('email')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->string('category')->default('general')->after('phone')->index();
            $table->string('subject')->nullable()->after('category');
            $table->longText('message')->nullable()->after('subject');
            $table->string('locale', 5)->default('id')->after('message');
            $table->string('ip_address', 45)->nullable()->after('locale');
            $table->text('user_agent')->nullable()->after('ip_address');
            $table->timestamp('read_at')->nullable()->after('status');
            $table->timestamp('replied_at')->nullable()->after('read_at');
            $table->timestamp('archived_at')->nullable()->after('replied_at');
            $table->unsignedSmallInteger('spam_score')->nullable()->after('archived_at');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->string('slug_id')->nullable()->unique()->after('slug');
            $table->string('slug_en')->nullable()->unique()->after('slug_id');
            $table->string('page_type')->nullable()->after('content_en')->index();
            $table->string('translation_status')->default('not_available')->after('verification_status')->index();
            $table->boolean('is_public')->default(false)->after('translation_status')->index();
        });

        Schema::create('admission_paths', function (Blueprint $table) {
            $table->id();
            $table->string('name_id');
            $table->string('name_en')->nullable();
            $table->string('slug_id')->unique();
            $table->string('slug_en')->unique();
            $table->longText('description_id')->nullable();
            $table->longText('description_en')->nullable();
            $table->longText('requirements_id')->nullable();
            $table->longText('requirements_en')->nullable();
            $table->string('registration_url')->nullable();
            $table->timestamp('starts_at')->nullable()->index();
            $table->timestamp('ends_at')->nullable()->index();
            $table->string('status')->default('upcoming')->index();
            $table->string('verification_status')->default('pending')->index();
            $table->boolean('is_public')->default(false)->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_paths');
    }
};
