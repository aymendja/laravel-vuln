<x-layouts.app title="Vulnerability Catalog - Laravel Vuln Lab">
    <div class="space-y-12">
        
        <div class="space-y-3 max-w-2xl">
            <span class="text-xs font-mono px-3 py-1 rounded-full bg-red-500/10 text-red-400 border border-red-500/20">
                Security Catalog & Labs
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-white">
                Vulnerability <span class="text-red-500">lab.</span>
            </h1>
            <p class="text-slate-400 text-sm sm:text-base leading-relaxed">
                Select a vulnerability below to launch its interactive testing environment, analyze the vulnerable code patterns, and apply secure architectural fixes.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <x-vuln-card 
                title="SQL Injection (SQLi)" 
                severity="Critical" 
                cve="A03:2021" 
                description="Bypass input sanitization and manipulate raw database queries to extract unauthorized records." 
                link="{{ route('vulns.show', ['slug' => 'sql-injection']) }}"
            />

            <x-vuln-card 
                title="Cross-Site Scripting (XSS)" 
                severity="High" 
                cve="A03:2021-XSS" 
                description="Inject and execute arbitrary client-side JavaScript payloads through unescaped Blade views." 
                link="{{ route('vulns.show', ['slug' => 'xss']) }}"
            />

            <x-vuln-card 
                title="Server-Side Template Injection (SSTI)" 
                severity="Critical" 
                cve="CWE-1336" 
                description="Make the server compile and execute your own code by controlling the Blade template source itself." 
                link="{{ route('vulns.show', ['slug' => 'ssti']) }}"
            />

            <x-vuln-card 
                title="Path Traversal" 
                severity="High" 
                cve="CWE-22" 
                description="Use ../ sequences in a filename to escape the intended folder and read files the app never meant to expose." 
                link="{{ route('vulns.show', ['slug' => 'path-traversal']) }}"
            />

            <x-vuln-card 
                title="Mass Assignment" 
                severity="High" 
                cve="A01:2021" 
                description="Exploit unprotected model attributes to alter critical user privileges via HTTP requests." 
                link="{{ route('vulns.show', ['slug' => 'mass-assignment']) }}"
            />

        </div>

    </div>
</x-layouts.app>