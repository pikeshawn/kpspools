<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceStopController;
use App\Http\Controllers\SummaryController;
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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/customers',
    [CustomerController::class, 'index'])
    ->name('customers');

Route::middleware(['auth:sanctum', 'verified'])->get('/customers/show/{customer}',
    [CustomerController::class, 'show'])
    ->name('customers.show');

Route::middleware(['auth:sanctum', 'verified'])->get('/customers/create',
    [CustomerController::class, 'create'])
    ->name('customers.create');

Route::middleware(['auth:sanctum', 'verified'])->post('/customers/store',
    [CustomerController::class, 'store'])
    ->name('customers.store');

Route::middleware(['auth:sanctum', 'verified'])->get('/service_stops/{customer}',
    [ServiceStopController::class, 'index'])
    ->name('service_stops');

Route::middleware(['auth:sanctum', 'verified'])->get('/summary/{customer}',
    [SummaryController::class, 'customerSummary'])
    ->name('summary');

Route::middleware(['auth:sanctum', 'verified'])->get('/service_stops/create/{customer}',
    [ServiceStopController::class, 'create'])
    ->name('service_stops.create');

Route::middleware(['auth:sanctum', 'verified'])->get('/service_stops/notes/{customer}',
    [ServiceStopController::class, 'notes'])
    ->name('service_stops.notes');

Route::middleware(['auth:sanctum', 'verified'])->post('/service_stops/store',
    [ServiceStopController::class, 'store'])
    ->name('service_stops.store');

Route::middleware(['auth:sanctum', 'verified'])->post('/service_stops/sendText',
    [ServiceStopController::class, 'sendText'])
    ->name('service_stops.sendText');

Route::middleware(['auth:sanctum', 'verified'])->get('/ss/{serviceStop}',
    [ServiceStopController::class, 'edit'])
    ->name('service_stops.edit');

Route::middleware(['auth:sanctum', 'verified'])->patch('/service_stops/',
    [ServiceStopController::class, 'update'])
    ->name('service_stops.update');

Route::middleware(['auth:sanctum', 'verified'])->delete('/service_stops/{serviceStop}',
    [ServiceStopController::class, 'destroy'])
    ->name('service_stops.destroy');

Route::middleware(['auth:sanctum', 'verified'])->get('/general/notes/{customer}',
    [CustomerController::class, 'notes'])
    ->name('general.notes');

Route::middleware(['auth:sanctum', 'verified'])->get('/general/new_note/{customer}',
    [CustomerController::class, 'new_note'])
    ->name('general.new_note');

Route::middleware(['auth:sanctum', 'verified'])->post('/general/store',
    [CustomerController::class, 'store_note'])
    ->name('general.store');

Route::middleware(['auth:sanctum', 'verified'])->delete('/general/{generalNote}',
    [CustomerController::class, 'destroy'])
    ->name('general.destroy');

Route::middleware(['auth:sanctum', 'verified'])->get('/general/{generalNote}',
    [CustomerController::class, 'showNote'])
    ->name('general.showNote');

Route::middleware(['auth:sanctum', 'verified'])->patch('/general/',
    [CustomerController::class, 'update'])
    ->name('general.update');

Route::middleware(['auth:sanctum', 'verified'])
    ->get('customers/{customer}/edit',
        [CustomerController::class, 'edit'])
    ->name('customers.edit');


Route::middleware(['auth:sanctum', 'verified'])->get('/task/create/{customer}',
    [TaskController::class, 'create'])
    ->name('task.create');

Route::middleware(['auth:sanctum', 'verified'])->post('/task/store',
    [TaskController::class, 'store'])
    ->name('task.store');
