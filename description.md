# 💸 Expense Tracker — NativePHP Mobile App

A native Android (and iOS) expense tracking app built with **Laravel**, **Livewire**, and **NativePHP Mobile**. Scan receipts with your camera, auto-extract expense details using **OpenAI Vision (GPT-4o)**, and keep a running record of your spending — all stored locally on your device.

---

## 🧩 Problem Statement

Managing personal or business expenses is painful. Most people either:

- Forget to log expenses until it's too late
- Have to manually type out every receipt detail
- Rely on expensive SaaS apps that store your financial data on their servers
- Carry physical receipts that get lost or damaged

There's no lightweight, offline-first, AI-powered expense tracker that feels native on mobile and keeps your data on your own device.

---

## ✅ Solution

**Expense Tracker** solves this by combining:

- 📷 **Native camera access** — photograph a receipt the moment you get it
- 🤖 **OpenAI Vision (GPT-4o)** — automatically extracts merchant name, amount, date, and category from the photo
- 📊 **Local SQLite storage** — all data stays on your device, no accounts, no cloud
- 💱 **Multi-currency support** — set your preferred currency (NGN, USD, GBP, EUR, and more)
- 📋 **Monthly dashboard** — see your total spend and category breakdown at a glance

---

## 🛠 Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| UI | Livewire 3 + Tailwind CSS |
| Native Runtime | NativePHP Mobile v3 |
| AI | OpenAI GPT-4o Vision |
| Database | SQLite (on-device) |
| HTTP Client | Guzzle |

---

## 🤖 OpenAI Integration

When a user photographs a receipt, the app:

1. Reads the image from the device filesystem
2. Base64-encodes it
3. Sends it to **GPT-4o** with a structured prompt requesting JSON output
4. Parses the response and auto-fills the expense form with:
   - Merchant / store name
   - Total amount
   - Date of purchase
   - Expense category

The prompt uses `detail: low` to minimise token usage and cost.

### Adding Your OpenAI Key

1. Add to `.env`:
```env
OPENAI_API_KEY=sk-your-key-here
```

2. Add to `config/services.php`:
```php
'openai' => [
    'key' => env('OPENAI_API_KEY'),
],
```

3. Install the PHP client:
```bash
composer require openai-php/client
```

---

## 🚀 Running the Project

### Requirements

- PHP 8.2+
- Node.js 18+
- Composer
- Android Studio (for Android builds)
- NativePHP Mobile licence or free tier

### Setup

```bash
# Clone the repo
git clone https://github.com/your-username/expense-tracker.git
cd expense-tracker

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Copy environment file
cp .env.example .env
php artisan key:generate

# Add your OpenAI key to .env
OPENAI_API_KEY=sk-your-key-here

# Run migrations
php artisan migrate

# Build frontend assets
npm run build
```

### Running on Android

```bash
# Full build and run on emulator or connected device
php artisan native:run android

# Fast preview on physical device (recommended for development)
php artisan native:jump
```

### Running on iOS

```bash
php artisan native:run ios
```

---

## 📱 Features

- [x] Add expenses manually (merchant, amount, category, date, note)
- [x] Scan receipts with camera → auto-fill via OpenAI Vision
- [x] Monthly spending dashboard with category breakdown
- [x] Multi-currency support with in-app switcher
- [x] Delete individual expenses or clear all data
- [ ] Export monthly summary as CSV (coming soon)
- [ ] Push notifications for budget limits (coming soon)
- [ ] Bank statement import (coming soon)

---

## 🔌 Adding Other Integrations

The app is structured so additional integrations can be dropped in cleanly.

### Exchange Rate API (live currency conversion)
Use [exchangerate-api.com](https://exchangerate-api.com) (free tier available):
```php
// In a service class
$response = Http::get("https://v6.exchangerate-api.com/v6/{$key}/latest/USD");
$rates = $response->json('conversion_rates');
```

### Google Sheets Export
Use [Saloon](https://docs.saloon.dev) or Guzzle to push monthly summaries to a Google Sheet via the Sheets API — useful for accountants or business owners.

### WhatsApp / Telegram Bot
Send a daily or weekly spending summary to yourself via the Twilio WhatsApp API or Telegram Bot API — no need to open the app.

### Receipt OCR Alternative (without OpenAI)
If you don't want to use OpenAI, [OCR.space](https://ocr.space) offers a free REST API for text extraction from images. Lower accuracy for structured data but zero cost.

---

## 📁 Project Structure

```
app/
├── Livewire/
│   ├── Dashboard.php        # Monthly overview + recent expenses
│   ├── AddExpense.php       # Manual entry + receipt scanning
│   └── AppSettings.php      # Currency selection + data management
├── Models/
│   ├── Expense.php
│   └── Setting.php
├── Providers/
│   └── NativeServiceProvider.php

resources/views/
├── components/layouts/app.blade.php
└── livewire/
    ├── dashboard.blade.php
    ├── add-expense.blade.php
    └── app-settings.blade.php
```

---

## 📄 Licence

MIT — free to use, modify, and distribute.