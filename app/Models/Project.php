<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id', 'owner_id', 'name', 'description', 'color',
    ];

    public function team(): BelongsTo   { return $this->belongsTo(Team::class); }
    public function owner(): BelongsTo  { return $this->belongsTo(User::class, 'owner_id'); }
    public function tasks(): HasMany    { return $this->hasMany(Task::class); }
    public function activityLogs(): MorphMany { return $this->morphMany(ActivityLog::class, 'subject'); }

    public function completionPercentage(): int
    {
        $total = $this->tasks()->count();
        if ($total === 0) return 0;
        return (int) round($this->tasks()->where('status', Task::STATUS_DONE)->count() / $total * 100);
    }
}
