<div class="flex flex-col min-h-screen bg-gray-950">

    {{-- Header --}}
    <header class="bg-gray-900 px-4 py-4 pt-safe flex items-center gap-3 border-b border-gray-800">
        <a href="{{ route('home') }}" class="text-blue-400 font-medium text-sm">← Back</a>
        <h1 class="flex-1 text-center font-semibold text-white">Settings</h1>
        <div class="w-12"></div>
    </header>

    {{-- Status --}}
    @if ($status)
        <div class="mx-4 mt-3 bg-blue-900/40 border border-blue-700 text-blue-300 rounded-2xl px-4 py-2.5 text-sm">
            {{ $status }}
        </div>
    @endif

    <div class="px-4 pt-4 space-y-4">

        {{-- Currency --}}
        <div class="bg-gray-900 rounded-2xl p-4">
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-3">Currency</h2>
            <div class="space-y-2">
                @foreach ($currencies as $c)
                    <button
                        wire:click="setCurrency('{{ $c['symbol'] }}', '{{ $c['name'] }}')"
                        class="w-full flex items-center justify-between px-3 py-3 rounded-xl transition-colors
                               {{ $currencySymbol === $c['symbol']
                                   ? 'bg-blue-600 text-white'
                                   : 'bg-gray-800 text-gray-300' }}">
                        <span class="text-sm font-medium">{{ $c['name'] }}</span>
                        <span class="font-bold text-base">{{ $c['symbol'] }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Data --}}
        <div class="bg-gray-900 rounded-2xl p-4">
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-3">Data</h2>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white text-sm font-medium">Total expenses stored</p>
                    <p class="text-gray-500 text-xs mt-0.5">{{ $totalExpenses }} {{ Str::plural('record', $totalExpenses) }}</p>
                </div>
                <button
                    wire:click="clearAllData"
                    wire:confirm="This will delete all your expense records. Are you sure?"
                    class="bg-red-900/40 text-red-400 text-xs font-semibold px-4 py-2 rounded-xl">
                    Clear All
                </button>
            </div>
        </div>

    </div>

</div>