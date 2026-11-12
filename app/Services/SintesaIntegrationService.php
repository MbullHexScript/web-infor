<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use RuntimeException;

class SintesaIntegrationService
{
    /**
     * @return array<string, mixed>
     */
    public function health(): array
    {
        return $this->get('health');
    }

    /**
     * Synchronize data owned by SINTESA without replacing website-only fields.
     *
     * @return array<string, mixed>
     */
    public function sync(bool $dryRun = false, string $only = 'all'): array
    {
        if (! in_array($only, ['all', 'catalog', 'lecturers'], true)) {
            throw new RuntimeException('Jenis sinkronisasi harus all, catalog, atau lecturers.');
        }

        $result = [
            'source' => 'SINTESA',
            'mode' => $dryRun ? 'dry-run' : 'commit',
            'catalog' => ['curricula' => 0, 'courses' => 0],
            'lecturers' => 0,
            'generated_at' => now()->toIso8601String(),
        ];

        if (in_array($only, ['all', 'catalog'], true)) {
            $catalog = $this->get('catalog')['data'] ?? [];
            $result['catalog'] = $this->syncCatalog($catalog, $dryRun);
        }

        if (in_array($only, ['all', 'lecturers'], true)) {
            $result['lecturers'] = $this->syncLecturers($dryRun);
        }

        if (! $dryRun) {
            Cache::forget('homepage.id');
            Cache::forget('homepage.en');
        }

        return $result;
    }

    /**
     * @param  array<string, mixed>  $catalog
     * @return array{curricula: int, courses: int}
     */
    private function syncCatalog(array $catalog, bool $dryRun): array
    {
        $curricula = collect($catalog['curricula'] ?? []);
        $courses = collect($catalog['courses'] ?? []);

        if ($dryRun) {
            return ['curricula' => $curricula->count(), 'courses' => $courses->count()];
        }

        DB::transaction(function () use ($curricula, $courses): void {
            foreach ($curricula as $row) {
                $code = trim((string) ($row['code'] ?? ''));
                if ($code === '') {
                    continue;
                }

                $name = trim((string) ($row['name'] ?? $code));
                $year = (int) ($row['curriculum_year'] ?? 0) ?: null;
                $existing = DB::table('curricula')->where('code', $code)->first()
                    ?? ($year ? DB::table('curricula')->where('year', $year)->orderBy('id')->first() : null);
                $payload = [
                    'code' => $code,
                    'name_id' => $name,
                    'title_id' => $name,
                    'slug_id' => Str::slug($name),
                    'slug' => Str::slug($name),
                    'year' => $year,
                    'total_credits' => (int) ($row['total_sks'] ?? $row['catalog_sks'] ?? 0),
                    'total_semesters' => 8,
                    'status' => 'published',
                    'verification_status' => 'verified',
                    'is_active' => ($row['status'] ?? null) === 'Aktif',
                    'published_at' => now(),
                    'metadata' => $this->mergeMetadata($existing?->metadata, [
                        'source' => 'SINTESA',
                        'effective_from_cohort' => $row['effective_from_cohort'] ?? null,
                    ]),
                    'updated_at' => now(),
                ];
                if ($existing) {
                    DB::table('curricula')->where('id', $existing->id)->update($payload);
                } else {
                    DB::table('curricula')->insert($payload + ['created_at' => now()]);
                }
            }

            $curriculumIds = DB::table('curricula')->pluck('id', 'code');
            foreach ($courses as $row) {
                $curriculumCode = trim((string) ($row['curriculum_code'] ?? ''));
                $code = trim((string) ($row['course_code'] ?? ''));
                $curriculumId = $curriculumIds[$curriculumCode] ?? null;
                if (! $curriculumId || $code === '') {
                    continue;
                }

                $name = trim((string) ($row['course_name'] ?? $code));
                $existing = DB::table('courses')
                    ->where('curriculum_id', $curriculumId)
                    ->where('code', $code)
                    ->first();
                $payload = [
                    'name_id' => $name,
                    'title_id' => $name,
                    'slug_id' => Str::slug($code.'-'.$name),
                    'slug' => Str::slug($code.'-'.$name),
                    'semester' => (int) ($row['semester'] ?? 0) ?: null,
                    'credits' => (int) ($row['sks'] ?? 0),
                    'course_type' => Str::lower((string) ($row['course_type'] ?? '')) === 'wajib' ? 'mandatory' : 'elective',
                    'category' => $row['course_type'] ?? null,
                    'status' => 'published',
                    'verification_status' => 'verified',
                    'is_active' => ($row['status'] ?? null) === 'Aktif',
                    'metadata' => $this->mergeMetadata($existing?->metadata, [
                        'source' => 'SINTESA',
                        'curriculum_code' => $curriculumCode,
                        'eligibility_source' => $row['eligibility_source'] ?? null,
                    ]),
                    'updated_at' => now(),
                ];
                if ($existing) {
                    DB::table('courses')->where('id', $existing->id)->update($payload);
                } else {
                    DB::table('courses')->insert($payload + [
                        'curriculum_id' => $curriculumId,
                        'code' => $code,
                        'created_at' => now(),
                    ]);
                }
            }
        });

        return ['curricula' => $curricula->count(), 'courses' => $courses->count()];
    }

