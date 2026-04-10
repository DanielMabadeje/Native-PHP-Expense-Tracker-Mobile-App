<?php

namespace App\Livewire;

use App\Models\Expense;
use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class AppSettings extends Component
{
    public string $currencySymbol = '₦';
    public string $currencyName   = 'Nigerian Naira';
    public string $status         = '';

    public array $currencies = [
        ['symbol' => '₦', 'name' => 'Nigerian Naira (NGN)'],
        ['symbol' => '$', 'name' => 'US Dollar (USD)'],
        ['symbol' => '£', 'name' => 'British Pound (GBP)'],
        ['symbol' => '€', 'name' => 'Euro (EUR)'],
        ['symbol' => 'KES', 'name' => 'Kenyan Shilling (KES)'],
        ['symbol' => 'GHS', 'name' => 'Ghanaian Cedi (GHS)'],
        ['symbol' => 'ZAR', 'name' => 'South African Rand (ZAR)'],
    ];

    public function mount(): void
    {
        $this->currencySymbol = Setting::get('currency_symbol', '₦');
        $this->currencyName   = Setting::get('currency_name', 'Nigerian Naira (NGN)');
    }

    public function setCurrency(string $symbol, string $name): void
    {
        $this->currencySymbol = $symbol;
        $this->currencyName   = $name;
        Setting::set('currency_symbol', $symbol);
        Setting::set('currency_name', $name);
        $this->status = 'Currency updated to ' . $name;
    }

    public function clearAllData(): void
    {
        Expense::truncate();
        $this->status = 'All expenses cleared.';
    }

    public function render()
    {
        $totalExpenses = Expense::count();
        return view('livewire.app-settings', compact('totalExpenses'));
    }
}