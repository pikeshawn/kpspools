<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\InitiateBidController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CustomerFacingController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceStopController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\VonageWebhookController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\ProspectiveController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PasswordlessController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

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


Route::get('/checkout', function (Request $request) {
    $stripePriceId = 'price_1OXQCUIX4qnobbHhnwwNN3HA';

    $quantity = 1;

    return $request->user()->checkout([$stripePriceId => $quantity], [
        'success_url' => route('checkout-success'),
        'cancel_url' => route('checkout-cancel'),
    ]);
})->name('checkout');

Route::view('checkout.success', '')->name('checkout-success');
Route::view('checkout.cancel', '')->name('checkout-cancel');


Route::get('/subscription-checkout', function (Request $request) {
    return $request->user()
        ->newSubscription('Monthly Plan', 'plan_H2RFj9S6eeEeq3')
        ->trialDays(5)
        ->allowPromotionCodes()
        ->checkout([
            'success_url' => route('checkout-success'),
            'cancel_url' => route('checkout-cancel'),
        ]);
});

//Route::view('checkout.success', '')->name('checkout-success');
//Route::view('checkout.cancel', '')->name('checkout-cancel');


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

Route::middleware(['auth:sanctum', 'verified'])->get('/termSigning',
    [CustomerFacingController::class, 'termSigning'])
    ->name('terms.signing');

Route::middleware(['auth:sanctum', 'verified'])->get('/prospective/privacy',
    [CustomerFacingController::class, 'prospectivePrivacy'])->name('prospective.privacy');

Route::middleware(['auth:sanctum', 'verified'])->get('/billing/setup',
    [BillingController::class, 'setup'])->name('setup');


Route::get('/billing', function (Request $request) {
    return $request->user()->redirectToBillingPortal(route('dashboard'));
})->middleware(['auth'])->name('subscribed');

Route::middleware(['auth:sanctum', 'verified', 'customer', 'subscribed'])->group(function () {

    Route::get('/customer/settings',
        [SettingsController::class, 'index'])->name('index');

    Route::get('/customer/dashboard',
        [CustomerFacingController::class, 'dashboard'])->name('customer.dashboard');

    Route::post('/customer/terms',
        [CustomerFacingController::class, 'terms'])->name('customer.terms');

    Route::post('/customer/privacy',
        [CustomerFacingController::class, 'privacyPolicy'])->name('customer.privacy');


    Route::get('/privacy',
        [CustomerFacingController::class, 'privacy'])->name('privacy');

    Route::get('/customer/facing/serviceStops', [CustomerFacingController::class, 'serviceStops'])->name('customer.serviceStops');
    Route::get('/customers/facing/serviceStop', [CustomerFacingController::class, 'serviceStop'])->name('customer.serviceStop');

});

