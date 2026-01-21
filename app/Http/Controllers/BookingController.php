<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Guest;
use App\Models\RoomType;
use App\Models\Invoice;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return Booking::with(['guest', 'room', 'roomType', 'invoices'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'room_type_id' => 'required|exists:room_types,id',
            'wholesaler_id' => 'nullable|exists:wholesalers,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        // 1. Find or create the guest
        $guest = Guest::firstOrCreate(
            ['email' => $validated['email']],
            ['first_name' => $validated['first_name'], 'last_name' => $validated['last_name']]
        );

        // 2. Calculate Total Price
        $roomType = RoomType::find($validated['room_type_id']);
        $checkIn = new \DateTime($validated['check_in']);
        $checkOut = new \DateTime($validated['check_out']);
        $nights = $checkIn->diff($checkOut)->days;
        $nights = $nights > 0 ? $nights : 1;

        // Check for wholesaler rate
        $rate = $roomType->base_rate;
        if ($request->has('wholesaler_id') && !empty($request->wholesaler_id)) {
            $specialRate = \App\Models\WholesalerRate::where('wholesaler_id', $request->wholesaler_id)
                ->where('room_type_id', $roomType->id)
                ->where('start_date', '<=', $validated['check_in'])
                ->where('end_date', '>=', $validated['check_in'])
                ->first();
            
            if ($specialRate) {
                $rate = $specialRate->rate;
            }
        }

        $totalPrice = $rate * $nights;

        // 3. Create the Booking
        $booking = Booking::create([
            'guest_id' => $guest->id,
            'room_type_id' => $validated['room_type_id'],
            'wholesaler_id' => $validated['wholesaler_id'] ?? null,
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        // 4. Automatically generate Invoice
        Invoice::create([
            'booking_id' => $booking->id,
            'amount' => $totalPrice,
            'status' => 'unpaid'
        ]);

        return response()->json($booking->load('guest', 'roomType'), 201);
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
