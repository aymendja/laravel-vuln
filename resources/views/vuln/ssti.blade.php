<div class="space-y-8">

    {{-- ===================== Header ===================== --}}
    <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-8 space-y-4 shadow-xl">
        <div class="flex items-center gap-3">
            <span class="text-xs font-mono px-3 py-1 rounded-full bg-red-500/10 text-red-400 border border-red-500/20">
                Critical Severity
            </span>
            <span class="text-xs font-mono text-slate-500">CWE-1336: Server-Side Template Injection</span>
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">
            Server-Side Template Injection (SSTI) Lab
        </h1>
        <p class="text-sm text-slate-400 leading-relaxed">
            A greeting box compiles your input as a Blade template instead of treating it as plain
            text. Unlike XSS, the code you inject runs on the <strong class="text-white">server</strong>,
            not in the victim's browser, which makes it far more dangerous: it can read files or run
            shell commands on the machine hosting the app.
        </p>
        <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-3 text-xs text-amber-300">
            ⚠️ Level 1 and Level 2 execute real PHP code inside this container. Keep this lab local and
            never expose it to the internet.
        </div>
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
            <h3 class="text-sm font-mono text-white font-bold">Greeting (No Protection)</h3>
            <p class="text-xs text-slate-400">Goal: make the server execute your own code, not just display text.</p>
        </div>

        <form action="{{ route('vulns.ssti') }}#level1" method="GET" class="space-y-4">
            <input type="hidden" name="level" value="level1">
            <div class="space-y-1">
                <label class="block text-xs font-mono text-slate-400">Your name:</label>
                <input type="text" name="template" value="{{ $level === 'level1' ? $template : '' }}" placeholder="Type something..." class="w-full bg-[#030303] border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-red-500/50" required>
            </div>
            <button type="submit" class="w-full bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500/20 py-3 rounded-xl text-xs font-mono transition-colors font-bold">
                Greet Me (Vulnerable)
            </button>
        </form>

        @if($level === 'level1' && $template !== '')
            <div class="bg-[#030303] border border-white/10 rounded-xl p-4 space-y-2">
                <p class="text-[10px] font-mono uppercase text-slate-500">{{ $error ? 'Template error:' : 'Server response:' }}</p>
                <div class="text-sm font-mono break-words {{ $error ? 'text-red-400' : 'text-white' }}">{{ $error ?? $output }}</div>
            </div>
        @endif

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show vulnerable code</summary>
            <pre class="mt-3 bg-[#030303] border border-white/10 rounded-xl p-4 overflow-x-auto text-[11px] text-red-300">@verbatim
// Controller: the user's string IS the template
$output = Blade::render($template);
@endverbatim</pre>
        </details>

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show solution</summary>
            <div class="mt-3 space-y-2">
                <p>Blade's <code class="text-red-300">@{{ }}</code> echoes any PHP expression, so it runs as code, not text:</p>
                <code class="block bg-[#030303] border border-white/10 rounded-lg p-3 text-red-300 break-all">@{{ 7*7 }}</code>
                <p>confirms execution (returns <span class="text-white">49</span>), then this runs a shell command inside the container:</p>
                <code class="block bg-[#030303] border border-white/10 rounded-lg p-3 text-red-300 break-all">@{{ system('id') }}</code>
            </div>
        </details>
    </div>

    {{-- ===================== Level 2 : weak filter ===================== --}}
    <div id="level2" class="bg-[#0a0a0a] border border-amber-500/30 rounded-2xl p-6 space-y-6 max-w-xl mx-auto shadow-xl scroll-mt-24">
        <div class="space-y-2 text-center">
            <span class="text-xs font-mono px-2 py-1 rounded bg-amber-500/20 text-amber-400 border border-amber-500/30">Vulnerable &middot; Level 2</span>
            <h3 class="text-sm font-mono text-white font-bold">Greeting (Blacklist Filter)</h3>
            <p class="text-xs text-slate-400">The developer now strips <span class="text-amber-300">{{ 'system(' }}</span> and <span class="text-amber-300">{{ 'exec(' }}</span>. Can you still run code?</p>
        </div>

        <form action="{{ route('vulns.ssti') }}#level2" method="GET" class="space-y-4">
            <input type="hidden" name="level" value="level2">
            <div class="space-y-1">
                <label class="block text-xs font-mono text-slate-400">Your name:</label>
                <input type="text" name="template" value="{{ $level === 'level2' ? $template : '' }}" placeholder="Type something..." class="w-full bg-[#030303] border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-amber-500/50" required>
            </div>
            <button type="submit" class="w-full bg-amber-500/10 border border-amber-500/30 text-amber-400 hover:bg-amber-500/20 py-3 rounded-xl text-xs font-mono transition-colors font-bold">
                Greet Me (Filtered)
            </button>
        </form>

        @if($level === 'level2' && $template !== '')
            <div class="bg-[#030303] border border-white/10 rounded-xl p-4 space-y-3">
                <div class="space-y-1">
                    <p class="text-[10px] font-mono uppercase text-slate-500">Your input:</p>
                    <div class="text-xs text-slate-400 font-mono break-all">{{ $template }}</div>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-mono uppercase text-slate-500">{{ $error ? 'Template error:' : 'Server response:' }}</p>
                    <div class="text-sm font-mono break-words {{ $error ? 'text-red-400' : 'text-white' }}">{{ $error ?? $output }}</div>
                </div>
            </div>
        @endif

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show vulnerable code</summary>
            <pre class="mt-3 bg-[#030303] border border-white/10 rounded-xl p-4 overflow-x-auto text-[11px] text-amber-300">@verbatim
