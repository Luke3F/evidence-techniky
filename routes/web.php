<?php

use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ServiceRecordController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/equipment');

Route::get('equipment/{equipment}/export', [EquipmentController::class, 'export'])->name('equipment.export');
Route::resource('equipment', EquipmentController::class);

Route::post('equipment/{equipment}/service-records', [ServiceRecordController::class, 'store'])
    ->name('service-records.store');
Route::delete('equipment/{equipment}/service-records/{record}', [ServiceRecordController::class, 'destroy'])
    ->name('service-records.destroy');
