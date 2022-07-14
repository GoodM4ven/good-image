@props([
    'media',
    'collection' => 'default',
    'isBackground' => config('good-image.is-background'),
    'forceEagerLoading' => config('good-image.eager-loaded'),
    'forceDisplay' => config('good-image.enforced-display'),
    'alt' => '',
    'imageClasses' => '',
    'widthClass' => 'w-full',
    'heightClass' => 'h-32',
])

@php
$link = $media->getFirstMediaUrl($collection);
@endphp

<!-- Image Container -->
<div
    x-data="goodImage({
        thumbnailLink: @js($media->getGoodThumbnailImageUrl($collection)),
        link: @js($link),
        element: $el,
        isBackground: @js($isBackground),
        forceDisplay: @js($forceDisplay),
    });"
    x-intersect:enter.full="visible = true"
    x-intersect:leave.full="if (forceDisplay) return visible = true; visible = false;"
    {{ $attributes->class([
        $widthClass,
        $heightClass,
        'relative 2xl:mx-auto',
    ])->merge() }}
>
    <!-- Placeholder -->
    <canvas
        x-show="!isReadyToShowBackground"
        x-transition:leave="transition ease-in duration-1000"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        width="32"
        height="32"
        @class(['absolute inset-0 h-full w-full', $imageClasses])
    ></canvas>

    <!-- The Image -->
    <img
        src="{{ $link }}"
        alt="{{ $alt }}"
        loading="{{ $forceEagerLoading ? 'eager' : 'lazy' }}"
        x-init="loaded = $el.complete && $el.naturalHeight !== 0"
        x-bind:loading="visible ? 'eager' : 'lazy'"
        x-on:load="loaded = true"
        x-show="isReadyToShowImage()"
        x-cloak
        x-transition:enter="transition ease-out duration-1000"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        @class(['absolute inset-0 h-full w-full object-cover', $imageClasses])
    >

    @if ($slot->isNotEmpty())
        <!-- Extra Content -->
        {{ $slot }}
    @endif
</div>
