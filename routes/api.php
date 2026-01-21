<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\WholesalerController;
use App\Http\Controllers\AccountingController;
use Illuminate\Support\Facades\Route;

// Rooms
Route::get('/rooms', [RoomController::class, 'index']);
Route::post('/rooms', [RoomController::class, 'store']);
Route::patch('/rooms/{room}', [RoomController::class, 'update']);
Route::get('/room-types', [RoomController::class, 'roomTypes']);
Route::post('/room-types', [RoomController::class, 'storeRoomType']);

// Bookings
Route::get('/bookings', [BookingController::class, 'index']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::post('/bookings/{booking}/assign-room', [BookingController::class, 'assignRoom']);
Route::post('/bookings/{booking}/check-in', [BookingController::class, 'checkIn']);
Route::post('/bookings/{booking}/check-out', [BookingController::class, 'checkOut']);

// Wholesalers
Route::get('/wholesalers', [WholesalerController::class, 'index']);
Route::post('/wholesalers', [WholesalerController::class, 'store']);
Route::get('/wholesalers/{wholesaler}', [WholesalerController::class, 'show']);

// Accounting
Route::get('/invoices', [AccountingController::class, 'index']);
Route::post('/bookings/{booking}/generate-invoice', [AccountingController::class, 'generateInvoice']);
Route::post('/invoices/{invoice}/payments', [AccountingController::class, 'recordPayment']);
