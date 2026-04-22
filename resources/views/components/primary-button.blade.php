<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-violet-600 hover:bg-violet-700 border border-transparent rounded-lg font-medium text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
