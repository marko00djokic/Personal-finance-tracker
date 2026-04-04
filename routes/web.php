<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PlannedTransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    $writesThrottle = ['store' => 'throttle:60,1', 'update' => 'throttle:60,1', 'destroy' => 'throttle:60,1'];

    Route::resource('categories', CategoryController::class)
        ->except(['show'])
        ->middleware($writesThrottle);

    Route::resource('transactions', TransactionController::class)
        ->except(['show'])
        ->middleware($writesThrottle);

    Route::resource('planned-transactions', PlannedTransactionController::class)
        ->except(['show'])
        ->middleware($writesThrottle);

    Route::post('planned-transactions/{plannedTransaction}/confirm', [PlannedTransactionController::class, 'confirm'])
        ->middleware('throttle:60,1')
        ->name('planned-transactions.confirm');
    Route::post('planned-transactions/{plannedTransaction}/skip', [PlannedTransactionController::class, 'skip'])
        ->middleware('throttle:60,1')
        ->name('planned-transactions.skip');

    // Export
    Route::get('/export/csv', [ExportController::class, 'exportCsv'])->name('export.csv');
    Route::get('/export/pdf', [ExportController::class, 'exportPdf'])->name('export.pdf');
});

require __DIR__.'/auth.php';
