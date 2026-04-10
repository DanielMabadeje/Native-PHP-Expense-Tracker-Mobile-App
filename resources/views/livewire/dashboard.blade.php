<div class="flex flex-col min-h-screen bg-gray-950 pb-24">

    {{-- Header --}}
    <div class="bg-gray-900 px-4 pt-safe pb-6">
        <div class="flex items-center justify-between pt-3">
            <div>
                <h1 class="text-2xl font-bold text-white">Expenses</h1>
                <p class="text-gray-400 text-sm">{{ now()->format('F Y') }}</p>
            </div>
            <a href="{{ route('settings') }}"
                class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-lg">
                ⚙️
            </a>
        </div>

        {{-- Monthly total --}}
        <div class="mt-5 bg-blue-600 rounded-3xl p-5">
            <p class="text-blue-200 text-sm font-medium">Total this month</p>
            <p class="text-4xl font-bold text-white mt-1">
                {{ $currency }}{{ number_format($monthlyTotal, 2) }}
            </p>
        </div>
    </div>

    <div class="px-4 mt-4 space-y-4">

        {{-- Category breakdown --}}
        @if ($byCategory->isNotEmpty())
            <div class="bg-gray-900 rounded-2xl p-4">
                <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-3">By Category</h2>
                <div class="space-y-3">
                    @foreach ($byCategory as $cat => $total)
                        <div class="flex items-center justify-between">
                            <span class="text-white text-sm">{{ $cat }}</span>
                            <span class="text-white font-semibold text-sm">
                                {{ $currency }}{{ number_format($total, 2) }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-800 rounded-full h-1.5">
                            <div class="bg-blue-500 h-1.5 rounded-full"
                                style="width: {{ $monthlyTotal > 0 ? round(($total / $monthlyTotal) * 100) : 0 }}%">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Recent expenses --}}
        <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Recent</h2>

        @forelse ($recent as $expense)
            <div class="bg-gray-900 rounded-2xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center text-lg flex-shrink-0">
                    {{ mb_substr($expense->category, 0, 2) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white font-medium truncate">{{ $expense->merchant }}</p>
                    <p class="text-gray-500 text-xs mt-0.5">
                        {{ $expense->category }} · {{ $expense->date->format('d M') }}
                    </p>
                </div>
                <p class="text-white font-bold text-sm flex-shrink-0">
                    {{ $currency }}{{ number_format($expense->amount, 2) }}
                </p>
            </div>
        @empty
            <div class="text-center py-16 text-gray-600">
                <p class="text-5xl mb-3">💸</p>
                <p class="text-lg font-medium text-gray-500">No expenses yet</p>
                <p class="text-sm mt-1">Tap + to add your first expense</p>
            </div>
        @endforelse

    </div>

    {{-- Bottom nav --}}
    <div class="fixed bottom-0 left-0 right-0 bg-gray-900 border-t border-gray-800 px-4 py-3 pb-safe flex gap-3">
        <a href="{{ route('home') }}"
            class="flex-1 flex flex-col items-center text-blue-400 text-xs font-medium gap-1">
            <span class="text-xl">📊</span> Dashboard
        </a>
        <a href="{{ route('add') }}"
            class="flex-1 flex items-center justify-center bg-blue-600 text-white font-bold py-3 rounded-2xl text-base gap-2">
            <span>+</span> Add Expense
        </a>
        <a href="{{ route('settings') }}"
            class="flex-1 flex flex-col items-center text-gray-500 text-xs font-medium gap-1">
            <span class="text-xl">⚙️</span> Settings
        </a>
    </div>

</div>