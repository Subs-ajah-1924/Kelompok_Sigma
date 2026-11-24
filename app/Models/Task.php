<?php

namespace App\Models;

use App\Difficulty;
use App\TaskStatus;
use Carbon\Carbon;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'course_id',
        'title',
        'description',
        'deadline',
        'difficulty',
        'status',
        'completed_at',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'completed_at' => 'datetime',
            'status' => TaskStatus::class,
            'difficulty' => Difficulty::class,
            'is_read' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class)->withDefault();
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_task')->withTimestamps();
    }

    // Query Scopes
    public function scopeOwnedBy(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, function ($q, $term) {
            $q->where(function ($sub) use ($term) {
                $sub->where('title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        });
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['course_id'] ?? null, fn ($q, $id) => $q->where('course_id', $id))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['difficulty'] ?? null, fn ($q, $difficulty) => $q->where('difficulty', $difficulty))
            ->when($filters['deadline_from'] ?? null, fn ($q, $from) => $q->where('deadline', '>=', Carbon::parse($from)->startOfDay()))
            ->when($filters['deadline_to'] ?? null, fn ($q, $to) => $q->where('deadline', '<=', Carbon::parse($to)->endOfDay()));
    }

    public function scopeNotDone(Builder $query): Builder
    {
        return $query->where('status', '!=', TaskStatus::Done);
    }

    public function scopeDueWithin(Builder $query, int $days): Builder
    {
        return $query->where('deadline', '<=', now()->copy()->addDays($days)->endOfDay());
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('deadline', '<', now()->startOfDay());
    }
    protected static function booted(): void
    {
        static::deleting(function (Task $task): void {
            $task->groups()->detach();
        });
    }
}
