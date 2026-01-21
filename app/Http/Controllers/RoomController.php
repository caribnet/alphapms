<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return Room::with('roomType')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'room_number' => 'required|unique:rooms,room_number',
            'status' => 'required|in:available,occupied,maintenance,dirty',
        ]);

        return Room::create($validated);
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'status' => 'required|in:available,occupied,maintenance,dirty',
        ]);

        $room->update($validated);
        return $room;
    }

    public function roomTypes()
    {
        return RoomType::all();
    }

    public function storeRoomType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'base_rate' => 'required|numeric',
            'capacity' => 'required|integer',
        ]);

        return RoomType::create($validated);
    }
}
