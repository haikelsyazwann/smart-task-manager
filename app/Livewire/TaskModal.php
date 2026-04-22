<?php

namespace App\Livewire;

use App\Events\TaskUpdated;
use App\Models\Task;
use App\Services\ActivityLogService;
use App\Services\OpenAIProductivityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithFileUploads;

class TaskModal extends Component
{
    use WithFileUploads;

    public ?Task $task = null;
    public bool $open = false;
    public array $subtaskItems = [];
    public array $assigneeIds  = [];
    public $newAttachment;

    // Flat properties for editing
    public string $taskTitle       = '';
    public string $taskDescription = '';
    public string $taskPriority    = 'medium';
    public string $taskStatus      = 'todo';
    public ?string $taskDeadline   = null;

    // AI results
    public string $aiImprovedDesc   = '';
    public string $aiSubSuggestions = '';
    public string $aiCommentSummary = '';
    public bool $showAiDesc = false;
    public bool $showAiSubs = false;
    public bool $showAiSum  = false;

    protected $listeners = ['open-task-modal' => 'openTask'];

    public function openTask(int $taskId): void
    {
        $this->task = Task::with([
            'subtasks', 'assignees', 'project.team.members',
            'comments.user', 'attachments', 'activityLogs.user'
        ])->findOrFail($taskId);

        $this->taskTitle       = $this->task->title;
        $this->taskDescription = $this->task->description ?? '';
        $this->taskPriority    = $this->task->priority;
        $this->taskStatus      = $this->task->status;
        $this->taskDeadline    = $this->task->deadline?->format('Y-m-d');
        $this->subtaskItems    = $this->task->subtasks
            ->map(fn($s) => ['id' => $s->id, 'title' => $s->title, 'completed' => $s->completed])
            ->toArray();
        $this->assigneeIds     = $this->task->assignees
            ->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $this->open            = true;
        $this->reset(['aiImprovedDesc', 'aiSubSuggestions', 'aiCommentSummary',
                      'showAiDesc', 'showAiSubs', 'showAiSum', 'newAttachment']);
    }

    // ── AI features ──
    public function aiImproveDescription(OpenAIProductivityService $ai): void
    {
        if (!$this->taskDescription) return;
        $this->aiImprovedDesc = $ai->improveDescription($this->taskDescription);
        $this->showAiDesc = true;
    }

    public function applyImprovedDesc(): void
    {
        $this->taskDescription = $this->aiImprovedDesc;
        $this->showAiDesc = false;
    }

    public function aiGenerateSubtasks(OpenAIProductivityService $ai): void
    {
        $subs = $ai->generateSubtasks($this->taskTitle, $this->taskDescription);
        $this->aiSubSuggestions = implode("\n", $subs);
        $this->showAiSubs = true;
    }

    public function addAiSubtask(string $title): void
    {
        $this->subtaskItems[] = ['id' => null, 'title' => $title, 'completed' => false];
    }

    public function aiSummarizeComments(OpenAIProductivityService $ai): void
    {
        $thread = $this->task->comments
            ->map(fn($c) => "{$c->user->name}: {$c->body}")
            ->implode("\n");
        $this->aiCommentSummary = $thread ? $ai->summarizeComments($thread) : 'No comments to summarize.';
        $this->showAiSum = true;
    }

    // ── Subtask helpers ──
    public function addSubtask(string $title = ''): void
    {
        $this->subtaskItems[] = ['id' => null, 'title' => $title, 'completed' => false];
    }

    public function removeSubtask(int $idx): void
    {
        array_splice($this->subtaskItems, $idx, 1);
    }

    public function toggleSubtask(int $idx): void
    {
        $this->subtaskItems[$idx]['completed'] = !$this->subtaskItems[$idx]['completed'];
    }

    // ── Save ──
    public function save(ActivityLogService $activity): void
    {
        Gate::authorize('update', $this->task);

        $this->validate([
            'taskTitle'            => ['required', 'string', 'max:255'],
            'taskDescription'      => ['nullable', 'string'],
            'taskPriority'         => ['required', 'in:low,medium,high'],
            'taskStatus'           => ['required', 'in:todo,in_progress,done'],
            'taskDeadline'         => ['nullable', 'date'],
            'assigneeIds'          => ['array'],
            'assigneeIds.*'        => ['exists:users,id'],
            'subtaskItems'         => ['array'],
            'subtaskItems.*.title' => ['required', 'string', 'max:255'],
            'newAttachment'        => ['nullable', 'file', 'max:10240'],
        ]);

        $this->task->update([
            'title'       => $this->taskTitle,
            'description' => $this->taskDescription,
            'priority'    => $this->taskPriority,
            'status'      => $this->taskStatus,
            'deadline'    => $this->taskDeadline ?: null,
        ]);

        $this->task->assignees()->sync(array_map('intval', $this->assigneeIds));

        $this->task->subtasks()->delete();
        foreach ($this->subtaskItems as $idx => $sub) {
            if (trim($sub['title']) !== '') {
                $this->task->subtasks()->create([
                    'title'     => trim($sub['title']),
                    'completed' => $sub['completed'] ?? false,
                    'position'  => $idx,
                ]);
            }
        }

        if ($this->newAttachment) {
            $path = $this->newAttachment->store('task-attachments', 'public');
            $this->task->attachments()->create([
                'uploaded_by' => Auth::id(),
                'disk'        => 'public',
                'path'        => $path,
                'file_name'   => $this->newAttachment->getClientOriginalName(),
                'mime_type'   => $this->newAttachment->getClientMimeType(),
                'size'        => $this->newAttachment->getSize(),
            ]);
        }

        $activity->log(Auth::id(), 'task.updated', $this->task);
        TaskUpdated::dispatch($this->task->fresh());
        $this->dispatch('task-updated');
        $this->open = false;
    }

    // ── Delete ──
    public function delete(ActivityLogService $activity): void
    {
        Gate::authorize('delete', $this->task);
        $activity->log(Auth::id(), 'task.deleted', $this->task);
        $this->task->delete();
        $this->open = false;
        $this->dispatch('task-deleted');
    }

    public function render()
    {
        return view('livewire.task-modal', [
            'teamMembers' => $this->task?->project?->team?->members ?? collect(),
            'canDelete'   => $this->task ? Auth::user()->can('delete', $this->task) : false,
            'canUpdate'   => $this->task ? Auth::user()->can('update', $this->task) : false,
        ]);
    }

    public function toggleAssignee(int $userId): void
    {
        $uid = (string) $userId;
        if (in_array($uid, $this->assigneeIds)) {
            $this->assigneeIds = array_values(array_filter($this->assigneeIds, fn($id) => $id !== $uid));
        } else {
            $this->assigneeIds[] = $uid;
        }
    }
}
