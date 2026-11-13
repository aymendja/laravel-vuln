@props(['result' => null, 'level'])

@if($result && ($result['level'] ?? null) === $level)
    <div class="bg-[#030303] border border-white/10 rounded-xl p-4 space-y-3">
        <p class="text-[10px] font-mono uppercase text-slate-500">Upload result:</p>

        @if($result['ok'])
            <p class="text-xs text-emerald-300 font-mono break-words">{{ $result['message'] }}</p>

            <div class="space-y-1">
                <p class="text-[10px] font-mono uppercase text-slate-500">Saved as:</p>
                <div class="text-xs text-white font-mono break-all">{{ $result['name'] }}</div>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-mono uppercase text-slate-500">Location on the server:</p>
                <div class="text-xs text-slate-300 font-mono break-all">{{ $result['location'] }}</div>
            </div>

            @if(!empty($result['url']))
                <a href="{{ $result['url'] }}" target="_blank" rel="noopener" class="block text-xs font-mono text-red-400 hover:text-red-300 break-all">
                    Open the uploaded file &rarr; {{ $result['url'] }}
                </a>
            @else
                <p class="text-xs font-mono text-emerald-300">No public URL: this file cannot be requested from the browser.</p>
            @endif
        @else
            <p class="text-xs text-red-300 font-mono break-words">{{ $result['message'] }}</p>
        @endif
    </div>
@endif
