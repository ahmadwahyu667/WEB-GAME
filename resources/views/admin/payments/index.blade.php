@extends('layouts.admin')

@section('title', 'Manage Payments')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Payments List</h2>
    </div>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Reference ID</th>
                    <th>Provider</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>
                            @if($payment->order)
                                <a href="{{ route('admin.orders.show', $payment->order->id) }}">
                                    {{ $payment->order->order_number }}
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $payment->reference_id }}</td>
                        <td>{{ ucfirst($payment->provider) }}</td>
                        <td>{{ $payment->payment_method }}</td>
                        <td>{{ 'Rp ' . number_format($payment->amount, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge badge-{{ strtolower($payment->status) }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn-admin btn-admin-info btn-admin-sm">
                                <i data-lucide="eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2rem;">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $payments->links() }}
    </div>
</div>
@endsection
