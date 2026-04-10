<?php

namespace App\Livewire;

use App\Models\Expense;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Native\Mobile\Attributes\OnNative;
use Native\Mobile\Events\Camera\PhotoTaken;
use Native\Mobile\Facades\Camera;

#[Layout('components.layouts.app')]
class AddExpense extends Component
{
    #[Validate('required|string|min:1')]
    public string $merchant = '';

    #[Validate('required|numeric|min:0.01')]
    public string $amount = '';

    #[Validate('required|string')]
    public string $category = '';

    #[Validate('required|date')]
    public string $date = '';

    public string $note = '';
    public string $status = '';
    public string $receiptPath = '';

    public function mount(): void
    {
        $this->date     = now()->toDateString();
        $this->category = Expense::$categories[0];
    }

    public function scanReceipt(): void
    {
        $this->status = 'Opening camera...';
        try {
            Camera::getPhoto();
        } catch (\Throwable $e) {
            $this->status = 'Error: ' . $e->getMessage();
            Log::error('Camera::getPhoto failed: ' . $e->getMessage());
        }
    }

    #[OnNative(PhotoTaken::class)]
    public function handlePhotoTaken(string $path): void
    {
        $this->receiptPath = $path;
        // TODO: Send $path to OpenAI Vision to extract merchant/amount/date/category
        // For now just store the path and let user fill in manually
        $this->status = 'Receipt photo saved. Fill in the details below.';
    }

    public function save(): void
    {
        $this->validate();

        Expense::create([
            'merchant'     => trim($this->merchant),
            'amount'       => (float) $this->amount,
            'category'     => $this->category,
            'date'         => $this->date,
            'note'         => trim($this->note),
            'receipt_path' => $this->receiptPath ?: null,
        ]);

        $this->reset(['merchant', 'amount', 'note', 'receiptPath', 'status']);
        $this->date     = now()->toDateString();
        $this->category = Expense::$categories[0];
        $this->status   = 'Expense saved!';

        $this->redirectRoute('home');
    }

    public function render()
    {
        $currency = Setting::get('currency_symbol', '₦');
        return view('livewire.add-expense', compact('currency'));
    }
}