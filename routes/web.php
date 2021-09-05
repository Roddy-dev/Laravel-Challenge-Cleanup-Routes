<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\AdminDashboardController;

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

Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

Route::group(['prefix' =>  'book', 'as' => 'books.'], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/create', [BookController::class, 'create'])->name('create');
        Route::post('store', [BookController::class, 'store'])->name('store');
        Route::get('{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
        Route::post('{book}/report', [BookReportController::class, 'store'])->name('report.store');
    });
    Route::get('{book:slug}', [BookController::class, 'show'])->name('show');
});

Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
    Route::group(['prefix' => 'books', 'as' => 'user.books.'], function () {
        Route::get('/', [BookController::class, 'index'])->name('index');
        Route::get('{book:slug}/edit', [BookController::class, 'edit'])->name('edit');
        Route::put('{book:slug}', [BookController::class, 'update'])->name('update');
        Route::delete('{book}', [BookController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', [OrderController::class, 'index'])->name('user.orders.index');
    });
    
    Route::group(['prefix' => 'settings', 'as' => 'user.'], function () {
        Route::get('/', [UserSettingsController::class, 'index'])->name('settings');
        Route::post('{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
    });
});


Route::group(['prefix' =>  'admin', 'middleware' => 'isAdmin'], function () {
    Route::get('/', AdminDashboardController::class)->name('admin.index');
    
    Route::group(['prefix' => 'books', 'as' => 'admin.books.'], function () {
        Route::get('/', [AdminBookController::class, 'index'])->name('index');
        Route::get('create', [AdminBookController::class, 'create'])->name('create');
        Route::post('/', [AdminBookController::class, 'store'])->name('store');
        Route::get('{book}/edit', [AdminBookController::class, 'edit'])->name('edit');
        Route::put('{book}', [AdminBookController::class, 'update'])->name('update');
        Route::delete('{book}', [AdminBookController::class, 'destroy'])->name('destroy');
        Route::put('approve/{book}', [AdminBookController::class, 'approveBook'])->name('approve');
    });

    Route::group(['prefix' => 'users', 'as' => 'admin.users.'], function () {
        Route::get('/', [AdminUsersController::class, 'index'])->name('index');
        Route::get('{user}/edit', [AdminUsersController::class, 'edit'])->name('edit');
        Route::put('{user}', [AdminUsersController::class, 'update'])->name('update');
        Route::delete('{user}', [AdminUsersController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__ . '/auth.php';
