<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MidtransWebhookController;

/*
|--------------------------------------------------------------------------
| Web Routes - TirtaX
|--------------------------------------------------------------------------
*/

// ==========================================
// PUBLIC ROUTES
// ==========================================
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/track/{trackingNumber}', [ShipmentController::class, 'showTracking'])
    ->name('tracking.show');

Route::get('/tracking/search', [ShipmentController::class, 'searchTracking'])
    ->name('tracking.search');

// PWA Routes
Route::get('/install-app', function () {
    return view('pwa.install');
})->name('pwa.install');

Route::get('/offline', function () {
    return view('pwa.offline');
})->name('pwa.offline');

// ==========================================
// AUTH ROUTES
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot Password Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// ==========================================
// WEBHOOK MIDTRANS (HARUS DI LUAR MIDDLEWARE AUTH)
// ==========================================
Route::post('/midtrans-webhook', [MidtransWebhookController::class, 'handle'])
    ->name('midtrans.webhook');

// ==========================================
// PROTECTED ROUTES (HARUS LOGIN)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // Dashboard - Redirect berdasarkan role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'courier') {
            return redirect()->route('courier.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    })->name('dashboard');

    // ==========================================
    // CUSTOMER ROUTES
    // ==========================================
    Route::get('/customer/dashboard', [ShipmentController::class, 'customerDashboard'])
        ->name('customer.dashboard');

    Route::get('/my-shipments', [ShipmentController::class, 'myShipments'])
        ->name('shipments.my');

    Route::get('/my-shipments/{id}', [ShipmentController::class, 'shipmentDetail'])
        ->name('shipments.detail');

    Route::post('/my-shipments/{id}/cancel', [ShipmentController::class, 'cancelShipment'])
        ->name('shipments.cancel');

    Route::post('/my-shipments/{id}/review', [ShipmentController::class, 'submitReview'])
        ->name('shipments.review');

    Route::get('/my-shipments/{id}/invoice', [ShipmentController::class, 'downloadInvoice'])
        ->name('shipments.invoice');

    Route::get('/shipments/create', [ShipmentController::class, 'create'])
        ->name('shipments.create');
    Route::post('/shipments', [ShipmentController::class, 'store'])
        ->name('shipments.store');

    Route::get('/payment/{id}', [ShipmentController::class, 'showPayment'])
        ->name('payment.show');
    Route::get('/payment/success/{id}', [ShipmentController::class, 'paymentSuccess'])
        ->name('payment.success');

    // ==========================================
    // COURIER ROUTES
    // ==========================================
    Route::get('/courier/dashboard', [ShipmentController::class, 'courierDashboard'])
        ->name('courier.dashboard');
    Route::get('/courier/shipment/{id}', [ShipmentController::class, 'courierShipmentDetail'])
        ->name('courier.shipment.detail');
    Route::post('/courier/shipment/{id}/update', [ShipmentController::class, 'courierUpdateStatus'])
        ->name('courier.shipment.update');

    // ==========================================
    // ADMIN ROUTES
    // ==========================================

    // Dashboard Admin
    Route::get('/admin/dashboard', [ShipmentController::class, 'adminDashboard'])
        ->name('admin.dashboard');

    // Manajemen Pengiriman
    Route::get('/admin/shipments', [ShipmentController::class, 'adminShipments'])
        ->name('admin.shipments');
    Route::post('/admin/shipments/{id}/status', [ShipmentController::class, 'adminUpdateStatus'])
        ->name('admin.update_status');

    // Master Tarif (CRUD)
    Route::get('/admin/rates', [RateController::class, 'index'])->name('admin.rates.index');
    Route::get('/admin/rates/create', [RateController::class, 'create'])->name('admin.rates.create');
    Route::post('/admin/rates', [RateController::class, 'store'])->name('admin.rates.store');
    Route::get('/admin/rates/{id}/edit', [RateController::class, 'edit'])->name('admin.rates.edit');
    Route::put('/admin/rates/{id}', [RateController::class, 'update'])->name('admin.rates.update');
    Route::delete('/admin/rates/{id}', [RateController::class, 'destroy'])->name('admin.rates.destroy');

    // API Calculate Shipping (AJAX)
    Route::post('/api/calculate-shipping', [RateController::class, 'calculateShipping'])
        ->name('api.calculate_shipping');

    // Master Cabang (CRUD)
    Route::get('/admin/branches', [BranchController::class, 'index'])->name('admin.branches.index');
    Route::get('/admin/branches/create', [BranchController::class, 'create'])->name('admin.branches.create');
    Route::post('/admin/branches', [BranchController::class, 'store'])->name('admin.branches.store');
    Route::get('/admin/branches/{id}', [BranchController::class, 'show'])->name('admin.branches.show');
    Route::get('/admin/branches/{id}/edit', [BranchController::class, 'edit'])->name('admin.branches.edit');
    Route::put('/admin/branches/{id}', [BranchController::class, 'update'])->name('admin.branches.update');
    Route::delete('/admin/branches/{id}', [BranchController::class, 'destroy'])->name('admin.branches.destroy');

    // Master User Management (CRUD)
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Audit Log
    Route::get('/admin/audit-logs', [AuditLogController::class, 'index'])->name('admin.audit-logs.index');
    Route::get('/admin/audit-logs/{id}', [AuditLogController::class, 'show'])->name('admin.audit-logs.show');
    Route::post('/admin/audit-logs/cleanup', [AuditLogController::class, 'cleanup'])->name('admin.audit-logs.cleanup');

    // ==========================================
    // NOTIFICATION ROUTES
    // ==========================================
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::get('/api/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])
        ->name('api.notifications.unread-count');
    Route::get('/api/notifications/recent', [NotificationController::class, 'getRecent'])
        ->name('api.notifications.recent');
    Route::post('/api/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('api.notifications.read');
    Route::post('/api/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('api.notifications.read-all');
    Route::delete('/api/notifications/{id}', [NotificationController::class, 'destroy'])
        ->name('api.notifications.destroy');
    Route::post('/api/notifications/delete-read', [NotificationController::class, 'deleteAllRead'])
        ->name('api.notifications.delete-read');

    // ==========================================
    // LAPORAN & REKAP
    // ==========================================
    Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/admin/reports/export-excel', [ReportController::class, 'exportExcel'])->name('admin.reports.export-excel');
    Route::get('/admin/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('admin.reports.export-pdf');
});