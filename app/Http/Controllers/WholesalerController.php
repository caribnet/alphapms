<?php

namespace App\Http\Controllers;

use App\Models\Wholesaler;
use App\Models\WholesalerRate;
use Illuminate\Http\Request;

class WholesalerController extends Controller
{
    public function index()
    {
        return Wholesaler::all();
    }

    public function rates()
    {
        return WholesalerRate::with(['wholesaler', 'roomType'])->get();
    }

    public function storeRate(Request $request)
    {
        $validated = $request->validate([
            'wholesaler_id' => 'required|exists:wholesalers,id',
            'room_type_id' => 'required|exists:room_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'rate' => 'required|numeric|min:0',
        ]);

        return WholesalerRate::create($validated);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'contact_person' => 'nullable|string',
            'email' => 'nullable|email',
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        return Wholesaler::create($validated);
    }

    public function show(Wholesaler $wholesaler)
    {
        return $wholesaler->load('bookings.guest');
    }
}
