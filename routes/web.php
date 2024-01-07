<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceStopController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\VonageWebhookController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\ProspectiveController;
use App\Http\Controllers\PasswordlessController;
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

Route::middleware(['auth:sanctum', 'verified', 'serviceman'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified', 'prospective'])->get('/prospective',
    [ProspectiveController::class, 'index'])->name('prospective');

Route::middleware(['auth:sanctum', 'verified', 'prospective'])->post('/prospectiveTimes',
    [ProspectiveController::class, 'times'])->name('prospectiveTimes');

Route::middleware(['auth:sanctum', 'verified', 'prospective'])->post('/prospective/requested',
    [ProspectiveController::class, 'requested'])->name('prospective.requested');

Route::middleware(['auth:sanctum', 'verified', 'prospective'])->get('/prospectiveTimes',
    [ProspectiveController::class, 'index'])->name('prospective');

Route::middleware(['auth:sanctum', 'verified', 'serviceman'])->group(function () {

    Route::get('/prospective/customers',
        [ProspectiveController::class, 'customers'])->name('prospective.customers');

    Route::get('/customers',
        [CustomerController::class, 'index'])
        ->name('customers');

    Route::get('/registrationLink',
        [ProspectiveController::class, 'registrationLink'])
        ->name('prospective.registrationLink');

    Route::get('/customers/show/{address}',
        [CustomerController::class, 'show'])
        ->name('customers.show');

    Route::get('/customers/create/{id}',
        [CustomerController::class, 'create'])
        ->name('customers.create');

    Route::post('/customers/store',
        [CustomerController::class, 'store'])
        ->name('customers.store');

    Route::get('/service_stops/{address}',
        [ServiceStopController::class, 'index'])
        ->name('service_stops');

    Route::get('/summary/{customer}',
        [SummaryController::class, 'customerSummary'])
        ->name('summary');

    Route::get('/service_stops/create/{address}',
        [ServiceStopController::class, 'create'])
        ->name('service_stops.create');

    Route::get('/service_stops/notes/{customer}',
        [ServiceStopController::class, 'notes'])
        ->name('service_stops.notes');

    Route::post('/service_stops/store',
        [ServiceStopController::class, 'store'])
        ->name('service_stops.store');

    Route::post('/service_stops/sendText',
        [ServiceStopController::class, 'sendText'])
        ->name('service_stops.sendText');

    Route::get('/ss/{serviceStop}',
        [ServiceStopController::class, 'edit'])
        ->name('service_stops.edit');

    Route::patch('/service_stops/',
        [ServiceStopController::class, 'update'])
        ->name('service_stops.update');

    Route::delete('/service_stops/{serviceStop}',
        [ServiceStopController::class, 'destroy'])
        ->name('service_stops.destroy');

    Route::get('/general/notes/{customer}',
        [CustomerController::class, 'notes'])
        ->name('general.notes');

    Route::get('/general/new_note/{customer}',
        [CustomerController::class, 'new_note'])
        ->name('general.new_note');

    Route::post('/general/store',
        [CustomerController::class, 'store_note'])
        ->name('general.store');

    Route::delete('/general/{generalNote}',
        [CustomerController::class, 'destroy'])
        ->name('general.destroy');

    Route::get('/general/{generalNote}',
        [CustomerController::class, 'showNote'])
        ->name('general.showNote');

    Route::patch('/general/',
        [CustomerController::class, 'update'])
        ->name('general.update');

    Route::middleware(['auth:sanctum', 'verified'])
        ->get('customers/{customer}/edit',
            [CustomerController::class, 'edit'])
        ->name('customers.edit');

// Tasks
    Route::get('/task/create/{address}',
        [TaskController::class, 'create'])
        ->name('task.create');

    Route::post('/task/store',
        [TaskController::class, 'store'])
        ->name('task.store');

    Route::post('/task/pickedUp',
        [TaskController::class, 'pickedUp'])
        ->name('task.pickedUp');

    Route::post('/task/remove',
        [TaskController::class, 'remove'])
        ->name('task.remove');

    Route::post('/task/completed',
        [TaskController::class, 'completed'])
        ->name('task.completed');

    Route::post('/task/requestApproval',
        [TaskController::class, 'requestApproval'])
        ->name('task.requestApproval');

    Route::post('/task/approveItem',
        [TaskController::class, 'approveItem'])
        ->name('task.approveItem');

    Route::post('/task/assignServiceman',
        [TaskController::class, 'assignServiceman'])
        ->name('task.assignServiceman');

    Route::post('/task/updatePrice',
        [TaskController::class, 'updatePrice'])
        ->name('task.updatePrice');

    Route::post('/task/changeStatus',
        [TaskController::class, 'changeStatus'])
        ->name('task.changeStatus');

    Route::post('/task/changeDescription',
        [TaskController::class, 'changeDescription'])
        ->name('task.changeDescription');

    Route::post('/task/deleteItem',
        [TaskController::class, 'deleteItem'])
        ->name('task.deleteItem');

    Route::post('/task/notCompleted',
        [TaskController::class, 'notCompleted'])
        ->name('task.notCompleted');

    Route::get('/tasks',
        [TaskController::class, 'index'])
        ->name('tasks');

    Route::get('/tasksNeedsApproval',
        [TaskController::class, 'tasksNeedsApproval'])
        ->name('tasksNeedsApproval');

    Route::get('/customerTasks',
        [TaskController::class, 'customerTasks'])
        ->name('task.customerTasks');

    Route::get('/repairsAndPartsList',
        [TaskController::class, 'repairsAndPartsList'])
        ->name('repairsAndPartsList');

    Route::get('/route',
        [RouteController::class, 'index'])
        ->name('route');

    Route::post('/route/store',
        [RouteController::class, 'store'])
        ->name('store');


    Route::post('/registerLink',
        [RegisterController::class, 'registerLink'])
        ->name('registerLink');
});

Route::get('/delivery',
    [VonageWebhookController::class, 'delivery'])
    ->name('delivery');

Route::get('/inbound',
    [VonageWebhookController::class, 'inbound'])
    ->name('inbound');

Route::post('/chat/response',
    [ChatController::class, 'chatResponse'])
    ->name('chat.response');

Route::get('/chat',
    [ChatController::class, 'chat'])
    ->name('chat');

Route::get('/login/onboard/{token}',
    [PasswordlessController::class, 'onboard'])
    ->name('onboard');
