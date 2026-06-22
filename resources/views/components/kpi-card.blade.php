@props(['label', 'value', 'icon' => '📊', 'variant' => 'primary'])

@php
$variants = [
    'primary' => ['text' => 'text-agri-primary', 'bg' => 'bg-agri-primary/10'],
    'light' => ['text' => 'text-agri-light', 'bg' => 'bg-agri-light/20'],
    'harvest' => ['text' => 'text-harvest', 'bg' => 'bg-harvest/20'],
    'earth' => ['text' => 'text-earth', 'bg' => 'bg-earth/10'],
];
$v = $variants[$variant] ?? $variants['primary'];
@endphp

<div {{ $attributes->merge(['class' => 'kpi-card']) }}>
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
            <p class="mt-2 text-2xl font-bold {{ $v['text'] }} md:text-3xl">{{ $value }}</p>
        </div>
        <span class="flex h-12 w-12 items-center justify-center rounded-xl {{ $v['bg'] }} text-2xl">{{ $icon }}</span>
    </div>
</div>
