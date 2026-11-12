<?php

namespace App\Services;

use App\Models\CurriculumImport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;

class CurriculumWorkbookImporter
{
    /**
     * @return array<string, mixed>
     */
    public function inspect(string $path): array
    {
        return $this->extract($path);
    }

    /**
     * @return array<string, mixed>
     */
    public function import(string $path, bool $dryRun = false, bool $updateExisting = false): array
    {
        $startedAt = now();
        $payload = $this->extract($path);
        $summary = $payload['summary'];
        $mode = $dryRun ? 'dry-run' : ($updateExisting ? 'commit-update-existing' : 'commit');

        $record = CurriculumImport::create([
            'original_filename' => basename($path),
            'stored_path' => $path,
            'checksum' => $summary['sha256'],
            'mode' => $mode,
            'status' => 'validating',
            'sheets_processed' => $summary['sheets_processed'],
            'rows_processed' => $summary['course_count'],
            'warnings_count' => count($summary['warnings']),
            'errors_count' => count($summary['errors']),
            'summary_json' => $summary,
            'started_at' => $startedAt,
        ]);

        if ($summary['errors']) {
            $record->update([
                'status' => 'failed',
                'errors_count' => count($summary['errors']),
                'completed_at' => now(),
            ]);
            $this->writeReport($record, $payload);

            return ['import' => $record->fresh(), 'payload' => $payload];
        }

        if ($dryRun) {
            $changes = $this->countChanges($payload);
            $record->update([
                'status' => $summary['warnings'] ? 'success_with_warnings' : 'success',
                'rows_created' => $changes['created'],
                'rows_updated' => $changes['updated'],
                'rows_skipped' => $changes['skipped'],
                'completed_at' => now(),
            ]);
            $this->writeReport($record->fresh(), $payload);

            return ['import' => $record->fresh(), 'payload' => $payload];
        }

        DB::transaction(function () use ($payload, $record, $updateExisting): void {
            $changes = $this->persist($payload, $updateExisting);
            $record->update([
                'curriculum_id' => $changes['curriculum_id'],
                'status' => $payload['summary']['warnings'] ? 'success_with_warnings' : 'success',
                'rows_created' => $changes['created'],
                'rows_updated' => $changes['updated'],
                'rows_skipped' => $changes['skipped'],
                'completed_at' => now(),
            ]);
        });

        $this->writeReport($record->fresh(), $payload);

        return ['import' => $record->fresh(), 'payload' => $payload];
    }

    /**
     * @return array<string, mixed>
     */
    public function validateDatabase(): array
    {
        $warnings = [];
        $errors = [];
        $curriculum = DB::table('curricula')->where('code', 'OBE-2026-S1-INF')->first();

        if (! $curriculum) {
            $errors[] = 'Kurikulum OBE-2026-S1-INF belum ada di database.';
        }

        if ($curriculum) {
            $courses = DB::table('courses')->where('curriculum_id', $curriculum->id)->get();
            foreach (range(1, 8) as $semester) {
                if (! $courses->where('semester', $semester)->count()) {
                    $warnings[] = "Semester {$semester} belum memiliki mata kuliah.";
                }
            }
            $codes = $courses->pluck('code')->filter()->duplicates();
            if ($codes->isNotEmpty()) {
                $errors[] = 'Kode mata kuliah duplikat: '.$codes->implode(', ');
            }
        }

        return ['warnings' => $warnings, 'errors' => $errors];
    }

    /**
     * @return array<string, mixed>
     */
    private function extract(string $path): array
    {
        if (! File::exists($path)) {
            throw new RuntimeException("Workbook not found: {$path}");
        }

        $python = $this->pythonBinary();
        $script = app_path('Support/curriculum_extractor.py');
        $process = new Process([$python, $script, $path], base_path(), ['PYTHONIOENCODING' => 'utf-8']);
        $process->setTimeout(120);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(trim($process->getErrorOutput() ?: $process->getOutput()));
        }

