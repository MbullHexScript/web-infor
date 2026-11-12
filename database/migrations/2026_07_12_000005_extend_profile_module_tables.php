<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('key')->nullable()->unique()->after('id');
            $table->string('excerpt_id')->nullable()->after('title_en');
            $table->string('excerpt_en')->nullable()->after('excerpt_id');
            $table->longText('content_id')->nullable()->after('excerpt_en');
            $table->longText('content_en')->nullable()->after('content_id');
            $table->boolean('is_active')->default(true)->index()->after('content_en');
            $table->timestamp('published_at')->nullable()->index()->after('is_active');
        });

        Schema::table('lecturers', function (Blueprint $table) {
            $table->string('full_name')->nullable()->after('id');
            $table->string('academic_title')->nullable()->after('full_name');
            $table->string('employment_status')->nullable()->after('academic_title');
            $table->string('academic_position_id')->nullable()->after('employment_status');
            $table->string('academic_position_en')->nullable()->after('academic_position_id');
            $table->text('expertise_id')->nullable()->after('academic_position_en');
            $table->text('expertise_en')->nullable()->after('expertise_id');
            $table->longText('biography_id')->nullable()->after('expertise_en');
            $table->longText('biography_en')->nullable()->after('biography_id');
            $table->longText('education_id')->nullable()->after('biography_en');
            $table->longText('education_en')->nullable()->after('education_id');
            $table->string('email')->nullable()->index()->after('education_en');
            $table->string('nidn')->nullable()->after('email');
            $table->string('photo_id')->nullable()->after('nidn');
            $table->string('photo_alt')->nullable()->after('photo_id');
            $table->string('google_scholar_url')->nullable()->after('photo_alt');
            $table->string('sinta_url')->nullable()->after('google_scholar_url');
            $table->string('orcid_url')->nullable()->after('sinta_url');
            $table->string('scopus_url')->nullable()->after('orcid_url');
            $table->string('personal_website_url')->nullable()->after('scopus_url');
            $table->string('github_url')->nullable()->after('personal_website_url');
            $table->unsignedSmallInteger('sort_order')->default(0)->index()->after('github_url');
            $table->boolean('is_active')->default(true)->index()->after('sort_order');
            $table->boolean('is_featured')->default(false)->index()->after('is_active');
        });

        Schema::table('management_positions', function (Blueprint $table) {
            $table->foreignId('lecturer_id')->nullable()->index()->after('id');
            $table->foreignId('staff_id')->nullable()->index()->after('lecturer_id');
            $table->string('person_name')->nullable()->after('staff_id');
            $table->string('position_id')->nullable()->after('person_name');
            $table->string('position_en')->nullable()->after('position_id');
            $table->unsignedSmallInteger('period_start')->nullable()->after('position_en');
            $table->unsignedSmallInteger('period_end')->nullable()->after('period_start');
            $table->foreignId('parent_id')->nullable()->index()->after('period_end');
            $table->string('photo_id')->nullable()->after('parent_id');
            $table->unsignedSmallInteger('sort_order')->default(0)->index()->after('photo_id');
            $table->boolean('is_active')->default(true)->index()->after('sort_order');
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->string('full_name')->nullable()->after('id');
            $table->string('position_id')->nullable()->after('full_name');
            $table->string('position_en')->nullable()->after('position_id');
            $table->string('email')->nullable()->after('position_en');
            $table->string('photo_id')->nullable()->after('email');
            $table->text('biography_id')->nullable()->after('photo_id');
            $table->text('biography_en')->nullable()->after('biography_id');
            $table->unsignedSmallInteger('sort_order')->default(0)->index()->after('biography_en');
            $table->boolean('is_active')->default(true)->index()->after('sort_order');
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            $table->string('partner_type')->nullable()->index()->after('name');
            $table->string('logo_id')->nullable()->after('partner_type');
            $table->text('description_id')->nullable()->after('logo_id');
            $table->text('description_en')->nullable()->after('description_id');
            $table->string('website_url')->nullable()->after('description_en');
            $table->date('cooperation_start')->nullable()->after('website_url');
            $table->date('cooperation_end')->nullable()->after('cooperation_start');
            $table->text('scope_id')->nullable()->after('cooperation_end');
            $table->text('scope_en')->nullable()->after('scope_id');
            $table->string('document_id')->nullable()->after('scope_en');
            $table->boolean('is_active')->default(true)->index()->after('document_id');
            $table->boolean('is_featured')->default(false)->index()->after('is_active');
            $table->unsignedSmallInteger('sort_order')->default(0)->index()->after('is_featured');
        });

        Schema::create('expertise_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name_id');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->text('description_id')->nullable();
            $table->text('description_en')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('expertise_area_lecturer', function (Blueprint $table) {
            $table->foreignId('lecturer_id')->index();
            $table->foreignId('expertise_area_id')->index();
            $table->unique(['lecturer_id', 'expertise_area_id']);
        });

        Schema::create('lecturer_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->index();
            $table->string('degree');
            $table->string('field_of_study_id')->nullable();
            $table->string('field_of_study_en')->nullable();
            $table->string('institution')->nullable();
            $table->unsignedSmallInteger('graduation_year')->nullable();
            $table->string('country')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('accreditations', function (Blueprint $table) {
            $table->id();
            $table->string('accreditation_body')->nullable();
            $table->string('level_or_grade')->nullable();
            $table->string('decision_number')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->string('certificate_media_id')->nullable();
            $table->text('description_id')->nullable();
            $table->text('description_en')->nullable();
            $table->boolean('is_current')->default(false)->index();
            $table->boolean('is_public')->default(false)->index();
            $table->string('verification_status')->default('pending')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accreditations');
        Schema::dropIfExists('lecturer_educations');
        Schema::dropIfExists('expertise_area_lecturer');
        Schema::dropIfExists('expertise_areas');
    }
};
