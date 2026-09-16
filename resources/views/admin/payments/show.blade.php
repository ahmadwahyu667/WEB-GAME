@extends('layouts.admin')

@section('title', 'Payment Details - ' . $payment->reference_id)

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.payments.index') }}" class="btn-admin btn-admin-secondary">
        <i data-lucide="arrow-left"></i> Back to Payments
    </a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Payment Info</h3>
        </div>
        <div style="padding: 15px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee;"><strong>Status</strong></td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee; text-align: right;">
                        <span class="badge badge-{{ strtolower($payment->status) }}">{{ ucfirst($payment->status) }}</span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee;"><strong>Amount</strong></td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee; text-align: right;">{{ 'Rp ' . number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee;"><strong>Method</strong></td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee; text-align: right;">{{ $payment->payment_method }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee;"><strong>Provider</strong></td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee; text-align: right;">{{ ucfirst($payment->provider) }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee;"><strong>Reference ID</strong></td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee; text-align: right;">{{ $payment->reference_id }}</td>
                </tr>
                @if($payment->provider_reference)
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee;"><strong>Provider Ref</strong></td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee; text-align: right;">{{ $payment->provider_reference }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee;"><strong>Order #</strong></td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee; text-align: right;">
                        @if($payment->order)
                            <a href="{{ route('admin.orders.show', $payment->order->id) }}">{{ $payment->order->order_number }}</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0;"><strong>Created At</strong></td>
                    <td style="padding: 10px 0; text-align: right;">{{ $payment->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Raw Response (JSON)</h3>
        </div>
        <div style="padding: 15px;">
            <pre style="background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; font-size: 0.9em; max-height: 400px; overflow-y: auto; border: 1px solid #e9ecef;">
{{ $payment->raw_response ? json_encode($payment->raw_response, JSON_PRETTY_PRINT) : 'No raw response available.' }}
            </pre>
        </div>
    </div>
</div>

<div class="admin-card" style="margin-top: 20px;">
    <div class="admin-card-header">
        <h3 class="admin-card-title">Payment Logs</h3>
    </div>
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payment->logs ?? [] as $log)
                    <tr>
                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td><span class="badge badge-{{ strtolower($log->status) }}">{{ ucfirst($log->status) }}</span></td>
                        <td>{{ $log->note }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 15px;">No logs available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
