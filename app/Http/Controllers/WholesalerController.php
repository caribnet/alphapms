<?php

namespace App\Http\Controllers;

use App\Models\Wholesaler;
use Illuminate\Http\Request;

class WholesalerController extends Controller
{
    public function index()
    {
        return Wholesaler::all();
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
