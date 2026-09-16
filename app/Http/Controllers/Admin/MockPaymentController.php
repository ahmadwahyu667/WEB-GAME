<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Payment;
use App\Services\OrderService;
use Illuminate\Http\Request;

class MockPaymentController extends Controller
{
    public function index()
    {
        if (config('payment.mode') !== 'mock') {
            return redirect()->route('admin.dashboard')->with('error', 'Mock Payment hanya tersedia di mode mock.');
        }

        $pendingPayments = Payment::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.mock-payment.index', compact('pendingPayments'));
    }

    public function trigger(Request $request, OrderService $orderService)
    {
        if (config('payment.mode') !== 'mock') {
            return back()->with('error', 'Mock Payment hanya tersedia di mode mock.');
        }

        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'status' => 'required|in:paid,failed,expired',
        ]);

        try {
            $payment = Payment::findOrFail($request->payment_id);

            if ($payment->status !== 'pending') {
                return back()->with('error', 'Pembayaran tidak dalam status pending.');
            }

            $orderService->processWebhookPayment($payment->reference_id, $request->status);

            AdminAuditLog::log('trigger_mock_payment', "Memicu pembayaran {$payment->order->order_number} dengan status {$request->status}");

            return back()->with('success', "Pembayaran berhasil dipicu menjadi {$request->status}.");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