    private function syncLecturers(bool $dryRun): int
    {
        $page = 1;
        $lastPage = 1;
        $count = 0;

        do {
            $response = $this->get('lecturers', [
                'page' => $page,
                'per_page' => 100,
                'program_codes' => '55200,INF',
            ]);
            $rows = $response['data'] ?? [];
            $lastPage = (int) ($response['meta']['lastPage'] ?? 1);
            $count += count($rows);

            if (! $dryRun) {
                DB::transaction(function () use ($rows): void {
                    foreach ($rows as $index => $row) {
                        $this->upsertLecturer($row, $index);
                    }
                });
            }
            $page++;
        } while ($page <= $lastPage);

        if (! $dryRun) {
            $this->mergeDuplicateLecturers();
        }

        return $count;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function upsertLecturer(array $row, int $index): void
    {
        $sourceId = trim((string) ($row['id'] ?? ''));
        $nidn = trim((string) ($row['nidn'] ?? ''));
        $email = trim((string) ($row['email'] ?? ''));
        $name = trim((string) ($row['name'] ?? ''));
        if ($name === '') {
            return;
        }

        $slug = Str::slug($name);
        $existing = DB::table('lecturers')
            ->where(function ($query) use ($nidn, $email, $slug): void {
                $query->where('slug', $slug);
                if ($nidn !== '' && $nidn !== '-') {
                    $query->orWhere('nidn', $nidn);
                }
                if ($email !== '') {
                    $query->orWhere('email', $email);
                }
            })
            ->orderBy('id')
            ->first();

        $payload = [
            'full_name' => $name,
            'title_id' => $name,
            'title_en' => $name,
            'slug' => $existing?->slug ?: $slug,
            'email' => $email !== '' ? $email : null,
            'nidn' => $nidn !== '' && $nidn !== '-' ? $nidn : null,
            'academic_title' => $row['academicRank'] ?? null,
            'academic_position_id' => $row['academicRank'] ?? null,
            'academic_position_en' => $row['academicRank'] ?? null,
            'employment_status' => $row['lecturerType'] ?? null,
            'expertise_id' => $row['expertiseSummary'] ?? null,
            'expertise_en' => $row['expertiseSummary'] ?? null,
            'sinta_url' => $row['sintaProfileUrl'] ?? null,
            'status' => 'published',
            'verification_status' => 'verified',
            'is_active' => ($row['status'] ?? null) === 'Aktif',
            'sort_order' => $existing?->sort_order ?? ($index + 1),
            'metadata' => $this->mergeMetadata($existing?->metadata, [
                'source' => 'SINTESA',
                'source_id' => $sourceId,
                'source_code' => $row['sourceCode'] ?? null,
                'source_updated_at' => $row['updatedAt'] ?? null,
                'research_interests' => $row['researchInterests'] ?? [],
            ]),
            'updated_at' => now(),
        ];

        if ($existing) {
            DB::table('lecturers')->where('id', $existing->id)->update($payload);
        } else {
            DB::table('lecturers')->insert($payload + ['created_at' => now()]);
        }
    }

    /**
     * @param  array<string, scalar>  $query
     * @return array<string, mixed>
     */
    private function get(string $resource, array $query = []): array
    {
        $response = $this->request()->get((string) config('services.sintesa.url'), [
            'resource' => $resource,
            ...$query,
        ])->throw();

        return $response->json();
    }

    private function request(): PendingRequest
    {
        $url = trim((string) config('services.sintesa.url'));
        $token = trim((string) config('services.sintesa.token'));
        if ($url === '' || $token === '') {
            throw new RuntimeException('SINTESA_API_URL dan SINTESA_API_TOKEN wajib dikonfigurasi.');
        }

        return Http::acceptJson()
            ->withHeaders(['X-SINTESA-API-KEY' => $token])
            ->timeout(max(1, (int) config('services.sintesa.timeout', 15)))
            ->retry(2, 250);
    }

    private function mergeDuplicateLecturers(): void
    {
        $slugs = DB::table('lecturers')
            ->whereNotNull('slug')
            ->groupBy('slug')
            ->havingRaw('count(*) > 1')
            ->pluck('slug');

        foreach ($slugs as $slug) {
            $rows = DB::table('lecturers')->where('slug', $slug)->orderBy('id')->get();
            if (! $rows->contains(fn (object $row): bool => str_contains((string) $row->metadata, 'SINTESA'))) {
                continue;
            }

            DB::transaction(function () use ($rows): void {
                $canonical = $rows->first();
                $duplicates = $rows->slice(1);
                $duplicateIds = $duplicates->pluck('id')->all();

                foreach ([
                    ['management_positions', 'lecturer_id'],
                    ['lecturer_educations', 'lecturer_id'],
                    ['achievements', 'lecturer_id'],
                    ['expertise_groups', 'coordinator_lecturer_id'],
                    ['research_projects', 'lead_lecturer_id'],
                    ['community_services', 'lead_lecturer_id'],
                    ['publications', 'lecturer_id'],
                ] as [$table, $column]) {
                    if (Schema::hasColumn($table, $column)) {
                        DB::table($table)->whereIn($column, $duplicateIds)->update([$column => $canonical->id]);
                    }
                }

                foreach ([
                    ['expertise_area_lecturer', 'expertise_area_id'],
                    ['expertise_group_lecturer', 'expertise_group_id'],
                ] as [$table, $relatedColumn]) {
                    if (! Schema::hasTable($table)) {
                        continue;
                    }
                    $relatedIds = DB::table($table)->whereIn('lecturer_id', $duplicateIds)->pluck($relatedColumn);
                    foreach ($relatedIds as $relatedId) {
                        DB::table($table)->insertOrIgnore([
                            'lecturer_id' => $canonical->id,
                            $relatedColumn => $relatedId,
                        ]);
                    }
                    DB::table($table)->whereIn('lecturer_id', $duplicateIds)->delete();
                }

                $editorial = [];
                foreach (['photo_id', 'photo_alt', 'biography_id', 'biography_en', 'education_id', 'education_en'] as $column) {
                    $value = $rows->pluck($column)->first(fn ($item): bool => filled($item));
                    if ($value !== null) {
                        $editorial[$column] = $value;
                    }
                }
                DB::table('lecturers')->where('id', $canonical->id)->update($editorial + ['updated_at' => now()]);
                DB::table('lecturers')->whereIn('id', $duplicateIds)->delete();
            });
        }
    }

    /**
     * @param  array<string, mixed>  $source
     */
    private function mergeMetadata(?string $existing, array $source): string
    {
        $metadata = json_decode((string) $existing, true);

        return json_encode(
            [...(is_array($metadata) ? $metadata : []), ...$source],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
        );
    }
}
