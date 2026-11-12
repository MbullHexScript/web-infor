<?php

use App\Http\Controllers\Admin\AcademicController as AdminAcademicController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\Admin\InformationController as AdminInformationController;
use App\Http\Controllers\Admin\OperationsController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\QualityController;
use App\Http\Controllers\Admin\ResearchController as AdminResearchController;
use App\Http\Controllers\Admin\StudentLifeController as AdminStudentLifeController;
use App\Http\Controllers\Admin\TwoFactorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicSite\AcademicController;
use App\Http\Controllers\PublicSite\HealthController;
use App\Http\Controllers\PublicSite\HomeController;
use App\Http\Controllers\PublicSite\InformationController;
use App\Http\Controllers\PublicSite\PlaceholderPageController;
use App\Http\Controllers\PublicSite\ProfileController as PublicProfileController;
use App\Http\Controllers\PublicSite\ResearchController;
use App\Http\Controllers\PublicSite\StudentLifeController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::redirect('/', '/id');
Route::get('/health', HealthController::class)->name('health');
Route::any('/register', fn () => abort(404));

Route::get('/{locale}', HomeController::class)->whereIn('locale', ['id', 'en'])->name('home');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('two-factor', [TwoFactorController::class, 'challenge'])->name('two-factor.challenge');
    Route::post('two-factor', [TwoFactorController::class, 'verify'])->name('two-factor.verify');
});

