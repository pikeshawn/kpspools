<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceStopController;

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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/customers',
    [CustomerController::class, 'index'])
    ->name('customers');

Route::middleware(['auth:sanctum', 'verified'])->get('/service_stops/{customer}',
    [ServiceStopController::class, 'index'])
    ->name('service_stops');

Route::middleware(['auth:sanctum', 'verified'])->get('/service_stops/create/{customer}',
    [ServiceStopController::class, 'create'])
    ->name('service_stops.create');

Route::middleware(['auth:sanctum', 'verified'])->post('/service_stops/store',
    [ServiceStopController::class, 'store'])
    ->name('service_stops.store');

Route::middleware(['auth:sanctum', 'verified'])
    ->get('customers/{customer}/edit',
    [CustomerController::class, 'edit'])
    ->name('customers.edit');


