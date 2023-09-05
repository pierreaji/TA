<?php

use App\Events\SendLocation;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\ItemsAssignController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\ItemsInController;
use App\Http\Controllers\ItemRequestController;
use App\Http\Controllers\ItemsWarehouseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UsersController;
use App\Models\SalesTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/map', function (Request $request) {

    $track = SalesTracking::where(
        'id_user',
        $request->input('id_user'),
    )->orderBy('id', 'desc')->first();
    $tracks = SalesTracking::where('id_user', $request->input('id_user'))->orderBy('id', 'desc')->distinct()->get();

    return response()->json([
        'data' => $track,
        'tracks' => $tracks
    ]);
});

Route::get('/dashboard', function () {
    return redirect()->to(url(''));
});

Route::get('/foo', function () {
    Artisan::call('storage:link');
});

Route::get('/queue', [DashboardController::class, 'queue']);
Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy')->middleware('auth');
Route::middleware(['auth', 'sales'])->group(function () {

    Route::resource('users', UsersController::class)->middleware('admin');

    Route::resource('category', CategoryController::class);
    Route::resource('distributor', DistributorController::class);
    Route::resource('items', ItemsController::class);
    Route::prefix('items')->name('items.')->group(function () {
        Route::prefix('in')->middleware('admin')->name('in.')->group(function () {
            Route::get('index', [ItemsInController::class, 'index'])->name('index');
            Route::get('stock', [ItemsInController::class, 'stock'])->name('stock');
            Route::post('store', [ItemsInController::class, 'store'])->name('store');
            Route::post('update', [ItemsInController::class, 'update'])->name('update');
            Route::delete('delete/{id}', [ItemsInController::class, 'destroy'])->name('delete');
        });

        Route::prefix('assign')->middleware('admin')->name('assign.')->group(function () {
            Route::get('index', [ItemsAssignController::class, 'index'])->name('index');
            Route::get('stock', [ItemsAssignController::class, 'stock'])->name('stock');
            Route::post('store', [ItemsAssignController::class, 'store'])->name('store');
            Route::post('confirm', [ItemsAssignController::class, 'confirm'])->name('confirm');
            Route::delete('delete/{id}', [ItemsAssignController::class, 'destroy'])->name('delete');
        });

        Route::prefix('request')->middleware('admin')->name('request.')->group(function () {
            Route::get('index', [ItemRequestController::class, 'index'])->name('index');
            Route::get('stock', [ItemRequestController::class, 'stock'])->name('stock');
            Route::post('store', [ItemRequestController::class, 'store'])->name('store');
            Route::post('approve/{id}', [ItemRequestController::class, 'approve'])->name('approve');
            Route::delete('delete/{id}', [ItemRequestController::class, 'destroy'])->name('delete');
        });

        Route::prefix('warehouse')->name('warehouse.')->group(function () {
            Route::get('index', [ItemsWarehouseController::class, 'index'])->name('index');
            Route::post('approve/{id}', [ItemsWarehouseController::class, 'approve'])->name('approve');
            Route::post('confirm', [ItemsWarehouseController::class, 'confirm'])->name('confirm');
        });
    });

    Route::prefix('sales')->middleware('admin')->name('sales.')->group(function () {
        Route::prefix('transaction')->name('transaction.')->group(function () {
            Route::get('index', [TransactionController::class, 'index'])->name('index');
            Route::post('store', [TransactionController::class, 'store'])->name('store');
            Route::get('history', [TransactionController::class, 'history'])->name('history');
            Route::get('admin-history', [TransactionController::class, 'historyAdmin'])->name('historyAdmin');
            Route::get('sale-history', [TransactionController::class, 'historySale'])->name('historySale');
        });
        Route::prefix('tracking')->name('tracking.')->group(function () {
            Route::get('index', [TransactionController::class, 'salesTracking'])->name('index');
            Route::post('store', [TransactionController::class, 'salesTrackingStore'])->name('store');
        });
        Route::prefix('return')->name('return.')->group(function () {
            Route::get('index', [TransactionController::class, 'return'])->name('index');
            Route::get('sales', [TransactionController::class, 'return'])->name('sales');
            Route::post('store', [TransactionController::class, 'returnStore'])->name('store');
            Route::post('storeAll', [TransactionController::class, 'returnStoreAll'])->name('storeAll');
            Route::get('history', [TransactionController::class, 'returnHistory'])->name('history');
        });
    });
    Route::prefix('report')->middleware('admin')->name('report.')->group(function () {
        Route::prefix('profit')->name('profit.')->group(function () {
            Route::get('index', [ReportController::class, 'profit'])->name('index');
        });
        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('index', [ReportController::class, 'sales'])->name('index');
            Route::get('received', [ReportController::class, 'salesRequest'])->name('received');
        });
        Route::prefix('in')->name('in.')->group(function () {
            Route::get('index', [ReportController::class, 'in'])->name('index');
        });
    });
});
Route::get('users/sales/{id}', [UsersController::class, 'sales'])->middleware('auth')->name('users.sales');
Route::post('users/sales/{id}', [UsersController::class, 'salesStore'])->middleware('auth')->name('users.sales.store');
Route::post('users/sales/{id}/confirm', [UsersController::class, 'salesConfirm'])->middleware('auth')->name('users.sales.confirm');
Route::get('userssalesdoc/{id}', [UsersController::class, 'generateWord'])->middleware('auth');

require __DIR__ . '/auth.php';
