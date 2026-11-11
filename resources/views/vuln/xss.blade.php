<div class="space-y-8">

    {{-- ===================== Header ===================== --}}
    <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-8 space-y-4 shadow-xl">
        <div class="flex items-center gap-3">
            <span class="text-xs font-mono px-3 py-1 rounded-full bg-red-500/10 text-red-400 border border-red-500/20">
                High Severity
            </span>
            <span class="text-xs font-mono text-slate-500">CVE: A03:2021-XSS (Reflected Cross-Site Scripting)</span>
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">
            Cross-Site Scripting (XSS) Lab
        </h1>
        <p class="text-sm text-slate-400 leading-relaxed">
            A search box reflects your input back into the page. Break the first two levels by injecting
            JavaScript, then see how output encoding makes the same payloads harmless.
        </p>
        <div class="flex flex-wrap gap-2 text-xs font-mono">
            <a href="#level1" class="px-3 py-1 rounded-full bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/20">Level 1 &middot; Easy</a>
            <a href="#level2" class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 hover:bg-amber-500/20">Level 2 &middot; Filtered</a>
            <a href="#secure" class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20">Secure</a>
        </div>
    </div>

    {{-- ===================== Level 1 : no protection ===================== --}}
    <div id="level1" class="bg-[#0a0a0a] border border-red-500/30 rounded-2xl p-6 space-y-6 max-w-xl mx-auto shadow-xl scroll-mt-24">
        <div class="space-y-2 text-center">
            <span class="text-xs font-mono px-2 py-1 rounded bg-red-500/20 text-red-400 border border-red-500/30">Vulnerable &middot; Level 1</span>
            <h3 class="text-sm font-mono text-white font-bold">Search (No Protection)</h3>
            <p class="text-xs text-slate-400">Goal: make the page execute your JavaScript.</p>
        </div>

        <form action="{{ route('vulns.xss') }}#level1" method="GET" class="space-y-4">
            <input type="hidden" name="level" value="level1">
            <div class="space-y-1">
                <label class="block text-xs font-mono text-slate-400">Search:</label>
                <input type="text" name="q" value="{{ $level === 'level1' ? $q : '' }}" placeholder="Type something..." class="w-full bg-[#030303] border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-red-500/50" required>
            </div>
            <button type="submit" class="w-full bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500/20 py-3 rounded-xl text-xs font-mono transition-colors font-bold">
                Search (Vulnerable)
            </button>
        </form>

        @if($level === 'level1' && $q !== '')
            <div class="bg-[#030303] border border-white/10 rounded-xl p-4 space-y-2">
                <p class="text-[10px] font-mono uppercase text-slate-500">Search results for:</p>
                {{-- INTENTIONALLY VULNERABLE: raw, unescaped output --}}
                <div class="text-sm text-white font-mono break-words">{!! $output !!}</div>
            </div>
        @endif

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show vulnerable code</summary>
            <pre class="mt-3 bg-[#030303] border border-white/10 rounded-xl p-4 overflow-x-auto text-[11px] text-red-300">@verbatim
// Controller
$output = $q;                  // input is used as-is

// Blade view
{!! $output !!}                // unescaped output
@endverbatim</pre>
        </details>

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show solution</summary>
            <div class="mt-3 space-y-2">
                <p>The input is printed as raw HTML, so a script tag runs:</p>
                <code class="block bg-[#030303] border border-white/10 rounded-lg p-3 text-red-300 break-all">{{ "<script>alert('XSS')</script>" }}</code>
            </div>
        </details>
    </div>

    {{-- ===================== Level 2 : weak filter ===================== --}}
    <div id="level2" class="bg-[#0a0a0a] border border-amber-500/30 rounded-2xl p-6 space-y-6 max-w-xl mx-auto shadow-xl scroll-mt-24">
        <div class="space-y-2 text-center">
            <span class="text-xs font-mono px-2 py-1 rounded bg-amber-500/20 text-amber-400 border border-amber-500/30">Vulnerable &middot; Level 2</span>
            <h3 class="text-sm font-mono text-white font-bold">Search (Blacklist Filter)</h3>
            <p class="text-xs text-slate-400">The developer now removes <span class="text-amber-300">{{ '<script>' }}</span> tags. Can you still bypass it?</p>
        </div>

        <form action="{{ route('vulns.xss') }}#level2" method="GET" class="space-y-4">
            <input type="hidden" name="level" value="level2">
            <div class="space-y-1">
                <label class="block text-xs font-mono text-slate-400">Search:</label>
                <input type="text" name="q" value="{{ $level === 'level2' ? $q : '' }}" placeholder="Type something..." class="w-full bg-[#030303] border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-amber-500/50" required>
            </div>
            <button type="submit" class="w-full bg-amber-500/10 border border-amber-500/30 text-amber-400 hover:bg-amber-500/20 py-3 rounded-xl text-xs font-mono transition-colors font-bold">
                Search (Filtered)
            </button>
        </form>

        @if($level === 'level2' && $q !== '')
            <div class="bg-[#030303] border border-white/10 rounded-xl p-4 space-y-3">
                <div class="space-y-1">
                    <p class="text-[10px] font-mono uppercase text-slate-500">Your input:</p>
                    <div class="text-xs text-slate-400 font-mono break-all">{{ $q }}</div>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-mono uppercase text-slate-500">Search results for:</p>
                    {{-- INTENTIONALLY VULNERABLE: filtered, but still unescaped output --}}
                    <div class="text-sm text-white font-mono break-words">{!! $output !!}</div>
                </div>
            </div>
        @endif

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show vulnerable code</summary>
            <pre class="mt-3 bg-[#030303] border border-white/10 rounded-xl p-4 overflow-x-auto text-[11px] text-amber-300">@verbatim