        return json_decode($process->getOutput(), true, 512, JSON_THROW_ON_ERROR);
    }

    private function pythonBinary(): string
    {
        $configured = config('academic.python_binary');
        if ($configured && File::exists($configured)) {
            return $configured;
        }

        $bundled = 'C:\\Users\\Lenovo\\.cache\\codex-runtimes\\codex-primary-runtime\\dependencies\\python\\python.exe';
        if (File::exists($bundled)) {
            return $bundled;
        }

        return 'python';
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array{created:int,updated:int,skipped:int}
     */
    private function countChanges(array $payload): array
    {
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $curriculum = DB::table('curricula')->where('code', $payload['curriculum']['code'])->first();

        if ($curriculum) {
            $updated++;
        } else {
            $created++;
        }

        foreach ($payload['specializations'] as $specialization) {
            DB::table('specializations')->where('code', $specialization['code'])->exists() ? $updated++ : $created++;
        }

        foreach ($payload['profiles'] as $profile) {
            $exists = $curriculum && DB::table('graduate_profiles')
                ->where('curriculum_id', $curriculum->id)
                ->where('code', $profile['code'])
                ->exists();
            $exists ? $updated++ : $created++;
        }

        foreach ($payload['courses'] as $course) {
            $exists = $curriculum && DB::table('courses')
                ->where('curriculum_id', $curriculum->id)
                ->where('code', $course['code'])
                ->exists();
            $exists ? $updated++ : $created++;
        }

        return compact('created', 'updated', 'skipped');
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array{curriculum_id:int,created:int,updated:int,skipped:int}
     */
    private function persist(array $payload, bool $updateExisting): array
    {
        $created = 0;
        $updated = 0;

        $curriculumPayload = array_merge($payload['curriculum'], [
            'title_id' => $payload['curriculum']['name_id'],
            'title_en' => $payload['curriculum']['name_en'],
            'slug' => $payload['curriculum']['slug_id'],
            'status' => 'published',
            'verification_status' => 'verified',
            'is_active' => true,
            'published_at' => now(),
            'metadata' => json_encode($payload['summary'], JSON_UNESCAPED_UNICODE),
            'updated_at' => now(),
        ]);

        $existing = DB::table('curricula')->where('code', $payload['curriculum']['code'])->first();
        if ($existing) {
            $updated++;
            DB::table('curricula')->where('id', $existing->id)->update($curriculumPayload);
            $curriculumId = $existing->id;
        } else {
            $created++;
            $curriculumId = DB::table('curricula')->insertGetId(array_merge($curriculumPayload, ['created_at' => now()]));
        }

        $specializationIds = [];
        foreach ($payload['specializations'] as $specialization) {
            $data = array_merge($specialization, [
                'title_id' => $specialization['name_id'],
                'title_en' => $specialization['name_en'],
                'slug' => $specialization['slug_id'],
                'status' => 'published',
                'verification_status' => 'verified',
                'is_active' => true,
                'metadata' => json_encode(['source' => 'curriculum-import'], JSON_UNESCAPED_UNICODE),
                'updated_at' => now(),
            ]);
            $existing = DB::table('specializations')->where('code', $specialization['code'])->first();
            if ($existing) {
                $updated++;
                DB::table('specializations')->where('id', $existing->id)->update($data);
                $specializationIds[$specialization['code']] = $existing->id;
            } else {
                $created++;
                $specializationIds[$specialization['code']] = DB::table('specializations')->insertGetId(array_merge($data, ['created_at' => now()]));
            }
        }

        foreach ($payload['profiles'] as $profile) {
            $data = array_merge($profile, [
                'curriculum_id' => $curriculumId,
                'title_en' => $profile['title_en'] ?: $profile['title_id'],
                'status' => 'published',
                'verification_status' => 'verified',
                'is_active' => true,
                'metadata' => json_encode(['source' => 'curriculum-import'], JSON_UNESCAPED_UNICODE),
                'updated_at' => now(),
            ]);
            $existing = DB::table('graduate_profiles')->where('curriculum_id', $curriculumId)->where('code', $profile['code'])->first();
            if ($existing) {
                $updated++;
                DB::table('graduate_profiles')->where('id', $existing->id)->update($data);
            } else {
                $created++;
                DB::table('graduate_profiles')->insert(array_merge($data, ['created_at' => now()]));
            }
        }

        $courseIds = [];
        foreach ($payload['courses'] as $course) {
            $specializationId = $course['specialization_code'] ? ($specializationIds[$course['specialization_code']] ?? null) : null;
            $metadata = $course['metadata'];
            $metadata['prerequisite_codes'] = $course['prerequisite_codes'];
            $data = [
                'curriculum_id' => $curriculumId,
                'specialization_id' => $specializationId,
                'code' => $course['code'],
                'name_id' => $course['name_id'],
                'name_en' => $course['name_en'],
                'title_id' => $course['name_id'],
                'title_en' => $course['name_en'],
                'slug' => $course['slug_id'],
                'slug_id' => $course['slug_id'],
                'slug_en' => $course['slug_en'],
                'semester' => $course['semester'],
                'credits' => $course['credits'],
                'course_type' => $course['course_type'],
                'category' => $course['category'],
                'description_id' => $course['description_id'],
                'description_en' => $course['description_en'],
                'learning_materials_id' => $course['learning_materials_id'],
                'learning_materials_en' => $course['learning_materials_en'],
                'software_tools_id' => $course['software_tools_id'],
                'software_tools_en' => $course['software_tools_en'],
                'status' => 'published',
                'verification_status' => 'verified',
                'is_active' => true,
                'sort_order' => $course['sort_order'],
                'metadata' => json_encode($metadata, JSON_UNESCAPED_UNICODE),
                'updated_at' => now(),
            ];
            $existing = DB::table('courses')->where('curriculum_id', $curriculumId)->where('code', $course['code'])->first();
            if ($existing) {
                $updated++;
                DB::table('courses')->where('id', $existing->id)->update($data);
                $courseIds[$course['code']] = $existing->id;
            } else {
                $created++;
                $courseIds[$course['code']] = DB::table('courses')->insertGetId(array_merge($data, ['created_at' => now()]));
            }
        }

        DB::table('course_prerequisites')->whereIn('course_id', array_values($courseIds))->delete();
        $skipped = 0;
        foreach ($payload['courses'] as $course) {
            foreach ($course['prerequisite_codes'] as $prerequisiteCode) {
                if (! isset($courseIds[$course['code']], $courseIds[$prerequisiteCode])) {
                    $skipped++;

                    continue;
                }
                DB::table('course_prerequisites')->insert([
                    'course_id' => $courseIds[$course['code']],
                    'prerequisite_course_id' => $courseIds[$prerequisiteCode],
                    'requirement_type' => 'required',
                    'notes_id' => $prerequisiteCode,
                    'notes_en' => $prerequisiteCode,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return ['curriculum_id' => $curriculumId, 'created' => $created, 'updated' => $updated, 'skipped' => $skipped];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function writeReport(CurriculumImport $import, array $payload): void
    {
        $directory = storage_path('logs/imports');
        File::ensureDirectoryExists($directory);
        $base = $directory.'/curriculum-import-'.$import->id.'-'.Carbon::parse($import->created_at)->format('Ymd-His');
        File::put($base.'.json', json_encode(['import' => $import->toArray(), 'payload' => $payload], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        File::put($base.'.md', $this->markdownReport($import, $payload));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function markdownReport(CurriculumImport $import, array $payload): string
    {
        $summary = $payload['summary'];
        $semesterRows = collect($summary['semester_totals'])
            ->map(fn (array $item, string|int $semester): string => "| {$semester} | {$item['course_count']} | {$item['mandatory_credits']} | {$item['elective_credits']} | {$item['student_package_credits']} |")
            ->implode(PHP_EOL);

        return Str::of("# Curriculum Import Report #{$import->id}\n\n")
            ->append("- File: {$import->original_filename}\n")
            ->append("- Checksum: {$import->checksum}\n")
            ->append("- Mode: {$import->mode}\n")
            ->append("- Status: {$import->status}\n")
            ->append("- Rows processed: {$import->rows_processed}\n")
            ->append("- Created: {$import->rows_created}\n")
            ->append("- Updated: {$import->rows_updated}\n")
            ->append("- Skipped: {$import->rows_skipped}\n")
            ->append("- Warnings: {$import->warnings_count}\n")
            ->append("- Errors: {$import->errors_count}\n\n")
            ->append("## Sheets Processed\n\n")
            ->append(implode(', ', $summary['sheets_processed'])."\n\n")
            ->append("## Semester Credits\n\n")
            ->append("| Semester | Courses | Mandatory | Elective Offered | Student Package |\n")
            ->append("| --- | ---: | ---: | ---: | ---: |\n")
            ->append($semesterRows."\n\n")
            ->append("Total paket mahasiswa: {$summary['student_total_credits']} SKS\n\n")
            ->append("## Warnings\n\n")
            ->append($summary['warnings'] ? '- '.implode("\n- ", $summary['warnings']) : 'Tidak ada.')
            ->append("\n\n## Errors\n\n")
            ->append($summary['errors'] ? '- '.implode("\n- ", $summary['errors']) : 'Tidak ada.')
            ->toString();
    }
}
