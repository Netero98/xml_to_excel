<?php

use App\Http\Controllers\OffersController;
use App\Http\Controllers\ProfileController;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/offers', [OffersController::class, 'index'])->name(RouteServiceProvider::ROUTE_OFFERS_INDEX);
    Route::get('/offers/download/excel', [OffersController::class, 'downloadExcel'])->name(RouteServiceProvider::ROUTE_OFFERS_DOWNLOAD_EXCEL);
    Route::delete('/offers/flush', [OffersController::class, 'flush'])->name(RouteServiceProvider::ROUTE_OFFERS_FLUSH);
    Route::post('/offers/refresh', [OffersController::class, 'refresh'])->name(RouteServiceProvider::ROUTE_OFFERS_REFRESH);
});

require __DIR__.'/auth.php';
