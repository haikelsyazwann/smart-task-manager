<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use Livewire\Component;

class ActivityFeed extends Component
{
    public function render()
    {
        return view('livewire.activity-feed', [
            'logs' => ActivityLog::with(['user', 'subject'])->latest()->take(12)->get(),
        ]);
    }
}
