@props([
    'type' => 'normal', // normal, warning, danger, info
    'text' => 'Badge'
])

@php
    $classes = match($type) {
        'danger', 'kritis' => 'bg-red-100 text-red-700 border-red-200',
        'warning', 'rendah', 'rework' => 'bg-amber-100 text-amber-800 border-amber-200',
        'success', 'normal', 'pass' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
        'info', 'blue' => 'bg-blue-100 text-blue-800 border-blue-200',
        default => 'bg-slate-100 text-slate-700 border-slate-200',
    };
    $dot = match($type) {
        'danger', 'kritis' => 'bg-red-500',
        'warning', 'rendah', 'rework' => 'bg-amber-500',
        'success', 'normal', 'pass' => 'bg-emerald-500',
        'info', 'blue' => 'bg-blue-500',
        default => 'bg-slate-500',
    };
@endphp

<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $classes }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
    {{ $text }}
</span>
