<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class HomepageController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Admin/Homepage', [
            'sections' => DB::table('homepage_sections')->orderBy('sort_order')->get(),
            'quickLinks' => DB::table('quick_links')->orderBy('sort_order')->get(),
            'statistics' => DB::table('statistics')->orderBy('sort_order')->get(),
        ]);
    }

    public function updateSections(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sections' => ['required', 'array'],
            'sections.*.key' => ['required', 'string'],
            'sections.*.title_id' => ['required', 'string', 'max:255'],
            'sections.*.title_en' => ['nullable', 'string', 'max:255'],
            'sections.*.subtitle_id' => ['nullable', 'string'],
            'sections.*.subtitle_en' => ['nullable', 'string'],
            'sections.*.background_style' => ['required', 'string', 'max:50'],
            'sections.*.is_active' => ['required', 'boolean'],
            'sections.*.sort_order' => ['required', 'integer', 'min:1'],
        ]);

        foreach ($validated['sections'] as $section) {
            DB::table('homepage_sections')->where('key', $section['key'])->update([
                'title_id' => $section['title_id'],
                'title_en' => $section['title_en'],
                'subtitle_id' => $section['subtitle_id'],
                'subtitle_en' => $section['subtitle_en'],
                'background_style' => $section['background_style'],
                'is_active' => $section['is_active'],
                'sort_order' => $section['sort_order'],
                'updated_at' => now(),
            ]);
        }

        $this->clearHomepageCache();

        return back()->with('status', 'Homepage sections updated.');
    }

    public function updateQuickLinks(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'quick_links' => ['required', 'array'],
            'quick_links.*.key' => ['required', 'string'],
            'quick_links.*.label_id' => ['required', 'string', 'max:255'],
            'quick_links.*.label_en' => ['required', 'string', 'max:255'],
            'quick_links.*.url' => ['required', 'string', 'max:2048'],
            'quick_links.*.icon' => ['nullable', 'string', 'max:80'],
            'quick_links.*.is_external' => ['required', 'boolean'],
            'quick_links.*.is_active' => ['required', 'boolean'],
            'quick_links.*.sort_order' => ['required', 'integer', 'min:1'],
        ]);

        foreach ($validated['quick_links'] as $link) {
            DB::table('quick_links')->where('key', $link['key'])->update([
                'label_id' => $link['label_id'],
                'label_en' => $link['label_en'],
                'url' => $link['url'],
                'icon' => $link['icon'],
                'is_external' => $link['is_external'],
                'is_active' => $link['is_active'],
                'sort_order' => $link['sort_order'],
                'updated_at' => now(),
            ]);
        }

        $this->clearHomepageCache();

        return back()->with('status', 'Quick links updated.');
    }

    public function updateStatistics(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'statistics' => ['required', 'array'],
            'statistics.*.key' => ['required', 'string'],
            'statistics.*.label_id' => ['required', 'string', 'max:255'],
            'statistics.*.label_en' => ['nullable', 'string', 'max:255'],
            'statistics.*.value' => ['nullable', 'string', 'max:50'],
            'statistics.*.suffix' => ['nullable', 'string', 'max:20'],
            'statistics.*.verification_status' => ['required', 'string', 'max:50'],
            'statistics.*.is_active' => ['required', 'boolean'],
            'statistics.*.sort_order' => ['required', 'integer', 'min:1'],
        ]);

        foreach ($validated['statistics'] as $statistic) {
            DB::table('statistics')->where('key', $statistic['key'])->update([
                'label_id' => $statistic['label_id'],
                'label_en' => $statistic['label_en'],
                'value' => $statistic['value'],
                'suffix' => $statistic['suffix'],
                'verification_status' => $statistic['verification_status'],
                'is_active' => $statistic['is_active'],
                'sort_order' => $statistic['sort_order'],
                'updated_at' => now(),
            ]);
        }

        $this->clearHomepageCache();

        return back()->with('status', 'Statistics updated.');
    }

    private function clearHomepageCache(): void
    {
        Cache::forget('homepage.id');
        Cache::forget('homepage.en');
    }
}
