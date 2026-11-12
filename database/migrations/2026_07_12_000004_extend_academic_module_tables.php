<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('curricula', function (Blueprint $table) {
            $table->string('code')->nullable()->unique()->after('id');
            $table->string('name_id')->nullable()->after('code');
            $table->string('name_en')->nullable()->after('name_id');
            $table->string('slug_id')->nullable()->unique()->after('name_en');
            $table->string('slug_en')->nullable()->unique()->after('slug_id');
            $table->unsignedSmallInteger('year')->nullable()->index()->after('slug_en');
            $table->unsignedSmallInteger('total_credits')->default(0)->after('year');
            $table->unsignedTinyInteger('total_semesters')->default(8)->after('total_credits');
            $table->text('description_id')->nullable()->after('total_semesters');
            $table->text('description_en')->nullable()->after('description_id');
            $table->foreignId('document_id')->nullable()->after('description_en');
            $table->boolean('is_active')->default(false)->index()->after('document_id');
            $table->timestamp('published_at')->nullable()->index()->after('is_active');
        });

        Schema::table('specializations', function (Blueprint $table) {
            $table->string('code')->nullable()->unique()->after('id');
            $table->string('name_id')->nullable()->after('code');
            $table->string('name_en')->nullable()->after('name_id');
            $table->string('slug_id')->nullable()->unique()->after('name_en');
            $table->string('slug_en')->nullable()->unique()->after('slug_id');
            $table->text('description_id')->nullable()->after('slug_en');
            $table->text('description_en')->nullable()->after('description_id');
            $table->text('competencies_id')->nullable()->after('description_en');
            $table->text('competencies_en')->nullable()->after('competencies_id');
            $table->text('career_id')->nullable()->after('competencies_en');
            $table->text('career_en')->nullable()->after('career_id');
            $table->string('icon')->nullable()->after('career_en');
            $table->string('image_id')->nullable()->after('icon');
            $table->string('accent_style')->nullable()->after('image_id');
            $table->unsignedSmallInteger('sort_order')->default(0)->index()->after('accent_style');
            $table->boolean('is_active')->default(true)->index()->after('sort_order');
        });

        Schema::table('graduate_profiles', function (Blueprint $table) {
            $table->foreignId('curriculum_id')->nullable()->index()->after('id');
            $table->string('code')->nullable()->index()->after('curriculum_id');
            $table->text('description_id')->nullable()->after('title_en');
            $table->text('description_en')->nullable()->after('description_id');
            $table->text('career_examples_id')->nullable()->after('description_en');
            $table->text('career_examples_en')->nullable()->after('career_examples_id');
            $table->string('icon')->nullable()->after('career_examples_en');
            $table->unsignedSmallInteger('sort_order')->default(0)->index()->after('icon');
            $table->boolean('is_active')->default(true)->index()->after('sort_order');
            $table->unique(['curriculum_id', 'code']);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('curriculum_id')->nullable()->index()->after('id');
            $table->foreignId('specialization_id')->nullable()->index()->after('curriculum_id');
            $table->string('code')->nullable()->index()->after('specialization_id');
            $table->string('name_id')->nullable()->after('code');
            $table->string('name_en')->nullable()->after('name_id');
            $table->string('slug_id')->nullable()->index()->after('name_en');
            $table->string('slug_en')->nullable()->index()->after('slug_id');
            $table->unsignedTinyInteger('semester')->nullable()->index()->after('slug_en');
            $table->unsignedTinyInteger('credits')->default(0)->after('semester');
            $table->string('course_type')->default('mandatory')->index()->after('credits');
            $table->string('category')->nullable()->index()->after('course_type');
            $table->text('description_id')->nullable()->after('category');
            $table->text('description_en')->nullable()->after('description_id');
            $table->text('learning_materials_id')->nullable()->after('description_en');
            $table->text('learning_materials_en')->nullable()->after('learning_materials_id');
            $table->text('software_tools_id')->nullable()->after('learning_materials_en');
            $table->text('software_tools_en')->nullable()->after('software_tools_id');
            $table->boolean('is_active')->default(true)->index()->after('software_tools_en');
            $table->boolean('is_featured')->default(false)->index()->after('is_active');
            $table->unsignedSmallInteger('sort_order')->default(0)->index()->after('is_featured');
            $table->unique(['curriculum_id', 'code']);
        });

        Schema::table('course_prerequisites', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->index()->after('id');
            $table->foreignId('prerequisite_course_id')->nullable()->index()->after('course_id');
            $table->string('requirement_type')->default('required')->after('prerequisite_course_id');
            $table->string('minimum_grade')->nullable()->after('requirement_type');
            $table->text('notes_id')->nullable()->after('minimum_grade');
            $table->text('notes_en')->nullable()->after('notes_id');
        });

        Schema::table('rps_documents', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->index()->after('id');
            $table->foreignId('curriculum_id')->nullable()->index()->after('course_id');
            $table->string('version')->nullable()->after('title_en');
            $table->string('academic_year')->nullable()->index()->after('version');
            $table->string('semester_type')->nullable()->index()->after('academic_year');
            $table->string('coordinator_name')->nullable()->after('semester_type');
            $table->date('approved_date')->nullable()->after('coordinator_name');
            $table->string('file_id')->nullable()->after('approved_date');
            $table->unsignedBigInteger('file_size')->default(0)->after('file_id');
            $table->string('checksum', 64)->nullable()->index()->after('file_size');
            $table->unsignedBigInteger('download_count')->default(0)->after('checksum');
            $table->boolean('is_public')->default(false)->index()->after('download_count');
            $table->boolean('is_current')->default(false)->index()->after('is_public');
            $table->timestamp('published_at')->nullable()->index()->after('is_current');
        });

        Schema::create('curriculum_imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_id')->nullable()->index();
            $table->string('original_filename');
            $table->string('stored_path')->nullable();
            $table->string('checksum', 64)->index();
            $table->string('mode')->index();
            $table->string('status')->index();
            $table->json('sheets_processed')->nullable();
            $table->unsignedInteger('rows_processed')->default(0);
            $table->unsignedInteger('rows_created')->default(0);
            $table->unsignedInteger('rows_updated')->default(0);
            $table->unsignedInteger('rows_skipped')->default(0);
            $table->unsignedInteger('warnings_count')->default(0);
            $table->unsignedInteger('errors_count')->default(0);
            $table->json('summary_json')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curriculum_imports');
    }
};
