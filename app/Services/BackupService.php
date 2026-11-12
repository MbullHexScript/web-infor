<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class BackupService
{
    /**
     * @return array<string, mixed>
     */
    public function run(): array
    {
        $directory = storage_path('app/backups');
        File::ensureDirectoryExists($directory);
        $path = $directory.'/backup-'.now()->format('Ymd-His').'.json';
        $payload = [
            'created_at' => now()->toIso8601String(),
            'app' => config('app.name'),
            'tables' => Schema::getTableListing(),
        ];
        File::put($path, json_encode($payload, JSON_PRETTY_PRINT));

        $id = DB::table('backup_records')->insertGetId([
            'backup_type' => 'manual',
            'status' => 'completed',
            'disk' => 'local',
            'path' => $path,
            'size' => File::size($path),
            'checksum' => hash_file('sha256', $path),
            'started_at' => now(),
            'finished_at' => now(),
            'notes' => 'Metadata backup placeholder. Use documented DB/media backup procedure for production.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return ['id' => $id, 'path' => $path, 'size' => File::size($path)];
    }

    /**
     * @return array<string, mixed>
     */
    public function verify(?int $id = null): array
    {
        $record = DB::table('backup_records')->when($id, fn ($query) => $query->where('id', $id))->orderByDesc('id')->first();
        if (! $record) {
            return ['ok' => false, 'message' => 'No backup records found.'];
        }

        return [
            'ok' => $record->path && File::exists($record->path) && hash_file('sha256', $record->path) === $record->checksum,
            'id' => $record->id,
            'path' => $record->path,
        ];
    }

    public function list(): array
    {
        return DB::table('backup_records')->orderByDesc('id')->limit(20)->get()->map(fn ($row) => (array) $row)->all();
    }
}
