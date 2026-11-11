<div class="space-y-8">
    <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-8 space-y-4 shadow-xl">
        <div class="flex items-center gap-3">
            <span class="text-xs font-mono px-3 py-1 rounded-full bg-red-500/10 text-red-400 border border-red-500/20">
                Critical Severity
            </span>
            <span class="text-xs font-mono text-slate-500">CVE: A03:2021-SQLi (Authentication Bypass)</span>
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">
            SQL Injection Authentication Bypass Lab
        </h1>
        <p class="text-sm text-slate-400 leading-relaxed">
            Test a real application login form using standard HTTP POST requests. Compare vulnerable raw concatenation against secure parameterized queries.
        </p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 rounded-xl p-4 font-mono text-xs">
             {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/30 text-red-300 rounded-xl p-4 font-mono text-xs">
             {{ session('error') }}
        </div>
    @endif

    <div class="bg-[#0a0a0a] border border-red-500/30 rounded-2xl p-6 space-y-6 max-w-xl mx-auto shadow-xl">
        <div class="space-y-2 text-center">
            <span class="text-xs font-mono px-2 py-1 rounded bg-red-500/20 text-red-400 border border-red-500/30">Vulnerable</span>
            <h3 class="text-sm font-mono text-white font-bold">Standard Login Form (Insecure)</h3>
        </div>

        <form action="{{ route('lab.sql-login', ['type' => 'vulnerable']) }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-1">
                <label class="block text-xs font-mono text-slate-400">Email Address:</label>
                <input type="text" name="email" placeholder="admin@example.com" class="w-full bg-[#030303] border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-red-500/50" required>
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-mono text-slate-400">Password:</label>
                <input type="password" name="password" placeholder="Password" class="w-full bg-[#030303] border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-red-500/50">
            </div>

            <button type="submit" class="w-full bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500/20 py-3 rounded-xl text-xs font-mono transition-colors font-bold">
                Login via Vulnerable Code
            </button>
        </form>
    </div>

    <div class="bg-[#0a0a0a] border border-emerald-500/30 rounded-2xl p-6 space-y-6 max-w-xl mx-auto shadow-xl">
        <div class="space-y-2 text-center">
            <span class="text-xs font-mono px-2 py-1 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Secure</span>
            <h3 class="text-sm font-mono text-white font-bold">Standard Login Form (Secure)</h3>
            <p class="text-xs text-slate-400">Try the same payload here—it will fail safely.</p>
        </div>

        <form action="{{ route('lab.sql-login', ['type' => 'secure']) }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-1">
                <label class="block text-xs font-mono text-slate-400">Email Address:</label>
                <input type="text" name="email" placeholder="admin@example.com" class="w-full bg-[#030303] border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-emerald-500/50" required>
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-mono text-slate-400">Password:</label>
                <input type="password" name="password" placeholder="Password" class="w-full bg-[#030303] border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-emerald-500/50">
            </div>

            <button type="submit" class="w-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 hover:bg-emerald-500/20 py-3 rounded-xl text-xs font-mono transition-colors font-bold">
                Login via Secure Code
            </button>
        </form>
    </div>
</div>