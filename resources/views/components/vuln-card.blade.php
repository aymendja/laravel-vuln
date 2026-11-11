@props(['title', 'severity' => 'High', 'cve' => 'A03:2021', 'description', 'link' => '#'])

@php
    $colors = [
        'critical' => 'bg-red-500/10 text-red-400 border-red-500/20',
        'high' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
        'medium' => 'bg-orange-500/10 text-orange-400 border-orange-500/20',
    ];
    $badgeClass = $colors[strtolower($severity)] ?? $colors['high'];
@endphp

<div class="bg-[#0a0a0a] border border-white/10 hover:border-red-500/50 transition-all rounded-2xl p-6 flex flex-col justify-between group shadow-2xl">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <span class="text-xs font-mono px-3 py-1 rounded-full border {{ $badgeClass }}">
                {{ $severity }}
            </span>
            <span class="text-xs font-mono text-slate-500">{{ $cve }}</span>
        </div>
        <h3 class="text-lg font-bold text-white group-hover:text-red-400 transition-colors">
            {{ $title }}
        </h3>
        <p class="text-xs text-slate-400 leading-relaxed">
            {{ $description }}
        </p>
    </div>
    <div class="mt-8 pt-4 border-t border-white/10 flex items-center justify-between">
        <span class="text-xs font-mono text-slate-500">Interactive Lab</span>
        <a href="{{ $link }}" class="text-xs font-semibold text-red-400 hover:text-red-300 flex items-center gap-1 font-mono">
            Test Vulnerability &rarr;
        </a>
    </div>
</div>