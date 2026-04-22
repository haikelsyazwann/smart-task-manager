@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-violet-500 dark:focus:border-violet-500 focus:ring-2 focus:ring-violet-500 rounded-lg shadow-none placeholder-gray-400 dark:placeholder-gray-500']) }}>
