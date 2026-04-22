<div>
@if($open && $task)
<div class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4"
     wire:click.self="$set('open', false)">
<div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 w-full max-w-3xl shadow-2xl flex flex-col max-h-[90vh]">

    {{-- Modal header --}}
    <div class="flex items-start gap-3 px-5 py-4 border-b border-gray-200 dark:border-gray-800 flex-shrink-0">
        <div class="flex-1 min-w-0">
            <p class="text-[11px] text-gray-400 mb-0.5">
                {{ $task->project->name }} / {{ $task->project->team->name }}
            </p>
            <h2 class="font-semibold text-base truncate">{{ $taskTitle }}</h2>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full uppercase tracking-wide
                {{ $taskStatus === 'done' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400'
                 : ($taskStatus === 'in_progress' ? 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400'
                 : 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400') }}">
                {{ str_replace('_', ' ', $taskStatus) }}
            </span>
            <button wire:click="$set('open', false)"
                    class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-lg leading-none">✕</button>
        </div>
    </div>

    {{-- Tabs + Content in ONE Alpine scope --}}
    <div x-data="{ tab: 'details' }" class="flex flex-col flex-1 overflow-hidden">

        {{-- Tab bar --}}
        <div class="flex border-b border-gray-200 dark:border-gray-800 flex-shrink-0 px-2">
            <button @click="tab='details'"
                    :class="tab==='details' ? 'border-violet-600 text-violet-600 dark:text-violet-400 font-medium' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700'"
                    class="px-4 py-3 text-sm border-b-2 transition-colors">Details</button>

            <button @click="tab='subtasks'"
                    :class="tab==='subtasks' ? 'border-violet-600 text-violet-600 dark:text-violet-400 font-medium' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700'"
                    class="px-4 py-3 text-sm border-b-2 transition-colors">
                Subtasks
                @if(count($subtaskItems) > 0)
                <span class="ml-1 text-[10px] bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded-full text-gray-500">{{ count(array_filter($subtaskItems, fn($s) => $s['completed'])) }}/{{ count($subtaskItems) }}</span>
                @endif
            </button>

            <button @click="tab='comments'"
                    :class="tab==='comments' ? 'border-violet-600 text-violet-600 dark:text-violet-400 font-medium' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700'"
                    class="px-4 py-3 text-sm border-b-2 transition-colors">
                Comments
                @if($task->comments->count() > 0)
                <span class="ml-1 text-[10px] bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded-full text-gray-500">{{ $task->comments->count() }}</span>
                @endif
            </button>

            <button @click="tab='activity'"
                    :class="tab==='activity' ? 'border-violet-600 text-violet-600 dark:text-violet-400 font-medium' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700'"
                    class="px-4 py-3 text-sm border-b-2 transition-colors">Activity</button>
        </div>

        {{-- Scrollable content --}}
        <div class="overflow-y-auto flex-1">

            {{-- ─── DETAILS ─── --}}
            <div x-show="tab==='details'" class="p-5 space-y-4">
                @if($canUpdate)
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Title *</label>
                    <input wire:model="taskTitle" type="text"
                           class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 text-gray-900 dark:text-gray-100">
                    @error('taskTitle') <p class="text-xs text-red-500 mt-0.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Description</label>
                    <textarea wire:model="taskDescription" rows="3"
                              class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 resize-none text-gray-900 dark:text-gray-100"
                              placeholder="Describe what needs to be done…"></textarea>
                    <div class="flex justify-end mt-1">
                        <button wire:click="aiImproveDescription" type="button"
                                class="inline-flex items-center gap-1.5 text-xs bg-violet-50 dark:bg-violet-900/30 hover:bg-violet-100 text-violet-700 dark:text-violet-400 px-3 py-1.5 rounded-lg border border-violet-200 dark:border-violet-800 transition-colors font-medium">
                            ✦ AI Improve
                        </button>
                    </div>
                    @if($showAiDesc)
                    <div class="mt-2 bg-violet-50 dark:bg-violet-900/20 border border-violet-200 dark:border-violet-800 rounded-lg p-3">
                        <p class="text-xs font-semibold text-violet-700 dark:text-violet-400 mb-1">✦ Improved description:</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $aiImprovedDesc }}</p>
                        <div class="flex gap-2 mt-2">
                            <button wire:click="applyImprovedDesc" type="button"
                                    class="text-xs bg-violet-600 hover:bg-violet-700 text-white px-3 py-1 rounded-md transition-colors">Apply</button>
                            <button wire:click="$set('showAiDesc', false)" type="button"
                                    class="text-xs border border-gray-200 dark:border-gray-700 px-3 py-1 rounded-md hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Dismiss</button>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
                        <select wire:model="taskStatus"
                                class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 text-gray-900 dark:text-gray-100">
                            <option value="todo">To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Priority</label>
                        <select wire:model="taskPriority"
                                class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 text-gray-900 dark:text-gray-100">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Deadline</label>
                        <input wire:model="taskDeadline" type="date"
                               class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 text-gray-900 dark:text-gray-100">
                    </div>
                </div>

                {{-- Assignees --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Assignees</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($teamMembers as $member)
                        @php $assigned = in_array((string)$member->id, $assigneeIds); @endphp
                        <button type="button"
                                wire:click="toggleAssignee({{ $member->id }})"
                                class="flex items-center gap-2 px-3 py-1.5 rounded-full border cursor-pointer transition-colors select-none
                                    {{ $assigned
                                        ? 'bg-violet-50 dark:bg-violet-900/30 border-violet-300 dark:border-violet-700 text-violet-700 dark:text-violet-300'
                                        : 'border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-600' }}">
                            <x-avatar :avatar="$member->avatar ?? 'boy1'" size="xs" />
                            <span class="text-xs font-medium">{{ $member->name }}</span>
                            @if($assigned)
                            <svg class="w-3 h-3 text-violet-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Attachments --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Attachments</label>
                    @if($task->attachments->count() > 0)
                    <div class="space-y-1 mb-2">
                        @foreach($task->attachments as $att)
                        <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 rounded-lg px-3 py-2">
                            <span>📎</span>
                            <a href="{{ asset('storage/'.$att->path) }}" target="_blank" class="hover:text-violet-600 hover:underline truncate flex-1">{{ $att->file_name }}</a>
                            <span class="text-gray-400 flex-shrink-0">{{ round($att->size / 1024) }} KB</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    <input wire:model="newAttachment" type="file"
                           class="text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border file:border-gray-200 dark:file:border-gray-700 file:text-xs file:bg-white dark:file:bg-gray-800 file:text-gray-700 dark:file:text-gray-300 hover:file:bg-gray-50 cursor-pointer">
                    @error('newAttachment') <p class="text-xs text-red-500 mt-0.5">{{ $message }}</p> @enderror
                </div>

                @else
                {{-- Read-only view --}}
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-medium text-gray-400 mb-0.5">Title</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $taskTitle }}</p>
                    </div>
                    @if($taskDescription)
                    <div>
                        <p class="text-xs font-medium text-gray-400 mb-0.5">Description</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">{{ $taskDescription }}</p>
                    </div>
                    @endif
                    <div class="flex gap-4">
                        <div><p class="text-xs font-medium text-gray-400 mb-0.5">Status</p><p class="text-sm">{{ ucwords(str_replace('_', ' ', $taskStatus)) }}</p></div>
                        <div><p class="text-xs font-medium text-gray-400 mb-0.5">Priority</p><p class="text-sm capitalize">{{ $taskPriority }}</p></div>
                        @if($taskDeadline)<div><p class="text-xs font-medium text-gray-400 mb-0.5">Deadline</p><p class="text-sm">{{ \Carbon\Carbon::parse($taskDeadline)->format('M j, Y') }}</p></div>@endif
                    </div>
                </div>
                @endif
            </div>

            {{-- ─── SUBTASKS ─── --}}
            <div x-show="tab==='subtasks'" style="display:none" class="p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold">Subtasks</h3>
                        @if(count($subtaskItems) > 0)
                        @php $doneCount = count(array_filter($subtaskItems, fn($s) => $s['completed'])); @endphp
                        <p class="text-xs text-gray-400">{{ $doneCount }}/{{ count($subtaskItems) }} completed</p>
                        @endif
                    </div>
                    @if($canUpdate)
                    <button wire:click="aiGenerateSubtasks" type="button"
                            class="inline-flex items-center gap-1.5 text-xs bg-violet-50 dark:bg-violet-900/30 hover:bg-violet-100 text-violet-700 dark:text-violet-400 px-3 py-1.5 rounded-lg border border-violet-200 dark:border-violet-800 transition-colors font-medium">
                        ✦ AI Generate
                    </button>
                    @endif
                </div>

                @if(count($subtaskItems) > 0)
                @php $pct = round(count(array_filter($subtaskItems, fn($s) => $s['completed'])) / count($subtaskItems) * 100); @endphp
                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full bg-violet-500 transition-all duration-500" style="width:{{ $pct }}%"></div>
                </div>
                @endif

                @if($showAiSubs)
                <div class="bg-violet-50 dark:bg-violet-900/20 border border-violet-200 dark:border-violet-800 rounded-lg p-3">
                    <p class="text-xs font-semibold text-violet-700 dark:text-violet-400 mb-2">✦ AI suggested subtasks:</p>
                    @foreach(array_filter(explode("\n", $aiSubSuggestions)) as $suggestion)
                    <div class="flex items-center justify-between py-1">
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $suggestion }}</span>
                        <button wire:click="addAiSubtask('{{ addslashes(trim($suggestion)) }}')" type="button"
                                class="text-xs text-violet-600 hover:underline ml-2 flex-shrink-0 font-medium">+ Add</button>
                    </div>
                    @endforeach
                    <button wire:click="$set('showAiSubs', false)" type="button"
                            class="mt-2 text-xs text-gray-400 hover:text-gray-600">Dismiss</button>
                </div>
                @endif

                <div class="space-y-1">
                    @foreach($subtaskItems as $idx => $sub)
                    <div class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 group transition-colors">
                        <input type="checkbox" wire:click="toggleSubtask({{ $idx }})"
                               {{ $sub['completed'] ? 'checked' : '' }}
                               class="w-4 h-4 rounded accent-violet-600 cursor-pointer flex-shrink-0">
                        @if($canUpdate)
                        <input wire:model="subtaskItems.{{ $idx }}.title" type="text"
                               class="flex-1 text-sm bg-transparent border-none focus:outline-none focus:ring-0 {{ $sub['completed'] ? 'line-through text-gray-400' : 'text-gray-700 dark:text-gray-200' }}">
                        <button wire:click="removeSubtask({{ $idx }})" type="button"
                                class="opacity-0 group-hover:opacity-100 text-gray-300 hover:text-red-500 transition text-xs flex-shrink-0">✕</button>
                        @else
                        <span class="flex-1 text-sm {{ $sub['completed'] ? 'line-through text-gray-400' : 'text-gray-700 dark:text-gray-200' }}">{{ $sub['title'] }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>

                @if($canUpdate)
                <div class="flex gap-2">
                    <input type="text" id="new-subtask-input" placeholder="Add a subtask…"
                           class="flex-1 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500"
                           onkeydown="if(event.key==='Enter'){event.preventDefault();@this.call('addSubtask',this.value);this.value='';}">
                    <button type="button"
                            onclick="const i=document.getElementById('new-subtask-input');if(i.value.trim()){@this.call('addSubtask',i.value);i.value='';}"
                            class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 text-sm px-3 py-2 rounded-lg transition-colors text-gray-600 dark:text-gray-300">
                        Add
                    </button>
                </div>
                @endif
            </div>

            {{-- ─── COMMENTS ─── --}}
            <div x-show="tab==='comments'" style="display:none" class="p-5 space-y-4">
                <livewire:task-comments :task="$task" :key="'comments-'.$task->id" />
                @if($task->comments->count() >= 2)
                <div class="border-t border-gray-200 dark:border-gray-800 pt-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">AI Summary</span>
                        <button wire:click="aiSummarizeComments" type="button"
                                class="inline-flex items-center gap-1.5 text-xs bg-violet-50 dark:bg-violet-900/30 hover:bg-violet-100 text-violet-700 dark:text-violet-400 px-3 py-1.5 rounded-lg border border-violet-200 dark:border-violet-800 transition-colors font-medium">
                            ✦ Summarize discussion
                        </button>
                    </div>
                    @if($showAiSum)
                    <div class="bg-violet-50 dark:bg-violet-900/20 border border-violet-200 dark:border-violet-800 rounded-lg p-3">
                        <p class="text-xs font-semibold text-violet-700 dark:text-violet-400 mb-1">✦ Discussion summary:</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $aiCommentSummary }}</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            {{-- ─── ACTIVITY ─── --}}
            <div x-show="tab==='activity'" style="display:none" class="p-5">
                @if($task->activityLogs->isEmpty())
                <p class="text-sm text-gray-400 dark:text-gray-500 py-6 text-center">No activity recorded yet.</p>
                @else
                <div class="space-y-0">
                    @foreach($task->activityLogs->sortByDesc('created_at') as $log)
                    <div class="flex items-start gap-3 py-3 border-b border-gray-100 dark:border-gray-800 last:border-0">
                        <div class="w-6 h-6 rounded-full bg-violet-100 dark:bg-violet-900 flex items-center justify-center text-[9px] font-semibold text-violet-700 dark:text-violet-300 flex-shrink-0 mt-0.5">
                            {{ $log->user ? strtoupper(substr($log->user->name, 0, 1)) : '?' }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <span class="font-medium">{{ $log->user?->name ?? 'System' }}</span>
                                <span class="text-gray-500"> {{ str_replace(['task.', '_'], ['', ' '], $log->action) }}</span>
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>{{-- /scrollable content --}}
    </div>{{-- /x-data tabs --}}

    {{-- Modal footer --}}
    <div class="flex items-center justify-between px-5 py-3 border-t border-gray-200 dark:border-gray-800 flex-shrink-0 bg-gray-50 dark:bg-gray-900/50 rounded-b-xl">
        <div class="flex items-center gap-2">
            @if($canDelete)
            <button wire:click="delete" wire:confirm="Permanently delete '{{ $taskTitle }}'?"
                    class="inline-flex items-center gap-1.5 text-xs text-red-600 dark:text-red-500 hover:text-red-700 border border-red-200 dark:border-red-800 hover:bg-red-50 dark:hover:bg-red-900/20 px-3 py-1.5 rounded-lg transition-colors">
                🗑 Delete task
            </button>
            @endif
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="$set('open', false)"
                    class="text-sm border border-gray-200 dark:border-gray-700 px-3 py-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-600 dark:text-gray-400">
                Cancel
            </button>
            @if($canUpdate)
            <button wire:click="save" wire:loading.attr="disabled"
                    class="text-sm bg-violet-600 hover:bg-violet-700 disabled:opacity-60 text-white font-medium px-4 py-1.5 rounded-lg transition-colors">
                <span wire:loading.remove wire:target="save">Save changes</span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>
            @endif
        </div>
    </div>

</div>
</div>
@endif
</div>