Route::prefix('admin')->middleware(['auth', 'two-factor.verified'])->group(function () {
    Route::get('/', DashboardController::class)->name('admin.dashboard');
    Route::get('academic', [AdminAcademicController::class, 'dashboard'])->name('admin.academic.dashboard');
    Route::get('academic/curriculum-imports', [AdminAcademicController::class, 'imports'])->name('admin.academic.imports');
    Route::post('academic/curriculum-imports/inspect', [AdminAcademicController::class, 'inspectImport'])->name('admin.academic.imports.inspect');
    Route::post('academic/curriculum-imports/commit', [AdminAcademicController::class, 'commitImport'])->name('admin.academic.imports.commit');
    Route::get('rps', [AdminAcademicController::class, 'rps'])->name('admin.rps.index');
    Route::post('rps', [AdminAcademicController::class, 'storeRps'])->name('admin.rps.store');
    Route::get('profile-module', [AdminProfileController::class, 'dashboard'])->name('admin.profile-module.dashboard');
    Route::post('profile-module/lecturers/import-legacy', [AdminProfileController::class, 'importLecturers'])->name('admin.profile-module.lecturers.import-legacy');
    Route::get('information', [AdminInformationController::class, 'dashboard'])->name('admin.information.dashboard');
    Route::get('news', [AdminInformationController::class, 'dashboard'])->name('admin.news.index');
    Route::get('announcements', [AdminInformationController::class, 'dashboard'])->name('admin.announcements.index');
    Route::get('events', [AdminInformationController::class, 'dashboard'])->name('admin.events.index');
    Route::get('gallery', [AdminInformationController::class, 'dashboard'])->name('admin.gallery.index');
    Route::get('documents', [AdminInformationController::class, 'dashboard'])->name('admin.documents.index');
    Route::get('faqs', [AdminInformationController::class, 'dashboard'])->name('admin.faqs.index');
    Route::post('information/legacy/inspect', [AdminInformationController::class, 'inspectLegacy'])->name('admin.information.legacy.inspect');
    Route::post('information/legacy/import', [AdminInformationController::class, 'importLegacy'])->name('admin.information.legacy.import');
    Route::get('research', [AdminResearchController::class, 'dashboard'])->name('admin.research.dashboard');
    Route::get('research/projects', [AdminResearchController::class, 'dashboard'])->name('admin.research.projects.index');
    Route::get('research/community-services', [AdminResearchController::class, 'dashboard'])->name('admin.research.community-services.index');
    Route::get('research/publications', [AdminResearchController::class, 'dashboard'])->name('admin.research.publications.index');
    Route::get('research/products', [AdminResearchController::class, 'dashboard'])->name('admin.research.products.index');
    Route::get('research/intellectual-properties', [AdminResearchController::class, 'dashboard'])->name('admin.research.intellectual-properties.index');
    Route::get('research/expertise-groups', [AdminResearchController::class, 'dashboard'])->name('admin.research.expertise-groups.index');
    Route::get('research/partnerships', [AdminResearchController::class, 'dashboard'])->name('admin.research.partnerships.index');
    Route::get('research/journals', [AdminResearchController::class, 'dashboard'])->name('admin.research.journals.index');
    Route::post('research/legacy/inspect', [AdminResearchController::class, 'inspectLegacy'])->name('admin.research.legacy.inspect');
    Route::post('research/legacy/import', [AdminResearchController::class, 'importLegacy'])->name('admin.research.legacy.import');
    Route::get('student-life', [AdminStudentLifeController::class, 'dashboard'])->name('admin.student-life.dashboard');
    Route::get('student-life/campus-life', [AdminStudentLifeController::class, 'dashboard'])->name('admin.student-life.campus-life.index');
    Route::get('student-life/achievements', [AdminStudentLifeController::class, 'dashboard'])->name('admin.student-life.achievements.index');
    Route::get('student-life/scholarships', [AdminStudentLifeController::class, 'dashboard'])->name('admin.student-life.scholarships.index');
    Route::get('student-life/competitions', [AdminStudentLifeController::class, 'dashboard'])->name('admin.student-life.competitions.index');
    Route::get('student-life/mbkm-internships', [AdminStudentLifeController::class, 'dashboard'])->name('admin.student-life.mbkm.index');
    Route::get('student-life/alumni', [AdminStudentLifeController::class, 'dashboard'])->name('admin.student-life.alumni.index');
    Route::get('student-life/hmps', [AdminStudentLifeController::class, 'dashboard'])->name('admin.student-life.hmps.index');
    Route::get('facilities', [AdminStudentLifeController::class, 'facilities'])->name('admin.facilities.index');
    Route::get('admissions', [AdminStudentLifeController::class, 'admissions'])->name('admin.admissions.index');
    Route::get('contact', [AdminStudentLifeController::class, 'contact'])->name('admin.contact.index');
    Route::post('contact/messages/{message}/{status}', [AdminStudentLifeController::class, 'updateMessageStatus'])->whereNumber('message')->name('admin.contact.messages.status');
    Route::delete('contact/messages/{message}', [AdminStudentLifeController::class, 'deleteMessage'])->whereNumber('message')->name('admin.contact.messages.delete');
    Route::get('contact/messages/export.csv', [AdminStudentLifeController::class, 'exportMessages'])->name('admin.contact.messages.export');
    Route::post('student-life/legacy/inspect', [AdminStudentLifeController::class, 'inspectLegacy'])->name('admin.student-life.legacy.inspect');
    Route::post('student-life/legacy/import', [AdminStudentLifeController::class, 'importLegacy'])->name('admin.student-life.legacy.import');
    Route::get('homepage', [HomepageController::class, 'edit'])->name('admin.homepage.edit');
    Route::patch('homepage/sections', [HomepageController::class, 'updateSections'])->name('admin.homepage.sections.update');
    Route::patch('homepage/quick-links', [HomepageController::class, 'updateQuickLinks'])->name('admin.homepage.quick-links.update');
    Route::patch('homepage/statistics', [HomepageController::class, 'updateStatistics'])->name('admin.homepage.statistics.update');
    Route::get('security/two-factor', [TwoFactorController::class, 'edit'])->name('admin.two-factor.edit');
    Route::post('security/two-factor', [TwoFactorController::class, 'enable'])->name('admin.two-factor.enable');
    Route::delete('security/two-factor', [TwoFactorController::class, 'disable'])->name('admin.two-factor.disable');
    Route::get('media', [OperationsController::class, 'media'])->name('admin.media.index');
    Route::post('media', [OperationsController::class, 'storeMedia'])->name('admin.media.store');
    Route::patch('media/{media}', [OperationsController::class, 'updateMedia'])->whereNumber('media')->name('admin.media.update');
    Route::post('media/{media}/replace', [OperationsController::class, 'replaceMedia'])->whereNumber('media')->name('admin.media.replace');
    Route::delete('media/{media}', [OperationsController::class, 'deleteMedia'])->whereNumber('media')->name('admin.media.delete');
    Route::get('seo', [OperationsController::class, 'seo'])->name('admin.seo.index');
    Route::patch('seo/settings', [OperationsController::class, 'updateSeoSettings'])->name('admin.seo.settings.update');
    Route::post('seo/sitemap', [OperationsController::class, 'generateSitemap'])->name('admin.seo.sitemap.generate');
    Route::get('redirects', [OperationsController::class, 'redirects'])->name('admin.redirects.index');
    Route::post('redirects', [OperationsController::class, 'storeRedirect'])->name('admin.redirects.store');
    Route::delete('redirects/{redirect}', [OperationsController::class, 'deleteRedirect'])->whereNumber('redirect')->name('admin.redirects.delete');
    Route::get('settings', [OperationsController::class, 'settings'])->name('admin.settings.index');
    Route::patch('settings', [OperationsController::class, 'updateSetting'])->name('admin.settings.update');
    Route::get('settings/navigation', [OperationsController::class, 'settings'])->name('admin.settings.navigation');
    Route::post('settings/navigation', [OperationsController::class, 'storeNavigation'])->name('admin.settings.navigation.store');
    Route::get('settings/social-links', [OperationsController::class, 'settings'])->name('admin.settings.social-links');
    Route::get('audit-log', [OperationsController::class, 'auditLog'])->name('admin.audit-log.index');
    Route::get('account', [OperationsController::class, 'account'])->name('admin.account.index');
    Route::patch('account', [OperationsController::class, 'updateAccount'])->name('admin.account.update');
    Route::post('account/recovery-codes', [OperationsController::class, 'regenerateRecoveryCodes'])->name('admin.account.recovery-codes');
    Route::get('operations', [OperationsController::class, 'backups'])->name('admin.operations.index');
    Route::post('operations/backups', [OperationsController::class, 'runBackup'])->name('admin.backups.run');
    Route::post('operations/maintenance', [OperationsController::class, 'toggleMaintenance'])->name('admin.maintenance.toggle');
    Route::get('quality', [QualityController::class, 'index'])->name('admin.quality.index');
    Route::get('quality/content-verification', [QualityController::class, 'contentVerification'])->name('admin.quality.content-verification');
    Route::get('quality/rps-coverage', [QualityController::class, 'rpsCoverage'])->name('admin.quality.rps-coverage');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/id/profil', [PublicProfileController::class, 'landing'])->defaults('locale', 'id')->name('profile.landing.id');
Route::get('/en/profile', [PublicProfileController::class, 'landing'])->defaults('locale', 'en')->name('profile.landing.en');
Route::get('/id/profil/struktur-pengelola', [PublicProfileController::class, 'management'])->defaults('locale', 'id')->name('profile.management.id');
Route::get('/en/profile/management', [PublicProfileController::class, 'management'])->defaults('locale', 'en')->name('profile.management.en');
Route::get('/id/profil/dosen', [PublicProfileController::class, 'lecturers'])->defaults('locale', 'id')->name('profile.lecturers.id');
Route::get('/en/profile/lecturers', [PublicProfileController::class, 'lecturers'])->defaults('locale', 'en')->name('profile.lecturers.en');
Route::get('/id/profil/dosen/{slug}', [PublicProfileController::class, 'lecturerDetail'])->defaults('locale', 'id')->name('profile.lecturers.show.id');
Route::get('/en/profile/lecturers/{slug}', [PublicProfileController::class, 'lecturerDetail'])->defaults('locale', 'en')->name('profile.lecturers.show.en');
Route::get('/id/profil/tenaga-kependidikan', [PublicProfileController::class, 'staff'])->defaults('locale', 'id')->name('profile.staff.id');
Route::get('/en/profile/staff', [PublicProfileController::class, 'staff'])->defaults('locale', 'en')->name('profile.staff.en');
Route::get('/id/profil/profil-lulusan', [PublicProfileController::class, 'graduateProfiles'])->defaults('locale', 'id')->name('profile.graduate-profiles.id');
Route::get('/en/profile/graduate-profiles', [PublicProfileController::class, 'graduateProfiles'])->defaults('locale', 'en')->name('profile.graduate-profiles.en');
Route::get('/id/profil/akreditasi', [PublicProfileController::class, 'accreditation'])->defaults('locale', 'id')->name('profile.accreditation.id');
Route::get('/en/profile/accreditation', [PublicProfileController::class, 'accreditation'])->defaults('locale', 'en')->name('profile.accreditation.en');
Route::get('/id/profil/kerja-sama', [PublicProfileController::class, 'partnerships'])->defaults('locale', 'id')->name('profile.partnerships.id');
Route::get('/en/profile/partnerships', [PublicProfileController::class, 'partnerships'])->defaults('locale', 'en')->name('profile.partnerships.en');
Route::get('/id/profil/{page}', [PublicProfileController::class, 'page'])->defaults('locale', 'id')->whereIn('page', ['tentang', 'sejarah', 'visi-misi-tujuan', 'sambutan'])->name('profile.page.id');
Route::get('/en/profile/{page}', [PublicProfileController::class, 'page'])->defaults('locale', 'en')->whereIn('page', ['about', 'history', 'vision-mission-objectives', 'head-message'])->name('profile.page.en');

Route::get('/id/akademik/kurikulum', [AcademicController::class, 'curriculum'])->defaults('locale', 'id')->name('academic.curriculum.id');
Route::get('/en/academics/curriculum', [AcademicController::class, 'curriculum'])->defaults('locale', 'en')->name('academic.curriculum.en');
Route::get('/id/akademik/mata-kuliah', [AcademicController::class, 'courses'])->defaults('locale', 'id')->name('academic.courses.id');
Route::get('/en/academics/courses', [AcademicController::class, 'courses'])->defaults('locale', 'en')->name('academic.courses.en');
Route::get('/id/akademik/mata-kuliah/{slug}', [AcademicController::class, 'courseDetail'])->defaults('locale', 'id')->name('academic.courses.show.id');
Route::get('/en/academics/courses/{slug}', [AcademicController::class, 'courseDetail'])->defaults('locale', 'en')->name('academic.courses.show.en');
Route::get('/id/akademik/peminatan', [AcademicController::class, 'specializations'])->defaults('locale', 'id')->name('academic.specializations.id');
Route::get('/en/academics/specializations', [AcademicController::class, 'specializations'])->defaults('locale', 'en')->name('academic.specializations.en');
Route::get('/id/akademik/peminatan/{slug}', [AcademicController::class, 'specializationDetail'])->defaults('locale', 'id')->name('academic.specializations.show.id');
Route::get('/en/academics/specializations/{slug}', [AcademicController::class, 'specializationDetail'])->defaults('locale', 'en')->name('academic.specializations.show.en');
Route::get('/id/akademik/rps', [AcademicController::class, 'rps'])->defaults('locale', 'id')->name('academic.rps.id');
Route::get('/en/academics/course-plans', [AcademicController::class, 'rps'])->defaults('locale', 'en')->name('academic.rps.en');
Route::get('/id/akademik/rps/{id}/preview', [AcademicController::class, 'rpsPreview'])->defaults('locale', 'id')->name('academic.rps.preview.id');
Route::get('/en/academics/course-plans/{id}/preview', [AcademicController::class, 'rpsPreview'])->defaults('locale', 'en')->name('academic.rps.preview.en');
Route::get('/akademik/rps/{id}/download', [AcademicController::class, 'rpsDownload'])->name('academic.rps.download');
Route::get('/id/akademik/kalender-akademik', [AcademicController::class, 'academicCalendar'])->defaults('locale', 'id')->name('academic.calendar.id');
Route::get('/en/academics/academic-calendar', [AcademicController::class, 'academicCalendar'])->defaults('locale', 'en')->name('academic.calendar.en');
Route::get('/id/akademik/jadwal', [AcademicController::class, 'classSchedule'])->defaults('locale', 'id')->name('academic.schedule.id');
Route::get('/en/academics/class-schedule', [AcademicController::class, 'classSchedule'])->defaults('locale', 'en')->name('academic.schedule.en');
Route::get('/id/akademik/dokumen', [AcademicController::class, 'documents'])->defaults('locale', 'id')->name('academic.documents.id');
Route::get('/en/academics/documents', [AcademicController::class, 'documents'])->defaults('locale', 'en')->name('academic.documents.en');

Route::get('/id/informasi', [InformationController::class, 'landing'])->defaults('locale', 'id')->name('information.landing.id');
Route::get('/en/information', [InformationController::class, 'landing'])->defaults('locale', 'en')->name('information.landing.en');
Route::get('/id/informasi/berita', [InformationController::class, 'news'])->defaults('locale', 'id')->name('information.news.id');
Route::get('/en/information/news', [InformationController::class, 'news'])->defaults('locale', 'en')->name('information.news.en');
Route::get('/id/informasi/berita/{slug}', [InformationController::class, 'newsDetail'])->defaults('locale', 'id')->name('information.news.show.id');
Route::get('/en/information/news/{slug}', [InformationController::class, 'newsDetail'])->defaults('locale', 'en')->name('information.news.show.en');
Route::get('/id/informasi/pengumuman', [InformationController::class, 'announcements'])->defaults('locale', 'id')->name('information.announcements.id');
Route::get('/en/information/announcements', [InformationController::class, 'announcements'])->defaults('locale', 'en')->name('information.announcements.en');
Route::get('/id/informasi/pengumuman/{slug}', [InformationController::class, 'announcementDetail'])->defaults('locale', 'id')->name('information.announcements.show.id');
Route::get('/en/information/announcements/{slug}', [InformationController::class, 'announcementDetail'])->defaults('locale', 'en')->name('information.announcements.show.en');
Route::get('/id/informasi/agenda', [InformationController::class, 'events'])->defaults('locale', 'id')->name('information.events.id');
Route::get('/en/information/events', [InformationController::class, 'events'])->defaults('locale', 'en')->name('information.events.en');
Route::get('/id/informasi/agenda/{slug}', [InformationController::class, 'eventDetail'])->defaults('locale', 'id')->name('information.events.show.id');
Route::get('/en/information/events/{slug}', [InformationController::class, 'eventDetail'])->defaults('locale', 'en')->name('information.events.show.en');
Route::get('/events/{id}/ics', [InformationController::class, 'eventIcs'])->whereNumber('id')->name('information.events.ics');
Route::get('/id/informasi/galeri', [InformationController::class, 'gallery'])->defaults('locale', 'id')->name('information.gallery.id');
Route::get('/en/information/gallery', [InformationController::class, 'gallery'])->defaults('locale', 'en')->name('information.gallery.en');
Route::get('/id/informasi/galeri/{slug}', [InformationController::class, 'galleryDetail'])->defaults('locale', 'id')->name('information.gallery.show.id');
Route::get('/en/information/gallery/{slug}', [InformationController::class, 'galleryDetail'])->defaults('locale', 'en')->name('information.gallery.show.en');
Route::get('/id/informasi/unduhan', [InformationController::class, 'downloads'])->defaults('locale', 'id')->name('information.downloads.id');
Route::get('/en/information/downloads', [InformationController::class, 'downloads'])->defaults('locale', 'en')->name('information.downloads.en');
Route::get('/documents/{id}/download', [InformationController::class, 'downloadDocument'])->whereNumber('id')->name('information.documents.download');
Route::get('/id/informasi/faq', [InformationController::class, 'faq'])->defaults('locale', 'id')->name('information.faq.id');
Route::get('/en/information/faq', [InformationController::class, 'faq'])->defaults('locale', 'en')->name('information.faq.en');
Route::get('/id/pencarian', [InformationController::class, 'search'])->defaults('locale', 'id')->name('search.id');
Route::get('/en/search', [InformationController::class, 'search'])->defaults('locale', 'en')->name('search.en');

Route::get('/id/riset', [ResearchController::class, 'landing'])->defaults('locale', 'id')->name('research.landing.id');
Route::get('/en/research', [ResearchController::class, 'landing'])->defaults('locale', 'en')->name('research.landing.en');
Route::get('/id/riset/penelitian', [ResearchController::class, 'projects'])->defaults('locale', 'id')->name('research.projects.id');
Route::get('/en/research/projects', [ResearchController::class, 'projects'])->defaults('locale', 'en')->name('research.projects.en');
Route::get('/id/riset/penelitian/{slug}', [ResearchController::class, 'projectDetail'])->defaults('locale', 'id')->name('research.projects.show.id');
Route::get('/en/research/projects/{slug}', [ResearchController::class, 'projectDetail'])->defaults('locale', 'en')->name('research.projects.show.en');
Route::get('/id/riset/pengabdian', [ResearchController::class, 'communityServices'])->defaults('locale', 'id')->name('research.services.id');
Route::get('/en/research/community-service', [ResearchController::class, 'communityServices'])->defaults('locale', 'en')->name('research.services.en');
Route::get('/id/riset/pengabdian/{slug}', [ResearchController::class, 'communityServiceDetail'])->defaults('locale', 'id')->name('research.services.show.id');
Route::get('/en/research/community-service/{slug}', [ResearchController::class, 'communityServiceDetail'])->defaults('locale', 'en')->name('research.services.show.en');
Route::get('/id/riset/publikasi', [ResearchController::class, 'publications'])->defaults('locale', 'id')->name('research.publications.id');
Route::get('/en/research/publications', [ResearchController::class, 'publications'])->defaults('locale', 'en')->name('research.publications.en');
Route::get('/research/publications/{id}/citation/{format}', [ResearchController::class, 'citation'])->whereNumber('id')->name('research.publications.citation');
Route::get('/id/riset/produk', [ResearchController::class, 'products'])->defaults('locale', 'id')->name('research.products.id');
Route::get('/en/research/products', [ResearchController::class, 'products'])->defaults('locale', 'en')->name('research.products.en');
Route::get('/id/riset/produk/{slug}', [ResearchController::class, 'productDetail'])->defaults('locale', 'id')->name('research.products.show.id');
Route::get('/en/research/products/{slug}', [ResearchController::class, 'productDetail'])->defaults('locale', 'en')->name('research.products.show.en');
Route::get('/id/riset/hki', [ResearchController::class, 'intellectualProperties'])->defaults('locale', 'id')->name('research.ip.id');
Route::get('/en/research/intellectual-property', [ResearchController::class, 'intellectualProperties'])->defaults('locale', 'en')->name('research.ip.en');
Route::get('/id/riset/kelompok-keahlian', [ResearchController::class, 'expertiseGroups'])->defaults('locale', 'id')->name('research.groups.id');
Route::get('/en/research/expertise-groups', [ResearchController::class, 'expertiseGroups'])->defaults('locale', 'en')->name('research.groups.en');
Route::get('/id/riset/kelompok-keahlian/{slug}', [ResearchController::class, 'expertiseGroupDetail'])->defaults('locale', 'id')->name('research.groups.show.id');
Route::get('/en/research/expertise-groups/{slug}', [ResearchController::class, 'expertiseGroupDetail'])->defaults('locale', 'en')->name('research.groups.show.en');
Route::get('/id/riset/rumah-jurnal', [ResearchController::class, 'journals'])->defaults('locale', 'id')->name('research.journals.id');
Route::get('/en/research/journals', [ResearchController::class, 'journals'])->defaults('locale', 'en')->name('research.journals.en');
Route::get('/id/riset/rumah-jurnal/{slug}', [ResearchController::class, 'journalDetail'])->defaults('locale', 'id')->name('research.journals.show.id');
Route::get('/en/research/journals/{slug}', [ResearchController::class, 'journalDetail'])->defaults('locale', 'en')->name('research.journals.show.en');
Route::get('/id/riset/kerja-sama', [ResearchController::class, 'partnerships'])->defaults('locale', 'id')->name('research.partnerships.id');
Route::get('/en/research/partnerships', [ResearchController::class, 'partnerships'])->defaults('locale', 'en')->name('research.partnerships.en');

Route::get('/id/kemahasiswaan', [StudentLifeController::class, 'landing'])->defaults('locale', 'id')->name('student-life.landing.id');
Route::get('/en/student-life', [StudentLifeController::class, 'landing'])->defaults('locale', 'en')->name('student-life.landing.en');
Route::get('/id/kemahasiswaan/kehidupan-mahasiswa', [StudentLifeController::class, 'campusLife'])->defaults('locale', 'id')->name('student-life.campus.id');
Route::get('/en/student-life/campus-life', [StudentLifeController::class, 'campusLife'])->defaults('locale', 'en')->name('student-life.campus.en');
Route::get('/id/kemahasiswaan/prestasi', [StudentLifeController::class, 'achievements'])->defaults('locale', 'id')->name('student-life.achievements.id');
Route::get('/en/student-life/achievements', [StudentLifeController::class, 'achievements'])->defaults('locale', 'en')->name('student-life.achievements.en');
Route::get('/id/kemahasiswaan/prestasi/{slug}', [StudentLifeController::class, 'achievementDetail'])->defaults('locale', 'id')->name('student-life.achievements.show.id');
Route::get('/en/student-life/achievements/{slug}', [StudentLifeController::class, 'achievementDetail'])->defaults('locale', 'en')->name('student-life.achievements.show.en');
Route::get('/id/kemahasiswaan/beasiswa', [StudentLifeController::class, 'scholarships'])->defaults('locale', 'id')->name('student-life.scholarships.id');
Route::get('/en/student-life/scholarships', [StudentLifeController::class, 'scholarships'])->defaults('locale', 'en')->name('student-life.scholarships.en');
Route::get('/id/kemahasiswaan/beasiswa/{slug}', [StudentLifeController::class, 'scholarshipDetail'])->defaults('locale', 'id')->name('student-life.scholarships.show.id');
Route::get('/en/student-life/scholarships/{slug}', [StudentLifeController::class, 'scholarshipDetail'])->defaults('locale', 'en')->name('student-life.scholarships.show.en');
Route::get('/id/kemahasiswaan/kompetisi', [StudentLifeController::class, 'competitions'])->defaults('locale', 'id')->name('student-life.competitions.id');
Route::get('/en/student-life/competitions', [StudentLifeController::class, 'competitions'])->defaults('locale', 'en')->name('student-life.competitions.en');
Route::get('/id/kemahasiswaan/kompetisi/{slug}', [StudentLifeController::class, 'competitionDetail'])->defaults('locale', 'id')->name('student-life.competitions.show.id');
Route::get('/en/student-life/competitions/{slug}', [StudentLifeController::class, 'competitionDetail'])->defaults('locale', 'en')->name('student-life.competitions.show.en');
Route::get('/id/kemahasiswaan/mbkm-magang', [StudentLifeController::class, 'mbkm'])->defaults('locale', 'id')->name('student-life.mbkm.id');
Route::get('/en/student-life/mbkm-internships', [StudentLifeController::class, 'mbkm'])->defaults('locale', 'en')->name('student-life.mbkm.en');
Route::get('/id/kemahasiswaan/alumni', [StudentLifeController::class, 'alumni'])->defaults('locale', 'id')->name('student-life.alumni.id');
Route::get('/en/student-life/alumni', [StudentLifeController::class, 'alumni'])->defaults('locale', 'en')->name('student-life.alumni.en');
Route::get('/id/kemahasiswaan/alumni/{slug}', [StudentLifeController::class, 'alumniDetail'])->defaults('locale', 'id')->name('student-life.alumni.show.id');
Route::get('/en/student-life/alumni/{slug}', [StudentLifeController::class, 'alumniDetail'])->defaults('locale', 'en')->name('student-life.alumni.show.en');
Route::get('/id/kemahasiswaan/hmps', [StudentLifeController::class, 'hmps'])->defaults('locale', 'id')->name('student-life.hmps.id');
Route::get('/en/student-life/student-association', [StudentLifeController::class, 'hmps'])->defaults('locale', 'en')->name('student-life.hmps.en');
Route::get('/id/fasilitas', [StudentLifeController::class, 'facilities'])->defaults('locale', 'id')->name('facilities.id');
Route::get('/en/facilities', [StudentLifeController::class, 'facilities'])->defaults('locale', 'en')->name('facilities.en');
Route::get('/id/fasilitas/{slug}', [StudentLifeController::class, 'facilityDetail'])->defaults('locale', 'id')->name('facilities.show.id');
Route::get('/en/facilities/{slug}', [StudentLifeController::class, 'facilityDetail'])->defaults('locale', 'en')->name('facilities.show.en');
Route::get('/id/pmb', [StudentLifeController::class, 'admissions'])->defaults('locale', 'id')->name('admissions.id');
Route::get('/en/admissions', [StudentLifeController::class, 'admissions'])->defaults('locale', 'en')->name('admissions.en');
Route::get('/id/kontak', [StudentLifeController::class, 'contact'])->defaults('locale', 'id')->name('contact.id');
Route::get('/en/contact', [StudentLifeController::class, 'contact'])->defaults('locale', 'en')->name('contact.en');
Route::post('/id/kontak', [StudentLifeController::class, 'storeContact'])->defaults('locale', 'id')->name('contact.store.id');
Route::post('/en/contact', [StudentLifeController::class, 'storeContact'])->defaults('locale', 'en')->name('contact.store.en');

Route::get('/{locale}/{path}', PlaceholderPageController::class)
    ->whereIn('locale', ['id', 'en'])
    ->where('path', '.*')
    ->name('placeholder');

Route::fallback(function () {
    if (! Schema::hasTable('legacy_redirects')) {
        abort(404);
    }

    $path = '/'.ltrim(request()->path(), '/');
    $redirect = DB::table('legacy_redirects')
        ->whereNull('deleted_at')
        ->where(fn ($query) => $query->where('old_path', $path)->orWhere('source_path', $path))
        ->where(fn ($query) => $query->where('is_active', true)->orWhereNull('is_active'))
        ->first();

    if ($redirect) {
        DB::table('legacy_redirects')->where('id', $redirect->id)->update([
            'hit_count' => ((int) $redirect->hit_count) + 1,
            'last_hit_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect($redirect->new_path ?: $redirect->target_path, $redirect->redirect_code ?: $redirect->status_code ?: 301);
    }

    abort(404);
});

require __DIR__.'/auth.php';
