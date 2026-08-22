@props([
    'title' => 'KPI Title',
    'value' => '0',
    'unit' => '',
    'subtext' => '',
    'icon' => 'activity',
    'iconColor' => 'text-blue-600',
    'bgColor' => 'bg-blue-50',
    'borderColor' => 'border-blue-100'
])

<div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden flex flex-col justify-between">
    <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $title }}</span>
        <div class="w-9 h-9 rounded-xl {{ $bgColor }} flex items-center justify-center {{ $iconColor }}">
            <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
        </div>
    </div>
    <div class="mt-3">
        <div class="flex items-baseline gap-1.5">
            <span class="text-2xl font-black text-slate-800 tracking-tight">{{ $value }}</span>
            @if($unit)
            <span class="text-xs font-semibold text-slate-400">{{ $unit }}</span>
            @endif
        </div>
        @if($subtext)
        <p class="text-[11px] text-slate-500 mt-1 font-medium">{{ $subtext }}</p>
        @endif
    </div>
</div>
