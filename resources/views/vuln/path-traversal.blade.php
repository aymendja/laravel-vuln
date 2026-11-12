<div class="space-y-8">

    {{-- ===================== Header ===================== --}}
    <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-8 space-y-4 shadow-xl">
        <div class="flex items-center gap-3">
            <span class="text-xs font-mono px-3 py-1 rounded-full bg-red-500/10 text-red-400 border border-red-500/20">
                High Severity
            </span>
            <span class="text-xs font-mono text-slate-500">CWE-22: Path Traversal</span>
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">
            Path Traversal Lab
        </h1>
        <p class="text-sm text-slate-400 leading-relaxed">
            A document viewer opens the file whose name you send. The developer assumed users would only
            ask for files inside the documents folder, but the filename is never checked. Use
            <span class="text-white font-mono">{{ '../' }}</span> sequences to climb out of the folder and read a file
            that was never meant to be public.
        </p>
        <div class="bg-[#030303] border border-white/10 rounded-xl p-3 text-xs font-mono text-slate-400 space-y-1">
            <p>Documents folder: <span class="text-white">resources/lab-files/docs/</span></p>
            <p>Available documents:
                @foreach($docs as $doc)
                    <span class="text-emerald-300">{{ $doc }}</span>@if(!$loop->last), @endif
                @endforeach
            </p>
            <p>Target: read the flag stored <span class="text-red-300">outside</span> that folder, or the app's <span class="text-red-300">.env</span> file.</p>
        </div>
        <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-3 text-xs text-amber-300">
            ⚠️ Level 1 and Level 2 can read any file the web server user can access. Keep this lab local and
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
            <h3 class="text-sm font-mono text-white font-bold">Document Viewer (No Protection)</h3>
            <p class="text-xs text-slate-400">Goal: read a file outside the documents folder.</p>
        </div>

        <form action="{{ route('vulns.path-traversal') }}#level1" method="GET" class="space-y-4">
            <input type="hidden" name="level" value="level1">
            <div class="space-y-1">
                <label class="block text-xs font-mono text-slate-400">File name:</label>
                <input type="text" name="file" value="{{ $level === 'level1' ? $file : 'welcome.txt' }}" placeholder="welcome.txt" class="w-full bg-[#030303] border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-red-500/50" required>
            </div>
            <button type="submit" class="w-full bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500/20 py-3 rounded-xl text-xs font-mono transition-colors font-bold">
                Open (Vulnerable)
            </button>
        </form>

        @if($level === 'level1' && $file !== '')
            <div class="bg-[#030303] border border-white/10 rounded-xl p-4 space-y-2">
                <p class="text-[10px] font-mono uppercase text-slate-500">Requested file: <span class="text-slate-300 normal-case">{{ $file }}</span></p>
                @if($error)
                    <p class="text-xs text-red-400 font-mono">{{ $error }}</p>
                @else
                    <pre class="text-xs text-white font-mono whitespace-pre-wrap break-words">{{ $output }}</pre>
                @endif
            </div>
        @endif

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show vulnerable code</summary>
            <pre class="mt-3 bg-[#030303] border border-white/10 rounded-xl p-4 overflow-x-auto text-[11px] text-red-300">@verbatim
// Controller
$path = $docsFolder . '/' . $file;   // user input appended as-is
$content = file_get_contents($path); // nothing checks where $path ends up
@endverbatim</pre>
        </details>

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show solutions</summary>
            <div class="mt-3 space-y-3">
                <p>The path is <span class="text-red-300">docs/</span> + your input, so each <span class="text-red-300">{{ '../' }}</span> moves one folder up:</p>
                <div class="space-y-1">
                    <p class="text-slate-500">1. The flag (one folder up, then into secret/):</p>
                    <code class="block bg-[#030303] border border-white/10 rounded-lg p-3 text-red-300 break-all">{{ '../secret/flag.txt' }}</code>
                </div>
                <div class="space-y-1">
                    <p class="text-slate-500">2. The app's environment file (three folders up = project root):</p>
                    <code class="block bg-[#030303] border border-white/10 rounded-lg p-3 text-red-300 break-all">{{ '../../../.env' }}</code>
                </div>
            </div>
        </details>
    </div>

    {{-- ===================== Level 2 : weak filter ===================== --}}
    <div id="level2" class="bg-[#0a0a0a] border border-amber-500/30 rounded-2xl p-6 space-y-6 max-w-xl mx-auto shadow-xl scroll-mt-24">
        <div class="space-y-2 text-center">
            <span class="text-xs font-mono px-2 py-1 rounded bg-amber-500/20 text-amber-400 border border-amber-500/30">Vulnerable &middot; Level 2</span>
            <h3 class="text-sm font-mono text-white font-bold">Document Viewer (Blacklist Filter)</h3>
            <p class="text-xs text-slate-400">The developer now removes <span class="text-amber-300">{{ '../' }}</span> from the file name. Can you still escape?</p>
        </div>

        <form action="{{ route('vulns.path-traversal') }}#level2" method="GET" class="space-y-4">
            <input type="hidden" name="level" value="level2">
            <div class="space-y-1">
                <label class="block text-xs font-mono text-slate-400">File name:</label>
                <input type="text" name="file" value="{{ $level === 'level2' ? $file : 'welcome.txt' }}" placeholder="welcome.txt" class="w-full bg-[#030303] border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-amber-500/50" required>
            </div>
            <button type="submit" class="w-full bg-amber-500/10 border border-amber-500/30 text-amber-400 hover:bg-amber-500/20 py-3 rounded-xl text-xs font-mono transition-colors font-bold">
                Open (Filtered)
            </button>
        </form>

        @if($level === 'level2' && $file !== '')
            <div class="bg-[#030303] border border-white/10 rounded-xl p-4 space-y-3">
                <div class="space-y-1">
                    <p class="text-[10px] font-mono uppercase text-slate-500">Your input:</p>
                    <div class="text-xs text-slate-400 font-mono break-all">{{ $file }}</div>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-mono uppercase text-slate-500">After the filter:</p>
                    <div class="text-xs text-amber-300 font-mono break-all">{{ str_replace('../', '', $file) }}</div>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-mono uppercase text-slate-500">Result:</p>
                    @if($error)
                        <p class="text-xs text-red-400 font-mono">{{ $error }}</p>
                    @else
                        <pre class="text-xs text-white font-mono whitespace-pre-wrap break-words">{{ $output }}</pre>
                    @endif
                </div>
            </div>
        @endif

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show vulnerable code</summary>
            <pre class="mt-3 bg-[#030303] border border-white/10 rounded-xl p-4 overflow-x-auto text-[11px] text-amber-300">@verbatim
