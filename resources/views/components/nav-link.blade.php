@props(['href', 'active' => false])

<a href="{{ $href }}"
   class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition-colors
          {{ $active
             ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300 font-medium'
             : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
    @isset($icon)
    <span class="{{ $active ? 'text-violet-600 dark:text-violet-400' : 'text-gray-400 dark:text-gray-500' }}">
        {{ $icon }}
    </span>
    @endisset
    {{ $slot }}
</a>
