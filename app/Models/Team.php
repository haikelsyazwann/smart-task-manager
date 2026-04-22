<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['owner_id', 'name', 'description'];

    public function owner(): BelongsTo        { return $this->belongsTo(User::class, 'owner_id'); }
    public function members(): BelongsToMany  { return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps(); }
    public function projects(): HasMany       { return $this->hasMany(Project::class); }
    public function activityLogs(): MorphMany { return $this->morphMany(ActivityLog::class, 'subject'); }
}