// Controller
$file = str_replace('../', '', $file);   // single pass, runs only once
$path = $docsFolder . '/' . $file;       // still no check on the final path
@endverbatim</pre>
        </details>

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show solutions</summary>
            <div class="mt-3 space-y-3">
                <p>The filter removes <span class="text-amber-300">{{ '../' }}</span> only once and never re-checks the result. Hide a new <span class="text-amber-300">{{ '../' }}</span> inside the one being removed:</p>
                <div class="space-y-1">
                    <p class="text-slate-500">1. Nested sequence. After the filter runs, the leftover characters join into a fresh <span class="text-amber-300">{{ '../' }}</span>:</p>
                    <code class="block bg-[#030303] border border-white/10 rounded-lg p-3 text-amber-300 break-all">{{ '....//secret/flag.txt' }}</code>
                </div>
                <div class="space-y-1">
                    <p class="text-slate-500">2. Same trick, repeated to reach the project root:</p>
                    <code class="block bg-[#030303] border border-white/10 rounded-lg p-3 text-amber-300 break-all">{{ '....//....//....//.env' }}</code>
                </div>
            </div>
        </details>
    </div>

    {{-- ===================== Secure : resolved path check ===================== --}}
    <div id="secure" class="bg-[#0a0a0a] border border-emerald-500/30 rounded-2xl p-6 space-y-6 max-w-xl mx-auto shadow-xl scroll-mt-24">
        <div class="space-y-2 text-center">
            <span class="text-xs font-mono px-2 py-1 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Secure</span>
            <h3 class="text-sm font-mono text-white font-bold">Document Viewer (Resolved Path Check)</h3>
            <p class="text-xs text-slate-400">Try every payload from the previous levels. None of them will work.</p>
        </div>

        <form action="{{ route('vulns.path-traversal') }}#secure" method="GET" class="space-y-4">
            <input type="hidden" name="level" value="secure">
            <div class="space-y-1">
                <label class="block text-xs font-mono text-slate-400">File name:</label>
                <input type="text" name="file" value="{{ $level === 'secure' ? $file : 'welcome.txt' }}" placeholder="welcome.txt" class="w-full bg-[#030303] border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-emerald-500/50" required>
            </div>
            <button type="submit" class="w-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 hover:bg-emerald-500/20 py-3 rounded-xl text-xs font-mono transition-colors font-bold">
                Open (Secure)
            </button>
        </form>

        @if($level === 'secure' && $file !== '')
            <div class="bg-[#030303] border border-white/10 rounded-xl p-4 space-y-2">
                <p class="text-[10px] font-mono uppercase text-slate-500">Requested file: <span class="text-slate-300 normal-case">{{ $file }}</span></p>
                @if($error)
                    <p class="text-xs text-red-400 font-mono">{{ $error }}</p>
                @else
                    <pre class="text-xs text-white font-mono whitespace-pre-wrap break-words">{{ $output }}</pre>
                @endif
            </div>
        @endif

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show secure code</summary>
            <pre class="mt-3 bg-[#030303] border border-white/10 rounded-xl p-4 overflow-x-auto text-[11px] text-emerald-300">@verbatim
// Controller
$base     = realpath($docsFolder);
$resolved = realpath($base . '/' . $file);   // collapses ../ and symlinks

if ($resolved === false
    || !str_starts_with($resolved, $base . DIRECTORY_SEPARATOR)) {
    // reject: the real location is outside the documents folder
}
@endverbatim</pre>
            <div class="mt-3 space-y-2">
                <p>The check runs on where the file <strong class="text-white">really is</strong>, after every <span class="text-emerald-300">{{ '../' }}</span> has been resolved, so no spelling trick can slip past it.</p>
                <p>Even better when possible: never accept a path at all. Accept an ID or pick from an allowlist of known file names.</p>
            </div>
        </details>
    </div>
</div>
