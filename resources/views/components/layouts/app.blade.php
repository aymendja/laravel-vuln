<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Laravel Vuln Lab' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'Fira Code', monospace; }
        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-[#050505] text-slate-100 min-h-screen bg-grid-pattern selection:bg-red-500 selection:text-white flex flex-col justify-between">

    <header class="border-b border-white/10 bg-[#050505]/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ url('/') }}" class="font-mono font-bold text-lg tracking-wider text-white flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
                VULN<span class="text-red-500">LAB</span>_
            </a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-16 w-full flex-grow">
        {{ $slot }}
    </main>

    <footer class="border-t border-white/10 py-8 text-center text-xs font-mono text-slate-500">
        Laravel Security Lab &bull; Educational Vulnerability Research Environment
    </footer>z

</body>
</html>