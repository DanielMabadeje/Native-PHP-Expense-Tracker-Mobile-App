<?php

namespace App\Livewire;

use App\Models\Expense;
use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $currency     = Setting::get('currency_symbol', '₦');
        $now          = now();
        $monthlyTotal = Expense::whereYear('date', $now->year)
            ->whereMonth('date', $now->month)
            ->sum('amount');

        $byCategory = Expense::whereYear('date', $now->year)
            ->whereMonth('date', $now->month)
            ->get()
            ->groupBy('category')
            ->map(fn($g) => $g->sum('amount'))
            ->sortByDesc(fn($v) => $v);

        $recent = Expense::latest('date')->take(10)->get();

        return view('livewire.dashboard', compact('currency', 'monthlyTotal', 'byCategory', 'recent'));
    }
}