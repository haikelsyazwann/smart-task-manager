<div class="space-y-4">
    {{-- Comment list --}}
    <div class="space-y-0 max-h-64 overflow-y-auto">
        @forelse($comments as $comment)
        <div class="flex gap-3 py-3 border-b border-gray-100 dark:border-gray-800 last:border-0">
            <div class="w-7 h-7 rounded-full bg-violet-100 dark:bg-violet-900 flex items-center justify-center text-[10px] font-semibold text-violet-700 dark:text-violet-300 flex-shrink-0 mt-0.5">
                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-baseline gap-2">
                    <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $comment->user->name }}</span>
                    <span class="text-[10px] text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                    @if($comment->user_id === auth()->id())
                    <button wire:click="deleteComment({{ $comment->id }})" wire:confirm="Delete this comment?"
                            class="ml-auto text-[10px] text-gray-300 hover:text-red-500 dark:text-gray-600 dark:hover:text-red-400 transition">✕</button>
                    @endif
                </div>
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed mt-0.5">{{ $comment->body }}</p>
            </div>
        </div>
        @empty
        <div class="text-center py-8">
            <p class="text-sm text-gray-400 dark:text-gray-500">No comments yet. Start the discussion!</p>
        </div>
        @endforelse
    </div>

    {{-- Add comment --}}
    <div class="flex gap-2 pt-2">
        <div class="w-7 h-7 rounded-full bg-violet-100 dark:bg-violet-900 flex items-center justify-center text-[10px] font-semibold text-violet-700 dark:text-violet-300 flex-shrink-0 mt-1">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="flex-1 space-y-2">
            <textarea wire:model="body" rows="2" placeholder="Add a comment…"
                      class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 resize-none text-gray-900 dark:text-gray-100 placeholder:text-gray-400"></textarea>
            @error('body') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
            <div class="flex justify-end">
                <button wire:click="addComment" wire:loading.attr="disabled"
                        class="text-xs bg-violet-600 hover:bg-violet-700 disabled:opacity-60 text-white font-medium px-3 py-1.5 rounded-lg transition-colors">
                    <span wire:loading.remove wire:target="addComment">Post comment</span>
                    <span wire:loading wire:target="addComment">Posting…</span>
                </button>
            </div>
        </div>
    </div>
</div>
