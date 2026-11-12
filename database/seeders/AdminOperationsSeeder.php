<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminOperationsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('seo_settings')->updateOrInsert(
            ['id' => 1],
            [
                'site_title_id' => 'Program Studi Informatika',
                'site_title_en' => 'Informatics Study Program',
                'default_meta_description_id' => 'Website resmi Program Studi Informatika Fakultas Sains dan Teknologi UIN Sultan Maulana Hasanuddin Banten.',
                'default_meta_description_en' => 'Official website of the Informatics Study Program, Faculty of Science and Technology, UIN Sultan Maulana Hasanuddin Banten.',
                'title_separator' => '|',
                'robots_index' => true,
                'robots_follow' => true,
                'sitemap_enabled' => true,
                'news_sitemap_enabled' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        );

        $navigation = [
            ['main', 'Beranda', 'Home', '/id', 'Home', 1],
            ['main', 'Profil', 'Profile', '/id/profil', 'Building2', 2],
            ['main', 'Akademik', 'Academics', '/id/akademik/kurikulum', 'BookOpen', 3],
            ['main', 'Informasi', 'Information', '/id/informasi', 'Newspaper', 4],
            ['main', 'Riset', 'Research', '/id/riset', 'FlaskConical', 5],
            ['main', 'Kemahasiswaan', 'Student Life', '/id/kemahasiswaan', 'Users', 6],
            ['main', 'PMB', 'Admissions', '/id/pmb', 'GraduationCap', 7],
            ['topbar', 'UIN SMH Banten', 'UIN SMH Banten', 'https://uinbanten.ac.id/', 'ExternalLink', 1],
            ['footer', 'Kontak', 'Contact', '/id/kontak', 'Mail', 1],
        ];

        foreach ($navigation as [$group, $id, $en, $url, $icon, $sort]) {
            DB::table('navigation_items')->updateOrInsert(
                ['group' => $group, 'label_id' => $id],
                [
                    'label_en' => $en,
                    'url' => $url,
                    'icon' => $icon,
                    'is_external' => str_starts_with($url, 'http'),
                    'open_in_new_tab' => str_starts_with($url, 'http'),
                    'is_active' => true,
                    'sort_order' => $sort,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }

        foreach ([
            'operations.upload' => ['max_image_mb' => 5, 'max_pdf_mb' => 20, 'allowed_images' => ['jpg', 'jpeg', 'png', 'webp', 'avif']],
            'operations.security' => ['csp_report_only' => false, 'admin_noindex' => true, 'idle_timeout_minutes' => 120],
            'operations.backup' => ['database_schedule' => 'daily', 'media_schedule' => 'weekly', 'retention_days' => 30],
            'operations.analytics' => ['provider' => null, 'tracking_id' => null, 'enabled' => false],
        ] as $key => $value) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $key],
                [
                    'value' => json_encode($value),
                    'type' => 'json',
                    'group' => 'operations',
                    'is_public' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }
    }
}
