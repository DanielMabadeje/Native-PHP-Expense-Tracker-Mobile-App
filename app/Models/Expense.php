<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['merchant', 'amount', 'category', 'date', 'note', 'receipt_path'];

    protected $casts = ['date' => 'date', 'amount' => 'decimal:2'];

    public static array $categories = [
        '🍔 Food & Dining',
        '🚗 Transport',
        '🛒 Shopping',
        '💡 Bills & Utilities',
        '🏥 Health',
        '🎉 Entertainment',
        '📦 Other',
    ];
}