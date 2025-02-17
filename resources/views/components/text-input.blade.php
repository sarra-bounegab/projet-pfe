@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' =>
     'border-gray-300 dark:border-gray-700  text-dark
      focus:border-500  rounded-md shadow-sm']) }}>
