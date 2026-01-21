<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Guest;
use App\Models\RoomType;
use App\Models\Invoice;
use App\Models\WholesalerRate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function stats()
    {
        $today = Carbon::today()->toDateString();
        
        $arrivals = Booking::where('check_in', $today)->count();
        $availableRooms = Room::where('status', 'available')->count();
        
        return response()->json([
            'arrivals' => $arrivals,
            'available_rooms' => $availableRooms,
        ]);
    }

    public function index()
    {
        return Booking::with(['guest', 'room', 'roomType', 'invoices'])->get();
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email',
                'room_type_id' => 'required|exists:room_types,id',
                'wholesaler_id' => 'nullable',
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
            if (!empty($validated['wholesaler_id'])) {
                $specialRate = WholesalerRate::where('wholesaler_id', $validated['wholesaler_id'])
                    ->where('room_type_id', $roomType->id)
                    ->where(function($query) use ($validated) {
                        $query->where(function($q) use ($validated) {
                            $q->where('start_date', '<=', $validated['check_in'])
                              ->where('end_date', '>=', $validated['check_in']);
                        })->orWhere(function($q) use ($validated) {
                            $q->where('start_date', '<=', $validated['check_out'])
                              ->where('end_date', '>=', $validated['check_out']);
                        });
                    })
                    ->first();
                
                if ($specialRate) {
                    $rate = $specialRate->rate;
                }
            }

            $totalPrice = $rate * $nights;

            // 3. Create the Booking
            $wholesalerId = !empty($validated['wholesaler_id']) ? $validated['wholesaler_id'] : null;

            $booking = Booking::create([
                'guest_id' => $guest->id,
                'room_type_id' => $validated['room_type_id'],
                'wholesaler_id' => $wholesalerId,
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
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
