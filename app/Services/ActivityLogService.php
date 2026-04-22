<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    public function log(?int $userId, string $action, Model $subject, array $properties = []): ActivityLog
    {
        // Auto-store subject name so it survives deletion
        $subjectName = $subject->title ?? $subject->name ?? null;
        if ($subjectName) {
            $properties['subject_name'] = $subjectName;
        }

        return $subject->activityLogs()->create([
            'user_id'    => $userId,
            'action'     => $action,
            'properties' => $properties,
        ]);
    }
}
