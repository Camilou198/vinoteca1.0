@props(['wine'])

<img
    class="w-full objet-cover rounded-t-lg md:w-48 md:h-auto md:rounded-l-lg"
    src="{{ $wine->image_url }}"
    alt="{{ $wine->name }}"
/>
