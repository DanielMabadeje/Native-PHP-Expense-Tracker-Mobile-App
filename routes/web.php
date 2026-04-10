<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\AddExpense;
use App\Livewire\AppSettings;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', Dashboard::class)->name('home');
Route::get('/add', AddExpense::class)->name('add');
Route::get('/settings', AppSettings::class)->name('settings');