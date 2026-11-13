@php
    // Built from pieces on purpose: a literal PHP open tag inside a Blade file would be executed, not displayed.
    $proofFile = '<' . '?php echo "executed on the server: " . (6 * 7);';
@endphp

<div class="space-y-8">

    {{-- ===================== Header ===================== --}}
    <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-8 space-y-4 shadow-xl">
        <div class="flex items-center gap-3">
            <span class="text-xs font-mono px-3 py-1 rounded-full bg-red-500/10 text-red-400 border border-red-500/20">
                Critical Severity
            </span>
            <span class="text-xs font-mono text-slate-500">CWE-434: Unrestricted Upload of File with Dangerous Type</span>
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">
            Unrestricted File Upload Lab
        </h1>
        <p class="text-sm text-slate-400 leading-relaxed">
            An avatar uploader should only accept pictures. When the server does not check what it
            receives, an attacker can upload a <strong class="text-white">script</strong> instead. If that
            script is saved in a folder the web server executes, requesting its URL runs the attacker's code
            on the server.
        </p>
        <div class="bg-[#030303] border border-white/10 rounded-xl p-3 text-xs font-mono text-slate-400 space-y-1">
            <p>Level 1 and 2 save files in: <span class="text-white">public/uploads/</span> (served by the web server)</p>
            <p>Secure level saves files in: <span class="text-emerald-300">storage/app/private/lab-uploads/</span> (not served)</p>
            <p>Target: get the server to <span class="text-red-300">run</span> the file you uploaded, not just store it.</p>
        </div>
        <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-3 text-xs text-amber-300">
            ⚠️ Level 1 and Level 2 let you place executable PHP on this machine. Keep this lab local and
            never expose it to the internet. Uploaded test files stay in <span class="font-mono">public/uploads/</span> until you delete them.
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
            <h3 class="text-sm font-mono text-white font-bold">Avatar Upload (No Protection)</h3>
            <p class="text-xs text-slate-400">Goal: make the server execute a file you uploaded.</p>
        </div>

        <form action="{{ route('vulns.file-upload.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="level" value="level1">
            <div class="space-y-1">
                <label class="block text-xs font-mono text-slate-400">Choose a file:</label>
                <input type="file" name="upload" class="w-full bg-[#030303] border border-white/10 rounded-xl px-4 py-2.5 text-xs text-slate-300 font-mono file:mr-3 file:rounded-lg file:border-0 file:bg-red-500/20 file:px-3 file:py-1 file:text-red-300" required>
            </div>
            <button type="submit" class="w-full bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500/20 py-3 rounded-xl text-xs font-mono transition-colors font-bold">
                Upload (Vulnerable)
            </button>
        </form>

        <x-upload-result :result="$result" level="level1" />

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show vulnerable code</summary>
            <pre class="mt-3 bg-[#030303] border border-white/10 rounded-xl p-4 overflow-x-auto text-[11px] text-red-300">@verbatim
// Controller
$name = $file->getClientOriginalName();          // name chosen by the client
$file->move(public_path('uploads/level1'), $name); // no check at all
                                                 // and public/ is executed by the web server
@endverbatim</pre>
        </details>

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show solution</summary>
            <div class="mt-3 space-y-2">
                <p>Create a file named <span class="text-red-300">proof.php</span> that contains this single line:</p>
                <code class="block bg-[#030303] border border-white/10 rounded-lg p-3 text-red-300 break-all">{{ $proofFile }}</code>
                <p>Upload it, then click the link in the result. The browser shows the output of the code, not the code itself. That means the server <em>ran</em> your file. A real attacker would put something far more harmful in it, which is why this is Critical.</p>
            </div>
        </details>
    </div>

    {{-- ===================== Level 2 : weak filter ===================== --}}
    <div id="level2" class="bg-[#0a0a0a] border border-amber-500/30 rounded-2xl p-6 space-y-6 max-w-xl mx-auto shadow-xl scroll-mt-24">
        <div class="space-y-2 text-center">
            <span class="text-xs font-mono px-2 py-1 rounded bg-amber-500/20 text-amber-400 border border-amber-500/30">Vulnerable &middot; Level 2</span>
            <h3 class="text-sm font-mono text-white font-bold">Avatar Upload (Content-Type Filter)</h3>
            <p class="text-xs text-slate-400">The developer now only accepts files labelled as images. Can you still get a script through?</p>
        </div>

        <form action="{{ route('vulns.file-upload.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="level" value="level2">
            <div class="space-y-1">
                <label class="block text-xs font-mono text-slate-400">Choose a file:</label>
                <input type="file" name="upload" class="w-full bg-[#030303] border border-white/10 rounded-xl px-4 py-2.5 text-xs text-slate-300 font-mono file:mr-3 file:rounded-lg file:border-0 file:bg-amber-500/20 file:px-3 file:py-1 file:text-amber-300" required>
            </div>
            <button type="submit" class="w-full bg-amber-500/10 border border-amber-500/30 text-amber-400 hover:bg-amber-500/20 py-3 rounded-xl text-xs font-mono transition-colors font-bold">
                Upload (Filtered)
            </button>
        </form>

        <x-upload-result :result="$result" level="level2" />

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show vulnerable code</summary>
            <pre class="mt-3 bg-[#030303] border border-white/10 rounded-xl p-4 overflow-x-auto text-[11px] text-amber-300">@verbatim