// Controller
$filtered = str_replace(['system(', 'exec('], '', $template);
$output = Blade::render($filtered);     // still compiles attacker input
@endverbatim</pre>
        </details>

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show solutions</summary>
            <div class="mt-3 space-y-3">
                <p>The filter only removes the exact lowercase text <span class="text-amber-300">{{ 'system(' }}</span>. Bypass it by:</p>

                <div class="space-y-1">
                    <p class="text-slate-500">1. Changing the letter case &mdash; PHP function calls are case-insensitive:</p>
                    <code class="block bg-[#030303] border border-white/10 rounded-lg p-3 text-amber-300 break-all">@{{ SyStem('id') }}</code>
                </div>

                <div class="space-y-1">
                    <p class="text-slate-500">2. Using a different function the filter doesn't know about:</p>
                    <code class="block bg-[#030303] border border-white/10 rounded-lg p-3 text-amber-300 break-all">@{{ passthru('id') }}</code>
                </div>

                <div class="space-y-1">
                    <p class="text-slate-500">3. Using the backtick shell-exec operator, which is not a function name at all:</p>
                    <code class="block bg-[#030303] border border-white/10 rounded-lg p-3 text-amber-300 break-all">@{{ `id` }}</code>
                </div>
            </div>
        </details>
    </div>

    {{-- ===================== Secure : data, never code ===================== --}}
    <div id="secure" class="bg-[#0a0a0a] border border-emerald-500/30 rounded-2xl p-6 space-y-6 max-w-xl mx-auto shadow-xl scroll-mt-24">
        <div class="space-y-2 text-center">
            <span class="text-xs font-mono px-2 py-1 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Secure</span>
            <h3 class="text-sm font-mono text-white font-bold">Greeting (Fixed Template)</h3>
            <p class="text-xs text-slate-400">Try every payload from the previous levels. None of them will run.</p>
        </div>

        <form action="{{ route('vulns.ssti') }}#secure" method="GET" class="space-y-4">
            <input type="hidden" name="level" value="secure">
            <div class="space-y-1">
                <label class="block text-xs font-mono text-slate-400">Your name:</label>
                <input type="text" name="template" value="{{ $level === 'secure' ? $template : '' }}" placeholder="Type something..." class="w-full bg-[#030303] border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-emerald-500/50" required>
            </div>
            <button type="submit" class="w-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 hover:bg-emerald-500/20 py-3 rounded-xl text-xs font-mono transition-colors font-bold">
                Greet Me (Secure)
            </button>
        </form>

        @if($level === 'secure' && $template !== '')
            <div class="bg-[#030303] border border-white/10 rounded-xl p-4 space-y-2">
                <p class="text-[10px] font-mono uppercase text-slate-500">Server response:</p>
                <div class="text-sm text-white font-mono break-words">{{ $output }}</div>
            </div>
        @endif

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show secure code</summary>
            <pre class="mt-3 bg-[#030303] border border-white/10 rounded-xl p-4 overflow-x-auto text-[11px] text-emerald-300">@verbatim
// The TEMPLATE is a fixed string written by the developer.
// The user's input is only ever a VARIABLE passed into it.
$output = Blade::render('Hello, {{ $name }}!', ['name' => $template]);
@endverbatim</pre>
            <div class="mt-3 space-y-2">
                <p>Even <code class="text-emerald-300">@{{ system('id') }}</code> is just the text assigned to
                <code class="text-emerald-300">$name</code>. It is never compiled, so it is printed back
                (HTML-encoded, same as the XSS fix) instead of executed.</p>
                <p>The real fix for SSTI is architectural: never let user input become the
                   template source. User input may only ever flow in as <em>data</em>.</p>
            </div>
        </details>
    </div>
</div>
