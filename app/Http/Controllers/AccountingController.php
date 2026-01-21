<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function index()
    {
        return Invoice::with(['booking.guest', 'payments'])->get();
    }

    public function generateInvoice(Booking $booking)
    {
        $invoice = Invoice::create([
            'booking_id' => $booking->id,
            'amount' => $booking->total_price,
            'status' => 'unpaid'
        ]);

        return $invoice;
    }

    public function recordPayment(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        $paymentAmount = $validated['amount'] > 0 ? $validated['amount'] : $invoice->amount;

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => $paymentAmount,
            'payment_method' => $validated['payment_method'],
            'paid_at' => now()
        ]);

        $totalPaid = $invoice->payments()->sum('amount');
        
        if ($totalPaid >= $invoice->amount) {
            $invoice->update(['status' => 'paid']);
        } elseif ($totalPaid > 0) {
            $invoice->update(['status' => 'partially_paid']);
        }

        return $payment;
    }
}
