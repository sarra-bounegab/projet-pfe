<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center 
    px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md 
    font-semibold text-xs text-dark-600 dark:text-dark-400 uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green focus:bg-green-700 dark:focus:bg-green active:bg-green-900 dark:active:bg-green-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
