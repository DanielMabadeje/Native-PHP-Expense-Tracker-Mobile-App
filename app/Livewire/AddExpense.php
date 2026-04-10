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
use OpenAI;

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

    public string $note        = '';
    public string $status      = '';
    public string $receiptPath = '';
    public bool   $scanning    = false;

    public function mount(): void
    {
        $this->date     = now()->toDateString();
        $this->category = Expense::$categories[0];
    }

    public function scanReceipt(): void
    {
        $this->status  = 'Opening camera...';
        $this->scanning = true;
        try {
            Camera::getPhoto();
        } catch (\Throwable $e) {
            $this->scanning = false;
            $this->status   = 'Error: ' . $e->getMessage();
            Log::error('Camera::getPhoto failed: ' . $e->getMessage());
        }
    }

    #[OnNative(PhotoTaken::class)]
    public function handlePhotoTaken(string $path, string $mimeType): void
    {
        $this->scanning    = false;
        $this->receiptPath = $path;
        $this->status      = 'Analysing receipt...';

        try {
            $this->extractFromReceipt($path, $mimeType);
        } catch (\Throwable $e) {
            $this->status = 'Could not read receipt. Please fill in manually.';
            Log::error('OpenAI Vision failed: ' . $e->getMessage());
        }
    }

    private function extractFromReceipt(string $path, string $mimeType): void
    {
        $imageData = @file_get_contents($path);

        if (empty($imageData)) {
            $this->status = 'Could not read photo. Please fill in manually.';
            return;
        }

        $base64 = base64_encode($imageData);
        $client = OpenAI::client(config('services.openai.key'));

        $response = $client->chat()->create([
            'model'      => 'gpt-4o',
            'max_tokens' => 300,
            'messages'   => [
                [
                    'role'    => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'This is a receipt. Extract the following and respond ONLY with valid JSON, no markdown, no explanation:
{
  "merchant": "store or restaurant name",
  "amount": 0.00,
  "date": "YYYY-MM-DD",
  "category": "one of: Food & Dining, Transport, Shopping, Bills & Utilities, Health, Entertainment, Other"
}
If you cannot determine a value, use an empty string for text fields and 0 for amount.',
                        ],
                        [
                            'type'      => 'image_url',
                            'image_url' => [
                                'url'    => "data:{$mimeType};base64,{$base64}",
                                'detail' => 'low',
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $content = $response->choices[0]->message->content ?? '';
        Log::info('OpenAI receipt response: ' . $content);

        $data = json_decode($content, true);

        if (! $data) {
            $this->status = 'Could not parse receipt. Please fill in manually.';
            return;
        }

        // Auto-fill form fields with extracted data
        if (! empty($data['merchant'])) {
            $this->merchant = $data['merchant'];
        }

        if (! empty($data['amount']) && $data['amount'] > 0) {
            $this->amount = (string) $data['amount'];
        }

        if (! empty($data['date'])) {
            try {
                $this->date = \Carbon\Carbon::parse($data['date'])->toDateString();
            } catch (\Throwable) {
                // keep today's date
            }
        }

        if (! empty($data['category'])) {
            // Match to one of our categories
            $matched = collect(Expense::$categories)->first(
                fn($cat) => str_contains(strtolower($cat), strtolower($data['category']))
            );
            if ($matched) {
                $this->category = $matched;
            }
        }

        $this->status = 'Receipt scanned! Please review and save.';
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

        $this->redirectRoute('home');
    }

    public function render()
    {
        $currency = Setting::get('currency_symbol', '₦');
        return view('livewire.add-expense', compact('currency'));
    }
}