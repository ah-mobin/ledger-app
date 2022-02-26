<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register'=>false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {

    Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');

    Route::resource('customers', \App\Http\Controllers\CustomerController::class);
    Route::get('customers/{id}/ledger', [\App\Http\Controllers\LedgerController::class, 'index'])->name('customers.ledger.index');
    Route::post('customers/{id}/ledger', [\App\Http\Controllers\LedgerController::class, 'store'])->name('customers.ledger.store');

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
