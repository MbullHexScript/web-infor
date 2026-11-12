<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminAuditLogger
{
    /**
     * @param  array<string, mixed>|null  $oldValues
     * @param  array<string, mixed>|null  $newValues
     */
    public function log(string $action, string $module, string $description, ?string $recordType = null, ?int $recordId = null, ?array $oldValues = null, ?array $newValues = null, ?Request $request = null): void
    {
        $request ??= request();

        DB::table('admin_activity_logs')->insert([
            'admin_id' => Auth::id(),
            'action' => $action,
            'module' => $module,
            'record_type' => $recordType,
            'record_id' => $recordId,
            'description' => $description,
            'old_values_json' => $oldValues ? json_encode($this->redact($oldValues)) : null,
            'new_values_json' => $newValues ? json_encode($this->redact($newValues)) : null,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 500, ''),
            'request_id' => $request->headers->get('X-Request-Id') ?: (string) Str::uuid(),
            'status' => 'recorded',
            'verification_status' => 'verified',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $values
     * @return array<string, mixed>
     */
    private function redact(array $values): array
    {
        foreach ($values as $key => $value) {
            if (Str::contains(Str::lower((string) $key), ['password', 'secret', 'token', 'recovery'])) {
                $values[$key] = '[redacted]';
            } elseif (is_array($value)) {
                $values[$key] = $this->redact($value);
            }
        }

        return $values;
    }
}
