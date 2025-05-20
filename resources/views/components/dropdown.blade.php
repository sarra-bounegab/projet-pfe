@props(['align' => '', 'width' => '48', 'contentClasses' => 'py-1 bg-white dark:bg-green-700'])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-bottom-left rtl:origin-bottom-right left-0',
    'top' => 'origin-top',
    default => 'ltr:origin-bottom-right rtl:origin-bottom-left right-0',
};

$width = match ($width) {
    '48' => 'w-48',
    default => $width,
};
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 bottom-full mb-2 {{ $alignmentClasses }} {{ $width }} max-h-[calc(100vh-100px)] overflow-y-auto rounded-md shadow-lg"
         style="display: none;"
         @click="open = false">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
