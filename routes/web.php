<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\studentcontroller;
use App\Http\Controllers\Usercontroller;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ExController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\categories;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\GcashMonitoringController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\CashbackController;



Route::get('/bill', [BillController::class, 'showBill']);//bill


// ... Existing Routes ...
Route::get('/',function(){return view ('welcome');})->name('home');

// ======= PASALUBONG AUTH ROUTES =======
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Google OAuth Routes
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ======= DASHBOARDS =======
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add');
    Route::post('/cart/update/{productId}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [OrderController::class, 'showCheckout'])
        ->middleware('role:customer')
        ->name('checkout.show');
    Route::post('/checkout', [OrderController::class, 'checkout'])
        ->middleware('role:customer')
        ->name('orders.checkout');

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/inventory', [InventoryController::class, 'index'])->name('admin.inventory.index');
        Route::get('/inventory/create', [InventoryController::class, 'create'])->name('admin.inventory.create');
        Route::post('/inventory', [InventoryController::class, 'store'])->name('admin.inventory.store');
        Route::get('/inventory/{product}/edit', [InventoryController::class, 'edit'])->name('admin.inventory.edit');
        Route::put('/inventory/{product}', [InventoryController::class, 'update'])->name('admin.inventory.update');
        Route::delete('/inventory/{product}', [InventoryController::class, 'destroy'])->name('admin.inventory.destroy');

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update-status');

        Route::get('/reports/sales', [SalesReportController::class, 'index'])->name('admin.reports.sales');

        Route::get('/users', [CustomerController::class, 'index'])->name('admin.users.index');
        Route::patch('/users/{user}/role', [CustomerController::class, 'updateRole'])->name('admin.users.update-role');
    });

    Route::middleware('role:seller')->prefix('seller')->group(function () {
        Route::get('/dashboard', [SellerController::class, 'dashboard'])->name('seller.dashboard');
        Route::get('/dashboard/stats', [SellerController::class, 'dashboardStats'])->name('seller.dashboard.stats');
        Route::patch('/orders/{order}/status', [SellerController::class, 'updateOrderStatus'])->name('seller.orders.update-status');
            Route::post('/orders/{order}/status', [SellerController::class, 'updateOrderStatus']);
        Route::get('/pos', [SellerController::class, 'pos'])->name('seller.pos');
        Route::post('/pos/checkout', [SellerController::class, 'checkout'])->name('seller.pos.checkout');
        Route::get('/stock', [SellerController::class, 'stock'])->name('seller.stock');
        Route::patch('/stock/{product}', [SellerController::class, 'updateStock'])->name('seller.stock.update');
        Route::get('/receipt/latest', [SellerController::class, 'latestReceipt'])->name('seller.receipt.latest');
        Route::get('/receipt/{sale}', [SellerController::class, 'receipt'])->name('seller.receipt');
    });

    Route::middleware('role:customer')->group(function () {
        Route::get('/customer/account', [CustomerDashboardController::class, 'account'])
            ->name('customer.account');
        Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])
            ->name('customer.dashboard');
        Route::get('/customer/orders/status', [CustomerDashboardController::class, 'orderStatus'])
            ->name('customer.orders.status');
        Route::patch('/customer/account', [CustomerDashboardController::class, 'updateAccount'])
            ->name('customer.account.update');

        // Payment Routes
        Route::get('/payment/order/{order}', [PaymentController::class, 'show'])->name('payment.show');
        Route::post('/payment/order/{order}/gcash', [PaymentController::class, 'processGcash'])->name('payment.gcash');
        Route::post('/payment/verify/{transaction}', [PaymentController::class, 'verifyPayment'])->name('payment.verify');
        Route::get('/payment/history', [PaymentController::class, 'history'])->name('payment.history');

        // Delivery Routes
        Route::get('/delivery/order/{order}', [DeliveryController::class, 'show'])->name('delivery.show');
        Route::get('/delivery/history', [DeliveryController::class, 'customerHistory'])->name('delivery.history');

        // Cashback Routes
        Route::get('/cashback/balance', [CashbackController::class, 'getBalance'])->name('cashback.balance');
        Route::get('/cashback/dashboard', [CashbackController::class, 'dashboard'])->name('cashback.dashboard');
        Route::get('/cashback/history', [CashbackController::class, 'history'])->name('cashback.history');
        Route::post('/cashback/redeem', [CashbackController::class, 'redeem'])->name('cashback.redeem');

        // Google Account Linking
        Route::post('/account/link-google', [GoogleAuthController::class, 'linkGoogleAccount'])->name('account.link-google');
        Route::get('/account/link-google/callback', [GoogleAuthController::class, 'handleLinkGoogleCallback'])->name('account.link-google.callback');
        Route::post('/account/unlink-google', [GoogleAuthController::class, 'unlinkGoogleAccount'])->name('account.unlink-google');
    });

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // GCash Monitoring Routes
        Route::get('/gcash/transactions', [GcashMonitoringController::class, 'index'])->name('admin.gcash.transactions');
        Route::get('/gcash/transactions/{transaction}', [GcashMonitoringController::class, 'show'])->name('admin.gcash.show');
        Route::patch('/gcash/transactions/{transaction}/status', [GcashMonitoringController::class, 'updateStatus'])->name('admin.gcash.update-status');
        Route::get('/gcash/export', [GcashMonitoringController::class, 'export'])->name('admin.gcash.export');
        Route::get('/gcash/statistics', [GcashMonitoringController::class, 'statistics'])->name('admin.gcash.statistics');

        // Delivery Monitoring Routes
        Route::get('/delivery/all', [DeliveryController::class, 'allDeliveries'])->name('admin.delivery.all');
        Route::patch('/delivery/{order}', [DeliveryController::class, 'update'])->name('admin.delivery.update');
        Route::post('/delivery/bulk-update', [DeliveryController::class, 'bulkUpdate'])->name('admin.delivery.bulk-update');

        // Cashback Management Routes
        Route::get('/cashback', [CashbackController::class, 'adminView'])->name('admin.cashback.index');
        Route::patch('/cashback/{cashback}/adjust', [CashbackController::class, 'adminAdjust'])->name('admin.cashback.adjust');

        // Payment Routes
        Route::post('/payment/{transaction}/verify', [PaymentController::class, 'adminVerify'])->name('admin.payment.verify');
    });
});

// ======= OLD ROUTES =======
Route::get('/student', [studentcontroller::class,'index']);
Route::post('/student', [studentcontroller::class,'store']);

//Arrow Function 
//load resources/view/student.blade.php
Route::get('/student/create',function(){
    return view ('student');
}) ->name ('student.create');
//store student data (calls your controller store method)
Route :: post ('/student/save',[studentcontroller::class,'save'])
->name('student.save');


Route::get('/college', [CollegeController::class,'index']);
//route, class, function sa controller
Route::post("/add", [CollegeController::class,'addCourse']);
Route::get('/categories', [categories::class,'index']);


Route::get('/products', [ProductController::class, 'index']);
Route::get('/items', [ItemController::class, 'index']);



