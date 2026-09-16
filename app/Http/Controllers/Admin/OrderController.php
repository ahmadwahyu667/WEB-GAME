<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product', 'payment']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('order_number', 'like', '%'.$request->search.'%');
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payment', 'payments']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,processing,completed,failed,cancelled',
        ]);

        try {
            $oldStatus = $order->status;
            $newStatus = $request->status;

            // Simple validation rule: cannot change from completed or cancelled
            if (in_array($oldStatus, ['completed', 'cancelled'])) {
                return back()->with('error', 'Tidak dapat mengubah status pesanan yang sudah selesai atau dibatalkan.');
            }

            $order->status = $newStatus;
            $order->save();

            AdminAuditLog::log('update_order_status', "Mengubah status pesanan {$order->order_number} dari {$oldStatus} ke {$newStatus}");

            return back()->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
