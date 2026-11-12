<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'full_name',
        'slug',
        'tagline',
        'description_id',
        'description_en',
        'scope_id',
        'scope_en',
        'url',
        'ojs_url',
        'verification_status',
        'status_label',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
