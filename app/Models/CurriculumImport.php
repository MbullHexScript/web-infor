<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurriculumImport extends Model
{
    protected $guarded = [];

    protected $casts = [
        'sheets_processed' => 'array',
        'summary_json' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
}
