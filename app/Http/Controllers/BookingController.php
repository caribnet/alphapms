<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return Booking::with(['guest', 'room', 'roomType'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'room_type_id' => 'required|exists:room_types,id',
            'wholesaler_id' => 'nullable|exists:wholesalers,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'total_price' => 'required|numeric',
        ]);

        return Booking::create($validated);
    }

    public function assignRoom(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);

        $room = Room::find($validated['room_id']);
        
        if ($room->room_type_id !== $booking->room_type_id) {
            return response()->json(['error' => 'Room type mismatch'], 422);
        }

        $booking->update(['room_id' => $room->id, 'status' => 'confirmed']);
        
        return $booking;
    }

    public function checkIn(Booking $booking)
    {
        if (!$booking->room_id) {
            return response()->json(['error' => 'Room must be assigned before check-in'], 422);
        }

        $booking->update(['status' => 'checked_in']);
        $booking->room->update(['status' => 'occupied']);

        return $booking;
    }

    public function checkOut(Booking $booking)
    {
        $booking->update(['status' => 'checked_out']);
        $booking->room->update(['status' => 'dirty']);

        return $booking;
    }
}
