<x-layouts.app :title="ucfirst(str_replace('-', ' ', $slug)) . ' - Laravel Vuln Lab'">
    <div class="space-y-8">
        <div>
            <a href="{{ url('/') }}" class="text-xs font-mono text-slate-400 hover:text-white transition-colors flex items-center gap-1">
                &larr; Back to Vulnerability Catalog
            </a>
        </div>

        @include($viewPath)
    </div>
</x-layouts.app>