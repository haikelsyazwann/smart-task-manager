<?php

namespace App\Livewire;

use App\Events\CommentCreated;
use App\Models\Comment;
use App\Models\Task;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TaskComments extends Component
{
    public Task $task;
    public string $body = '';

    public function mount(Task $task): void
    {
        $this->task = $task;
    }

    public function addComment(ActivityLogService $activity): void
    {
        $this->validate(['body' => ['required', 'string', 'max:2000']]);

        $comment = $this->task->comments()->create([
            'user_id' => Auth::id(),
            'body'    => $this->body,
        ]);

        $activity->log(Auth::id(), 'task.comment_added', $this->task, ['comment_id' => $comment->id]);
        CommentCreated::dispatch($comment);
        $this->reset('body');
    }

    public function deleteComment(int $commentId): void
    {
        $comment = Comment::findOrFail($commentId);
        abort_unless($comment->user_id === Auth::id() || Auth::user()->hasRole('admin'), 403);
        $comment->delete();
    }

    public function render()
    {
        return view('livewire.task-comments', [
            'comments' => $this->task->comments()->with('user')->oldest()->get(),
        ]);
    }
}