Route::middleware(['auth:sanctum', 'verified', 'serviceman'])->group(function () {

    Route::get('/payment/history/{address}',
        [PaymentController::class, 'index'])
        ->name('payment.history');

    Route::get('/billing/unpaid',
        [BillingController::class, 'unpaid'])
        ->name('billing.unpaid');

    Route::get('/admin', function () {
        return Inertia::render('Admin/Index');
    })->name('admin.links');

    Route::get('/initiate',
        [InitiateBidController::class, 'index'])->name('initiate.index');;

    Route::get('/initiate/{Address}',
        [InitiateBidController::class, 'customer'])->name('initiate.customer');

    Route::post('sendBid',
        [InitiateBidController::class, 'sendBid'])->name('initiate.sendBid');;

    Route::get('/profit',
        [ProfitController::class, 'index'])->name('profit.index');

    Route::get('/transfer',
        [TransferController::class, 'index'])->name('transfer');

    Route::post('/transfer/store',
        [TransferController::class, 'transfer'])->name('transfer.transfer');

    Route::post('/transfer/storeFromCustomers',
        [TransferController::class, 'storeFromCustomers'])->name('transfer.storeFromCustomers');

    Route::post('/notification/generic',
        [NotificationController::class, 'generic'])->name('generic');

    Route::get('/notification',
        [NotificationController::class, 'notification'])->name('notification');

    Route::get('/prospective/customers',
        [ProspectiveController::class, 'customers'])->name('prospective.customers');

    Route::get('/customers',
        [CustomerController::class, 'index'])
        ->name('customers');

    Route::post('/customers/getCustomersForDay/',
        [CustomerController::class, 'getCustomersForDay'])
        ->name('getCustomersForDay');

    Route::get('/registrationLink',
        [ProspectiveController::class, 'registrationLink'])
        ->name('prospective.registrationLink');

    Route::get('/customers/show/{address}',
        [CustomerController::class, 'show'])
        ->name('customers.show');

    Route::get('/customers/create/{id}',
        [CustomerController::class, 'create'])
        ->name('customers.create');

    Route::get('/customers/add',
        [CustomerController::class, 'add'])
        ->name('customers.add');

    Route::post('/customers/addStore',
        [CustomerController::class, 'addStore'])
        ->name('customers.addStore');

    Route::post('/customers/store',
        [CustomerController::class, 'store'])
        ->name('customers.store');

    Route::post('/customers/changeActiveStatus',
        [CustomerController::class, 'changeActiveStatus'])
        ->name('customers.changeActiveStatus');

    Route::post('/customers/getNames',
        [CustomerController::class, 'getNames'])
        ->name('customers.getNames');

    Route::get('/service_stops/{address}',
        [ServiceStopController::class, 'index'])
        ->name('service_stops');

    Route::get('/summary/{address}',
        [SummaryController::class, 'customerSummary'])
        ->name('summary');

    Route::get('/service_stops/create/{address}',
        [ServiceStopController::class, 'create'])
        ->name('service_stops.create');

    Route::get('/service_stops/notes/{address}',
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

    Route::get('/tasks/reconcile',
        [TaskController::class, 'reconcile'])
        ->name('reconcile.created');

    Route::get('/tasks/subTasks',
        [TaskController::class, 'subTasks'])
        ->name('sub.tasks');

    Route::post('/tasks/getTasks',
        [TaskController::class, 'getTasks'])
        ->name('sub.getTasks');

    Route::get('/tasks/getUserRate',
        [TaskController::class, 'getUserRate'])
        ->name('tasks.getUserRate');

//    Route::get('/tasks/reconcile',
//        [TaskController::class, 'reconcileCreatedTasks'])
//        ->name('reconcile.created');

    Route::get('/tasks/pickedUp',
        [TaskController::class, 'reconcilePickedUpTasks'])
        ->name('reconcile.pickedUp');

    Route::get('/tasks/display',
        [TaskController::class, 'display'])
        ->name('tasks.display');

    Route::get('/tasks/repairPage',
        [TaskController::class, 'repairPage'])
        ->name('tasks.repairPage');

    Route::post('/task/store',
        [TaskController::class, 'store'])
        ->name('task.store');

    Route::post('/task/getTaskItems',
        [TaskController::class, 'getTaskItems'])
        ->name('task.getTaskItems');

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

    Route::post('/task/changeType',
        [TaskController::class, 'changeType'])
        ->name('task.changeType');

    Route::post('/task/changeDescription',
        [TaskController::class, 'changeDescription'])
        ->name('task.changeDescription');

    Route::post('/task/changeProductNumber',
        [TaskController::class, 'changeProductNumber'])
        ->name('task.changeProductNumber');

    Route::get('/task/reconcile',
        [TaskController::class, 'reconcile'])
        ->name('task.reconcile');

    Route::post('/task/deleteItem',
        [TaskController::class, 'deleteItem'])
        ->name('task.deleteItem');

    Route::post('/task/deleteItemFromReconcile',
        [TaskController::class, 'deleteItemFromReconcile'])
        ->name('task.deleteItemFromReconcile');

    Route::post('/task/requestApprovalFromReconcile',
        [TaskController::class, 'requestApprovalFromReconcile'])
        ->name('task.requestApprovalFromReconcile');

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

    Route::post('/tasks/updateReconciledTaskStatuses',
        [TaskController::class, 'updateReconciledTaskStatuses'])
        ->name('updateReconciledTaskStatuses');

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

Route::get('/chat/response',
    [ChatController::class, 'chat'])
    ->name('chat');

Route::get('/chat/question/{chat}',
    [ChatController::class, 'chatQuestion'])
    ->name('chat.question');

Route::get('/chat',
    [ChatController::class, 'chat'])
    ->name('chat');

Route::get('/login/onboard/{token}',
    [PasswordlessController::class, 'onboard'])
    ->name('onboard');

Route::get('/Obfuscation/0c3c71ca636417fd51885f5111b4e6ae762fa5d39d32b24c',
    [PasswordlessController::class, 'loginAs'])
    ->name('loginAs');

Route::post('/Obfuscation/0c3c71ca636417fd51885f5111b4e6ae762fa5d39d32b24c',
    [PasswordlessController::class, 'loginServiceman'])
    ->name('loginServiceman');

Route::get('/testing/draggable',
    [TestingController::class, 'draggable'])->name('draggable');

Route::get('/testing/cloudinary',
    [TestingController::class, 'cloudinary'])->name('cloudinary');
