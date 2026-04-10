<div class="flex flex-col min-h-screen bg-gray-950">

    {{-- Header --}}
    <header class="bg-gray-900 px-4 py-4 pt-safe flex items-center gap-3 border-b border-gray-800">
        <a href="{{ route('home') }}" class="text-blue-400 font-medium text-sm">← Back</a>
        <h1 class="flex-1 text-center font-semibold text-white">Add Expense</h1>
        <div class="w-12"></div>
    </header>

    {{-- Status --}}
    @if ($status)
        <div class="mx-4 mt-3 bg-blue-900/40 border border-blue-700 text-blue-300 rounded-2xl px-4 py-2.5 text-sm">
            {{ $status }}
        </div>
    @endif

    <div class="flex-1 px-4 pt-4 pb-10 space-y-4">

        {{-- Scan receipt button --}}
        <button wire:click="scanReceipt"
            class="w-full bg-gray-800 border border-gray-700 border-dashed rounded-2xl py-4
           flex items-center justify-center gap-2 text-sm font-medium
           active:scale-95 transition-transform
           {{ $scanning ? 'text-blue-400' : 'text-gray-400' }}">
            @if ($scanning)
                ⏳ Scanning...
            @else
                📷 Scan Receipt
            @endif
        </button>

        {{-- Merchant --}}
        <div class="bg-gray-900 rounded-2xl p-4 space-y-1">
            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Merchant / Description</label>
            <input type="text" wire:model="merchant" placeholder="e.g. Shoprite, Bolt, Netflix"
                class="w-full bg-transparent text-white text-base outline-none placeholder-gray-600 mt-1">
            @error('merchant')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Amount --}}
        <div class="bg-gray-900 rounded-2xl p-4 space-y-1">
            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Amount
                ({{ $currency }})</label>
            <input type="number" wire:model="amount" placeholder="0.00" step="0.01" min="0"
                class="w-full bg-transparent text-white text-2xl font-bold outline-none placeholder-gray-700 mt-1">
            @error('amount')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Category --}}
        <div class="bg-gray-900 rounded-2xl p-4 space-y-2">
            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Category</label>
            <div class="flex flex-wrap gap-2 mt-1">
                @foreach (\App\Models\Expense::$categories as $cat)
                    <button wire:click="$set('category', '{{ $cat }}')"
                        class="px-3 py-1.5 rounded-xl text-sm font-medium transition-colors
                               {{ $category === $cat ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-400' }}">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Date --}}
        <div class="bg-gray-900 rounded-2xl p-4 space-y-1">
            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Date</label>
            <input type="date" wire:model="date"
                class="w-full bg-transparent text-white text-base outline-none mt-1">
        </div>

        {{-- Note --}}
        <div class="bg-gray-900 rounded-2xl p-4 space-y-1">
            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Note (optional)</label>
            <input type="text" wire:model="note" placeholder="Any extra details..."
                class="w-full bg-transparent text-white text-base outline-none placeholder-gray-600 mt-1">
        </div>

        {{-- Save --}}
        <button wire:click="save"
            class="w-full bg-blue-600 text-white font-bold py-4 rounded-2xl shadow-lg
                   active:scale-95 transition-transform text-base">
            Save Expense
        </button>

    </div>

</div>
