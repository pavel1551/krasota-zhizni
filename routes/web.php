<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEquipmentController;
use App\Http\Controllers\Admin\AdminHallController;
use App\Http\Controllers\Admin\AdminScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'brand' => 'Красота-Жизни',
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/halls', [HallController::class, 'index'])->name('halls.index');
Route::get('/halls/{hall}', [HallController::class, 'show'])->name('halls.show');

Route::middleware(['auth'])->group(function () {

    Route::get('/bookings/history', [BookingController::class, 'history'])->name('bookings.history');

    Route::get('/halls/{hall}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');

    Route::get('/bookings/{booking}/pay', [BookingController::class, 'payForm'])->name('bookings.pay.form');
    Route::post('/bookings/{booking}/pay', [BookingController::class, 'pay'])->name('bookings.pay');

    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{booking}/refund', [BookingController::class, 'refund'])->name('bookings.refund');

    // API для формы бронирования (занятость + реквизит)
    Route::get('/api/halls/{hall}/availability', [BookingController::class, 'apiHallAvailability'])
        ->name('api.halls.availability');

    Route::get('/api/halls/{hall}/equipment-availability', [BookingController::class, 'apiEquipmentAvailability'])
        ->name('api.halls.equipment_availability');
});

/*
|--------------------------------------------------------------------------
| Admin panel
|--------------------------------------------------------------------------
| prefix: /admin
| middleware: auth + admin
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // CRUD залов и реквизита
        Route::resource('equipment', AdminEquipmentController::class)->except(['show']);
        Route::resource('halls', AdminHallController::class)->except(['show']);

        // Расписание (занятость)
        Route::get('/schedule', [AdminScheduleController::class, 'index'])->name('schedule');

        // JSON API для расписания
        Route::get('/api/halls/{hall}/busy', [AdminScheduleController::class, 'apiBusy'])->name('api.halls.busy');
    });

require __DIR__ . '/auth.php';
