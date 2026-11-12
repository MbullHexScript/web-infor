<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminAuditLogger;
use App\Services\BackupService;
use App\Services\HealthCheckService;
use App\Services\MediaLibraryService;
use App\Services\RedirectManager;
use App\Services\SitemapService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class OperationsController extends Controller
{
    public function media(Request $request, MediaLibraryService $media): Response
    {
        $query = DB::table('media')
            ->leftJoin('media_usages', 'media.id', '=', 'media_usages.media_id')
            ->select('media.*', DB::raw('count(media_usages.id) as usage_count'))
            ->whereNull('media.deleted_at')
            ->groupBy('media.id')
            ->orderByDesc('media.created_at');

        if ($request->filled('q')) {
            $term = trim(strip_tags((string) $request->input('q')));
            $query->where(fn ($builder) => $builder->where('original_name', 'like', '%'.$term.'%')->orWhere('title_id', 'like', '%'.$term.'%'));
        }
        if ($request->filled('type')) {
            $type = $request->string('type');
            $query->when($type === 'image', fn ($builder) => $builder->where('mime_type', 'like', 'image/%'))
                ->when($type === 'pdf', fn ($builder) => $builder->where('mime_type', 'application/pdf'))
                ->when($type === 'document', fn ($builder) => $builder->where('mime_type', 'not like', 'image/%')->where('mime_type', '!=', 'application/pdf'));
        }
        if ($request->filled('used')) {
            $request->boolean('used') ? $query->having('usage_count', '>', 0) : $query->having('usage_count', '=', 0);
        }

        return Inertia::render('Admin/MediaLibraryPage', [
            'items' => $query->paginate(18)->withQueryString()->through(fn ($row) => [
                'id' => $row->id,
                'title' => $row->title_id ?: $row->original_name ?: $row->file_name,
                'originalName' => $row->original_name ?: $row->file_name,
                'mimeType' => $row->mime_type,
                'size' => $row->file_size ?: $row->size,
                'width' => $row->width,
                'height' => $row->height,
                'url' => $media->publicUrl($row),
                'altText' => $row->alt_text_id ?: $row->alt_id,
                'usageCount' => (int) $row->usage_count,
                'createdAt' => $row->created_at,
            ]),
            'filters' => $request->only(['q', 'type', 'used']),
            'audit' => $media->audit(),
        ]);
    }

    public function storeMedia(Request $request, MediaLibraryService $media, AdminAuditLogger $audit): RedirectResponse
    {
        $request->validate(['file' => ['required', 'file']]);
        $id = $media->store($request->file('file'), $request->user()->id, $request->string('type', 'document'));
        $audit->log('upload', 'media', 'Uploaded media file.', 'media', $id, null, ['media_id' => $id], $request);

        return back()->with('status', 'Media berhasil diunggah.');
    }

    public function updateMedia(Request $request, int $media, AdminAuditLogger $audit): RedirectResponse
    {
        $validated = $request->validate([
            'title_id' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'alt_text_id' => ['nullable', 'string', 'max:255'],
            'alt_text_en' => ['nullable', 'string', 'max:255'],
            'caption_id' => ['nullable', 'string', 'max:1000'],
            'caption_en' => ['nullable', 'string', 'max:1000'],
        ]);
        $old = (array) DB::table('media')->where('id', $media)->first();
        DB::table('media')->where('id', $media)->update([...$validated, 'updated_at' => now()]);
        $audit->log('update', 'media', 'Updated media metadata.', 'media', $media, $old, $validated, $request);

        return back()->with('status', 'Metadata media diperbarui.');
    }

    public function replaceMedia(Request $request, int $media, MediaLibraryService $service, AdminAuditLogger $audit): RedirectResponse
    {
        $request->validate(['file' => ['required', 'file']]);
        $service->replace($media, $request->file('file'), $request->user()->id);
        $audit->log('replace', 'media', 'Replaced media file.', 'media', $media, null, ['media_id' => $media], $request);

        return back()->with('status', 'File media diganti dan versi lama dicatat.');
    }

    public function deleteMedia(int $media, AdminAuditLogger $audit): RedirectResponse
    {
        abort_if(DB::table('media_usages')->where('media_id', $media)->exists(), 422, 'Media sedang digunakan.');
        DB::table('media')->where('id', $media)->update(['deleted_at' => now(), 'updated_at' => now()]);
        $audit->log('delete', 'media', 'Soft deleted media.', 'media', $media);

        return back()->with('status', 'Media dihapus secara soft delete.');
    }

    public function seo(Request $request): Response
    {
        return Inertia::render('Admin/SeoManagerPage', [
            'settings' => DB::table('seo_settings')->first(),
            'metadata' => DB::table('seo_metadata')->orderByDesc('updated_at')->paginate(15),
            'report' => $this->seoReport(),
        ]);
    }

    public function updateSeoSettings(Request $request, AdminAuditLogger $audit): RedirectResponse
    {
        $validated = $request->validate([
            'site_title_id' => ['nullable', 'string', 'max:255'],
            'site_title_en' => ['nullable', 'string', 'max:255'],
            'default_meta_description_id' => ['nullable', 'string', 'max:500'],
            'default_meta_description_en' => ['nullable', 'string', 'max:500'],
            'title_separator' => ['nullable', 'string', 'max:5'],
            'robots_index' => ['boolean'],
            'robots_follow' => ['boolean'],
            'google_site_verification' => ['nullable', 'alpha_dash', 'max:120'],
            'bing_site_verification' => ['nullable', 'alpha_dash', 'max:120'],
            'sitemap_enabled' => ['boolean'],
        ]);
        $record = DB::table('seo_settings')->first();
        DB::table('seo_settings')->updateOrInsert(['id' => $record->id ?? 1], [...$validated, 'updated_at' => now(), 'created_at' => $record->created_at ?? now()]);
        $audit->log('update', 'seo', 'Updated global SEO settings.', 'seo_settings', $record->id ?? 1, (array) $record, $validated, $request);

        return back()->with('status', 'SEO settings diperbarui.');
    }

    public function redirects(Request $request, RedirectManager $manager): Response
    {
        $query = DB::table('legacy_redirects')->whereNull('deleted_at')->orderByDesc('updated_at');
        if ($request->filled('q')) {
            $term = trim(strip_tags((string) $request->input('q')));
            $query->where(fn ($builder) => $builder->where('old_path', 'like', '%'.$term.'%')->orWhere('source_path', 'like', '%'.$term.'%')->orWhere('new_path', 'like', '%'.$term.'%')->orWhere('target_path', 'like', '%'.$term.'%'));
        }

        return Inertia::render('Admin/RedirectManagerPage', [
            'items' => $query->paginate(15)->withQueryString(),
            'filters' => $request->only(['q']),
            'audit' => $manager->audit(),
        ]);
    }

    public function storeRedirect(Request $request, RedirectManager $manager, AdminAuditLogger $audit): RedirectResponse
    {
        $id = $manager->store($request->all());
        $audit->log('create', 'redirect', 'Created redirect.', 'legacy_redirects', $id, null, $request->except('_token'), $request);

        return back()->with('status', 'Redirect dibuat.');
    }

    public function deleteRedirect(int $redirect, AdminAuditLogger $audit): RedirectResponse
    {
        DB::table('legacy_redirects')->where('id', $redirect)->update(['deleted_at' => now(), 'updated_at' => now()]);
        $audit->log('delete', 'redirect', 'Soft deleted redirect.', 'legacy_redirects', $redirect);

        return back()->with('status', 'Redirect dihapus.');
    }

    public function settings(): Response
    {
        return Inertia::render('Admin/SiteSettingsPage', [
            'settings' => DB::table('site_settings')->orderBy('group')->orderBy('key')->get(),
            'navigation' => DB::table('navigation_items')->whereNull('deleted_at')->orderBy('group')->orderBy('sort_order')->get(),
            'socialLinks' => DB::table('social_links')->orderBy('title_id')->get(),
        ]);
    }

    public function updateSetting(Request $request, AdminAuditLogger $audit): RedirectResponse
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:120'],
            'group' => ['required', 'string', 'max:80'],
            'value' => ['nullable', 'array'],
            'is_public' => ['boolean'],
        ]);

        DB::table('site_settings')->updateOrInsert(
            ['key' => $validated['key']],
            [
                'value' => json_encode($validated['value'] ?? []),
                'type' => 'json',
                'group' => $validated['group'],
                'is_public' => (bool) ($validated['is_public'] ?? false),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
        cache()->forget('site_settings');
        $audit->log('update', 'settings', 'Updated site setting.', 'site_settings', null, null, $validated, $request);

        return back()->with('status', 'Setting diperbarui.');
    }

    public function storeNavigation(Request $request, AdminAuditLogger $audit): RedirectResponse
    {
        $validated = $request->validate([
            'group' => ['required', 'string', 'max:40'],
            'label_id' => ['required', 'string', 'max:120'],
            'label_en' => ['nullable', 'string', 'max:120'],
            'url' => ['required', 'string', 'max:255', 'not_regex:/^(javascript|data|vbscript):/i'],
            'is_external' => ['boolean'],
            'open_in_new_tab' => ['boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);
        $id = DB::table('navigation_items')->insertGetId([...$validated, 'created_at' => now(), 'updated_at' => now()]);
        $audit->log('create', 'navigation', 'Created navigation item.', 'navigation_items', $id, null, $validated, $request);

        return back()->with('status', 'Navigasi ditambahkan.');
    }

    public function auditLog(Request $request): Response
    {
        $query = DB::table('admin_activity_logs')->orderByDesc('created_at');
        if ($request->filled('q')) {
            $term = trim(strip_tags((string) $request->input('q')));
            $query->where(fn ($builder) => $builder->where('action', 'like', '%'.$term.'%')->orWhere('module', 'like', '%'.$term.'%')->orWhere('description', 'like', '%'.$term.'%'));
        }

        return Inertia::render('Admin/AuditLogPage', [
            'items' => $query->paginate(20)->withQueryString(),
            'filters' => $request->only(['q']),
        ]);
    }

    public function account(Request $request): Response
    {
        return Inertia::render('Admin/AccountSecurityPage', [
            'user' => $request->user()->only(['name', 'email', 'last_login_at', 'two_factor_confirmed_at', 'password_changed_at']),
            'sessions' => DB::table('sessions')->where('user_id', $request->user()->id)->orderByDesc('last_activity')->get(['ip_address', 'user_agent', 'last_activity']),
            'recoveryCodes' => $request->user()->two_factor_recovery_codes ? json_decode($request->user()->two_factor_recovery_codes, true) : [],
        ]);
    }

    public function updateAccount(Request $request, AdminAuditLogger $audit): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:12', 'confirmed'],
        ]);
        $updates = ['name' => $validated['name'], 'email' => $validated['email']];
        if ($validated['password'] ?? null) {
            abort_if(in_array(Str::lower($validated['password']), ['password123456', 'password123!', 'admin123456'], true), 422, 'Password terlalu umum.');
            $updates['password'] = Hash::make($validated['password']);
            $updates['password_changed_at'] = now();
            DB::table('sessions')->where('user_id', $request->user()->id)->where('id', '!=', $request->session()->getId())->delete();
        }
        $request->user()->forceFill($updates)->save();
        $audit->log('update', 'account', 'Updated admin account.', 'users', $request->user()->id, null, ['name' => $updates['name'], 'email' => $updates['email']], $request);

        return back()->with('status', 'Akun diperbarui.');
    }

    public function regenerateRecoveryCodes(Request $request, AdminAuditLogger $audit): RedirectResponse
    {
        $codes = collect(range(1, 8))->map(fn () => Str::upper(Str::random(5).'-'.Str::random(5)))->all();
        $request->user()->forceFill(['two_factor_recovery_codes' => json_encode($codes)])->save();
        $audit->log('update', 'account', 'Regenerated recovery codes.', 'users', $request->user()->id, null, ['recovery_codes' => '[redacted]'], $request);

        return back()->with('status', 'Recovery codes dibuat ulang.');
    }

    public function backups(BackupService $backup, HealthCheckService $health): Response
    {
        return Inertia::render('Admin/OperationsStatusPage', [
            'backups' => $backup->list(),
            'health' => $health->check(),
            'maintenance' => app()->isDownForMaintenance(),
        ]);
    }

    public function runBackup(Request $request, BackupService $backup, AdminAuditLogger $audit): RedirectResponse
    {
        $result = $backup->run();
        $audit->log('backup', 'backup', 'Triggered manual backup.', 'backup_records', $result['id'], null, $result, $request);

        return back()->with('status', 'Backup metadata dibuat.');
    }

    public function toggleMaintenance(Request $request, AdminAuditLogger $audit): RedirectResponse
    {
        $enable = $request->boolean('enable');
        $enable ? Artisan::call('down', ['--secret' => env('APP_MAINTENANCE_SECRET', 'admin-bypass')]) : Artisan::call('up');
        $audit->log($enable ? 'enable' : 'disable', 'maintenance', $enable ? 'Enabled maintenance mode.' : 'Disabled maintenance mode.', null, null, null, ['enabled' => $enable], $request);

        return back()->with('status', $enable ? 'Maintenance mode aktif.' : 'Maintenance mode nonaktif.');
    }

    public function generateSitemap(SitemapService $sitemap, AdminAuditLogger $audit): RedirectResponse
    {
        $result = $sitemap->generate();
        $audit->log('generate', 'seo', 'Generated sitemap.', null, null, null, $result);

        return back()->with('status', 'Sitemap dibuat: '.$result['urls'].' URL.');
    }

    private function seoReport(): array
    {
        return [
            'missingMetaTitle' => DB::table('seo_metadata')->whereNull('meta_title_id')->whereNull('meta_title_en')->count(),
            'missingDescription' => DB::table('seo_metadata')->whereNull('meta_description_id')->whereNull('meta_description_en')->count(),
            'imagesWithoutAlt' => DB::table('media')->where('mime_type', 'like', 'image/%')->whereNull('alt_text_id')->whereNull('alt_id')->whereNull('deleted_at')->count(),
            'duplicateTitles' => DB::table('seo_metadata')->select('meta_title_id')->whereNotNull('meta_title_id')->groupBy('meta_title_id')->havingRaw('count(*) > 1')->count(),
        ];
    }
}