// Controller
$output = str_replace(
    ['&lt;script&gt;', '&lt;/script&gt;'], '', $q
);                             // case-sensitive, single pass

// Blade view
{!! $output !!}                // still unescaped output
@endverbatim</pre>
        </details>

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show solutions</summary>
            <div class="mt-3 space-y-3">
                <p>The filter only removes the exact lowercase string <span class="text-amber-300">{{ '<script>' }}</span>. Bypass it by:</p>

                <div class="space-y-1">
                    <p class="text-slate-500">1. Changing the letter case:</p>
                    <code class="block bg-[#030303] border border-white/10 rounded-lg p-3 text-amber-300 break-all">{{ '<ScRiPt>alert(1)</ScRiPt>' }}</code>
                </div>

                <div class="space-y-1">
                    <p class="text-slate-500">2. Using another tag (no script tag needed):</p>
                    <code class="block bg-[#030303] border border-white/10 rounded-lg p-3 text-amber-300 break-all">{{ '<svg onload=alert(1)>' }}</code>
                    <code class="block bg-[#030303] border border-white/10 rounded-lg p-3 text-amber-300 break-all">{{ '<img src=x onerror=alert(1)>' }}</code>
                </div>

                <div class="space-y-1">
                    <p class="text-slate-500">3. Nesting the tag, because the filter runs only once:</p>
                    <code class="block bg-[#030303] border border-white/10 rounded-lg p-3 text-amber-300 break-all">{{ '<scr<script>ipt>alert(1)</scr</script>ipt>' }}</code>
                </div>
            </div>
        </details>
    </div>

    {{-- ===================== Secure : output encoding ===================== --}}
    <div id="secure" class="bg-[#0a0a0a] border border-emerald-500/30 rounded-2xl p-6 space-y-6 max-w-xl mx-auto shadow-xl scroll-mt-24">
        <div class="space-y-2 text-center">
            <span class="text-xs font-mono px-2 py-1 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Secure</span>
            <h3 class="text-sm font-mono text-white font-bold">Search (HTML Encoding)</h3>
            <p class="text-xs text-slate-400">Try every payload from the previous levels. None of them will run.</p>
        </div>

        <form action="{{ route('vulns.xss') }}#secure" method="GET" class="space-y-4">
            <input type="hidden" name="level" value="secure">
            <div class="space-y-1">
                <label class="block text-xs font-mono text-slate-400">Search:</label>
                <input type="text" name="q" value="{{ $level === 'secure' ? $q : '' }}" placeholder="Type something..." class="w-full bg-[#030303] border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-emerald-500/50" required>
            </div>
            <button type="submit" class="w-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 hover:bg-emerald-500/20 py-3 rounded-xl text-xs font-mono transition-colors font-bold">
                Search (Secure)
            </button>
        </form>

        @if($level === 'secure' && $q !== '')
            <div class="bg-[#030303] border border-white/10 rounded-xl p-4 space-y-3">
                <div class="space-y-1">
                    <p class="text-[10px] font-mono uppercase text-slate-500">Search results for:</p>
                    {{-- SAFE: the double-brace syntax HTML-encodes the value, so it is displayed as plain text --}}
                    <div class="text-sm text-white font-mono break-words">{{ $q }}</div>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-mono uppercase text-slate-500">HTML sent to the browser (encoded):</p>
                    <code class="block text-xs text-emerald-300 break-all">{{ $output }}</code>
                </div>
            </div>
        @endif

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show secure code</summary>
            <pre class="mt-3 bg-[#030303] border border-white/10 rounded-xl p-4 overflow-x-auto text-[11px] text-emerald-300">@verbatim
// Blade view: {{ }} calls e() automatically
{{ $q }}
@endverbatim</pre>
            <div class="mt-3 space-y-2">
                <p>Encoding converts the special characters into harmless HTML entities:</p>
                <table class="w-full text-left border border-white/10 rounded-lg overflow-hidden">
                    <thead class="bg-white/5 text-slate-300">
                        <tr><th class="px-3 py-2">Character</th><th class="px-3 py-2">Becomes</th></tr>
                    </thead>
                    <tbody class="text-emerald-300">
                        <tr class="border-t border-white/10"><td class="px-3 py-2">{{ '<' }}</td><td class="px-3 py-2">{{ '&lt;' }}</td></tr>
                        <tr class="border-t border-white/10"><td class="px-3 py-2">{{ '>' }}</td><td class="px-3 py-2">{{ '&gt;' }}</td></tr>
                        <tr class="border-t border-white/10"><td class="px-3 py-2">{{ '"' }}</td><td class="px-3 py-2">{{ '&quot;' }}</td></tr>
                        <tr class="border-t border-white/10"><td class="px-3 py-2">{{ "'" }}</td><td class="px-3 py-2">{{ '&#039;' }}</td></tr>
                        <tr class="border-t border-white/10"><td class="px-3 py-2">{{ '&' }}</td><td class="px-3 py-2">{{ '&amp;' }}</td></tr>
                    </tbody>
                </table>
                <p>The browser shows the text, but never treats it as HTML, so no script can run.</p>
            </div>
        </details>
    </div>
</div>
