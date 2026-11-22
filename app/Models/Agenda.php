<?php

namespace App\Models;

use Database\Factories\AgendaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agenda extends Model
{
    /** @use HasFactory<AgendaFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'title',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
