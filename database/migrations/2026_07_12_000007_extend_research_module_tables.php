<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expertise_groups', function (Blueprint $table) {
            $table->string('name_id')->nullable()->after('id');
            $table->string('name_en')->nullable()->after('name_id');
            $table->string('slug_id')->nullable()->after('slug');
            $table->string('slug_en')->nullable()->after('slug_id');
            $table->text('description_id')->nullable()->after('title_en');
            $table->text('description_en')->nullable()->after('description_id');
            $table->text('research_focus_id')->nullable()->after('description_en');
            $table->text('research_focus_en')->nullable()->after('research_focus_id');
            $table->string('icon')->nullable()->after('research_focus_en');
            $table->string('image_id')->nullable()->after('icon');
            $table->foreignId('coordinator_lecturer_id')->nullable()->after('image_id')->constrained('lecturers')->nullOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0)->after('coordinator_lecturer_id');
            $table->boolean('is_active')->default(false)->after('sort_order')->index();
            $table->softDeletes();
            $table->unique('slug_id');
            $table->unique('slug_en');
        });

        Schema::create('expertise_group_lecturer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expertise_group_id')->constrained('expertise_groups')->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained('lecturers')->cascadeOnDelete();
            $table->string('role')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['expertise_group_id', 'lecturer_id']);
        });

        Schema::table('research_projects', function (Blueprint $table) {
            $table->string('slug_id')->nullable()->after('slug');
            $table->string('slug_en')->nullable()->after('slug_id');
            $table->longText('abstract_id')->nullable()->after('title_en');
            $table->longText('abstract_en')->nullable()->after('abstract_id');
            $table->unsignedSmallInteger('year')->nullable()->after('abstract_en')->index();
            $table->date('start_date')->nullable()->after('year');
            $table->date('end_date')->nullable()->after('start_date');
            $table->string('funding_source_id')->nullable()->after('end_date');
            $table->string('funding_source_en')->nullable()->after('funding_source_id');
            $table->decimal('funding_amount', 16, 2)->nullable()->after('funding_source_en');
            $table->string('lead_researcher_name')->nullable()->after('funding_amount');
            $table->foreignId('lead_lecturer_id')->nullable()->after('lead_researcher_name')->constrained('lecturers')->nullOnDelete();
            $table->json('team_members_json')->nullable()->after('lead_lecturer_id');
            $table->foreignId('expertise_group_id')->nullable()->after('team_members_json')->constrained('expertise_groups')->nullOnDelete();
            $table->text('output_id')->nullable()->after('expertise_group_id');
            $table->text('output_en')->nullable()->after('output_id');
            $table->string('featured_image_id')->nullable()->after('output_en');
            $table->foreignId('document_id')->nullable()->after('featured_image_id')->constrained('documents')->nullOnDelete();
            $table->string('external_url')->nullable()->after('document_id');
            $table->string('translation_status')->default('not_available')->after('verification_status')->index();
            $table->boolean('is_featured')->default(false)->after('translation_status')->index();
            $table->boolean('is_public')->default(false)->after('is_featured')->index();
            $table->timestamp('published_at')->nullable()->after('is_public')->index();
            $table->softDeletes();
            $table->unique('slug_id');
            $table->unique('slug_en');
        });

        Schema::table('community_services', function (Blueprint $table) {
            $table->string('slug_id')->nullable()->after('slug');
            $table->string('slug_en')->nullable()->after('slug_id');
            $table->longText('description_id')->nullable()->after('title_en');
            $table->longText('description_en')->nullable()->after('description_id');
            $table->unsignedSmallInteger('year')->nullable()->after('description_en')->index();
            $table->date('start_date')->nullable()->after('year');
            $table->date('end_date')->nullable()->after('start_date');
            $table->string('location_id')->nullable()->after('end_date');
            $table->string('location_en')->nullable()->after('location_id');
            $table->string('lead_person_name')->nullable()->after('location_en');
            $table->foreignId('lead_lecturer_id')->nullable()->after('lead_person_name')->constrained('lecturers')->nullOnDelete();
            $table->json('team_members_json')->nullable()->after('lead_lecturer_id');
            $table->text('beneficiaries_id')->nullable()->after('team_members_json');
            $table->text('beneficiaries_en')->nullable()->after('beneficiaries_id');
            $table->text('outputs_id')->nullable()->after('beneficiaries_en');
            $table->text('outputs_en')->nullable()->after('outputs_id');
            $table->string('featured_image_id')->nullable()->after('outputs_en');
            $table->foreignId('album_id')->nullable()->after('featured_image_id')->constrained('albums')->nullOnDelete();
            $table->string('external_url')->nullable()->after('album_id');
            $table->string('translation_status')->default('not_available')->after('verification_status')->index();
            $table->boolean('is_featured')->default(false)->after('translation_status')->index();
            $table->boolean('is_public')->default(false)->after('is_featured')->index();
            $table->timestamp('published_at')->nullable()->after('is_public')->index();
            $table->softDeletes();
            $table->unique('slug_id');
            $table->unique('slug_en');
        });

        Schema::table('publications', function (Blueprint $table) {
            $table->string('title')->nullable()->after('id');
            $table->text('abstract')->nullable()->after('title');
            $table->text('authors')->nullable()->after('abstract');
            $table->string('publication_type')->default('other')->after('authors')->index();
            $table->string('journal_or_conference')->nullable()->after('publication_type');
            $table->string('publisher')->nullable()->after('journal_or_conference');
            $table->unsignedSmallInteger('year')->nullable()->after('publisher')->index();
            $table->string('volume')->nullable()->after('year');
            $table->string('issue')->nullable()->after('volume');
            $table->string('pages')->nullable()->after('issue');
            $table->string('doi')->nullable()->after('pages')->index();
            $table->string('url')->nullable()->after('doi');
            $table->foreignId('lecturer_id')->nullable()->after('url')->constrained('lecturers')->nullOnDelete();
            $table->foreignId('research_project_id')->nullable()->after('lecturer_id')->constrained('research_projects')->nullOnDelete();
            $table->foreignId('expertise_group_id')->nullable()->after('research_project_id')->constrained('expertise_groups')->nullOnDelete();
            $table->json('indexing_json')->nullable()->after('expertise_group_id');
            $table->unsignedInteger('citation_count')->nullable()->after('indexing_json');
            $table->boolean('is_featured')->default(false)->after('verification_status')->index();
            $table->boolean('is_public')->default(false)->after('is_featured')->index();
            $table->softDeletes();
        });

        Schema::table('digital_products', function (Blueprint $table) {
            $table->string('slug_id')->nullable()->after('slug');
            $table->string('slug_en')->nullable()->after('slug_id');
            $table->longText('description_id')->nullable()->after('title_en');
            $table->longText('description_en')->nullable()->after('description_id');
            $table->string('product_type')->default('other')->after('description_en')->index();
            $table->unsignedSmallInteger('year')->nullable()->after('product_type')->index();
            $table->json('team_members_json')->nullable()->after('year');
            $table->json('technologies_json')->nullable()->after('team_members_json');
            $table->foreignId('related_research_id')->nullable()->after('technologies_json')->constrained('research_projects')->nullOnDelete();
            $table->string('featured_image_id')->nullable()->after('related_research_id');
            $table->foreignId('gallery_album_id')->nullable()->after('featured_image_id')->constrained('albums')->nullOnDelete();
            $table->string('demo_url')->nullable()->after('gallery_album_id');
            $table->string('repository_url')->nullable()->after('demo_url');
            $table->string('documentation_url')->nullable()->after('repository_url');
            $table->string('license')->nullable()->after('documentation_url');
            $table->boolean('is_featured')->default(false)->after('verification_status')->index();
            $table->boolean('is_public')->default(false)->after('is_featured')->index();
            $table->timestamp('published_at')->nullable()->after('is_public')->index();
            $table->softDeletes();
            $table->unique('slug_id');
            $table->unique('slug_en');
        });

        Schema::table('intellectual_properties', function (Blueprint $table) {
            $table->string('title')->nullable()->after('id');
            $table->string('registration_number')->nullable()->after('title')->index();
            $table->string('type')->default('other')->after('registration_number')->index();
            $table->string('holder')->nullable()->after('type');
            $table->text('inventors')->nullable()->after('holder');
            $table->date('registration_date')->nullable()->after('inventors');
            $table->date('issue_date')->nullable()->after('registration_date');
            $table->string('ip_status')->nullable()->after('issue_date')->index();
            $table->foreignId('certificate_id')->nullable()->after('ip_status')->constrained('documents')->nullOnDelete();
            $table->text('description_id')->nullable()->after('certificate_id');
            $table->text('description_en')->nullable()->after('description_id');
            $table->foreignId('related_product_id')->nullable()->after('description_en')->constrained('digital_products')->nullOnDelete();
            $table->foreignId('related_research_id')->nullable()->after('related_product_id')->constrained('research_projects')->nullOnDelete();
            $table->boolean('is_public')->default(false)->after('verification_status')->index();
            $table->softDeletes();
        });

        Schema::table('journals', function (Blueprint $table) {
            $table->string('code')->nullable()->unique()->after('id');
            $table->string('slug_id')->nullable()->unique()->after('slug');
            $table->string('slug_en')->nullable()->unique()->after('slug_id');
            $table->string('tagline_id')->nullable()->after('full_name');
            $table->string('tagline_en')->nullable()->after('tagline_id');
            $table->string('publisher')->nullable()->after('scope_en');
            $table->string('editor_in_chief')->nullable()->after('publisher');
            $table->unsignedSmallInteger('established_year')->nullable()->after('editor_in_chief');
            $table->string('publication_frequency_id')->nullable()->after('established_year');
            $table->string('publication_frequency_en')->nullable()->after('publication_frequency_id');
            $table->json('publication_months_json')->nullable()->after('publication_frequency_en');
            $table->string('print_issn')->nullable()->after('publication_months_json');
            $table->string('electronic_issn')->nullable()->after('print_issn');
            $table->string('accreditation_status')->nullable()->after('electronic_issn');
            $table->json('indexing_json')->nullable()->after('accreditation_status');
            $table->string('author_guidelines_url')->nullable()->after('indexing_json');
            $table->string('article_template_url')->nullable()->after('author_guidelines_url');
            $table->string('editorial_email')->nullable()->after('article_template_url');
            $table->string('logo_id')->nullable()->after('editorial_email');
            $table->string('cover_image_id')->nullable()->after('logo_id');
            $table->string('accent_style')->default('purple')->after('cover_image_id');
            $table->string('status')->default('preparing')->after('status_label')->index();
        });

        Schema::create('research_partnerships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();
            $table->foreignId('research_project_id')->nullable()->constrained('research_projects')->nullOnDelete();
            $table->foreignId('community_service_id')->nullable()->constrained('community_services')->nullOnDelete();
            $table->string('title_id')->nullable();
            $table->string('title_en')->nullable();
            $table->text('scope_id')->nullable();
            $table->text('scope_en')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignId('document_id')->nullable()->constrained('documents')->nullOnDelete();
            $table->boolean('is_public')->default(false)->index();
            $table->string('verification_status')->default('pending')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_partnerships');
        Schema::dropIfExists('expertise_group_lecturer');
    }
};