// Controller
$type = $file->getClientMimeType();       // the header the CLIENT sent
if (!in_array($type, ['image/jpeg', 'image/png', 'image/gif'])) {
    // reject
}
$file->move(public_path('uploads/level2'), $file->getClientOriginalName());
// extension and real content are never checked
@endverbatim</pre>
        </details>

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show solution</summary>
            <div class="mt-3 space-y-3">
                <p>The <span class="text-amber-300">Content-Type</span> of the file is written by the browser, or by whatever tool sends the request. You can change it.</p>

                <div class="space-y-1">
                    <p class="text-slate-500">1. Upload <span class="text-amber-300">proof.php</span> normally. It is rejected, because the browser labels it as a script type.</p>
                </div>

                <div class="space-y-1">
                    <p class="text-slate-500">2. Upload it again with an intercepting proxy (Burp Suite, or the browser's "Edit and Resend" in the Network tab). In the file part of the request, change the line:</p>
                    <code class="block bg-[#030303] border border-white/10 rounded-lg p-3 text-amber-300 break-all">Content-Type: application/x-php</code>
                    <p class="text-slate-500">to:</p>
                    <code class="block bg-[#030303] border border-white/10 rounded-lg p-3 text-amber-300 break-all">Content-Type: image/png</code>
                </div>

                <p>The filter is satisfied, the file keeps its <span class="text-amber-300">.php</span> name, and opening its link executes it. Nothing about the file itself changed, only the label.</p>
            </div>
        </details>
    </div>

    {{-- ===================== Secure ===================== --}}
    <div id="secure" class="bg-[#0a0a0a] border border-emerald-500/30 rounded-2xl p-6 space-y-6 max-w-xl mx-auto shadow-xl scroll-mt-24">
        <div class="space-y-2 text-center">
            <span class="text-xs font-mono px-2 py-1 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Secure</span>
            <h3 class="text-sm font-mono text-white font-bold">Avatar Upload (Validated)</h3>
            <p class="text-xs text-slate-400">Try both earlier attacks here. Then upload a real PNG, JPG or GIF to see the accepted case.</p>
        </div>

        <form action="{{ route('vulns.file-upload.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="level" value="secure">
            <div class="space-y-1">
                <label class="block text-xs font-mono text-slate-400">Choose an image:</label>
                <input type="file" name="upload" class="w-full bg-[#030303] border border-white/10 rounded-xl px-4 py-2.5 text-xs text-slate-300 font-mono file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-500/20 file:px-3 file:py-1 file:text-emerald-300" required>
            </div>
            <button type="submit" class="w-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 hover:bg-emerald-500/20 py-3 rounded-xl text-xs font-mono transition-colors font-bold">
                Upload (Secure)
            </button>
        </form>

        <x-upload-result :result="$result" level="secure" />

        <details class="text-xs font-mono text-slate-400">
            <summary class="cursor-pointer text-slate-300 hover:text-white">Show secure code</summary>
            <pre class="mt-3 bg-[#030303] border border-white/10 rounded-xl p-4 overflow-x-auto text-[11px] text-emerald-300">@verbatim
// Controller
Validator::make(['upload' => $file], [
    'upload' => [
        'required', 'file',
        'max:2048',                                  // size limit (KB)
        'mimes:jpg,jpeg,png,gif',                    // allowlist, checked on real content
        'mimetypes:image/jpeg,image/png,image/gif',
    ],
]);

$storedName = $file->hashName();                     // random name, extension from content
Storage::disk('local')->putFileAs('lab-uploads', $file, $storedName);
// stored in storage/app/private, outside the web root
@endverbatim</pre>
            <div class="mt-3 space-y-2">
                <p>Each defence closes one of the earlier holes:</p>
                <table class="w-full text-left border border-white/10 rounded-lg overflow-hidden">
                    <thead class="bg-white/5 text-slate-300">
                        <tr><th class="px-3 py-2">Defence</th><th class="px-3 py-2">What it stops</th></tr>
                    </thead>
                    <tbody class="text-emerald-300">
                        <tr class="border-t border-white/10"><td class="px-3 py-2">Allowlist of types</td><td class="px-3 py-2">Unknown dangerous types, which a blacklist always misses</td></tr>
                        <tr class="border-t border-white/10"><td class="px-3 py-2">Check the real content</td><td class="px-3 py-2">Spoofed Content-Type and fake extensions</td></tr>
                        <tr class="border-t border-white/10"><td class="px-3 py-2">Random server-side name</td><td class="px-3 py-2">Attacker-chosen names and extensions such as .php</td></tr>
                        <tr class="border-t border-white/10"><td class="px-3 py-2">Store outside public/</td><td class="px-3 py-2">Execution and direct access, even if something slips through</td></tr>
                        <tr class="border-t border-white/10"><td class="px-3 py-2">Size limit</td><td class="px-3 py-2">Filling the disk with huge files</td></tr>
                    </tbody>
                </table>
            </div>
        </details>
    </div>
</div>
