<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    public const PENDING = 'pending';

    public const WAITING = 'waiting';

    public const PAID = 'paid';

    public const FAILED = 'failed';

    public const EXPIRED = 'expired';

    public const CANCELLED = 'cancelled';

    public const REFUNDED = 'refunded';

    public const STATUS_PENDING = self::PENDING;

    public const STATUS_WAITING = self::WAITING;

    public const STATUS_PAID = self::PAID;

    public const STATUS_FAILED = self::FAILED;

    public const STATUS_EXPIRED = self::EXPIRED;

    public const STATUS_CANCELLED = self::CANCELLED;

    public const STATUS_REFUNDED = self::REFUNDED;

    protected $fillable = [
        'order_id',
        'provider',
        'payment_method',
        'transaction_id',
        'reference_id',
        'amount',
        'status',
        'payment_url',
        'qr_code',
        'expired_at',
        'paid_at',
        'raw_response',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'expired_at' => 'datetime',
            'paid_at' => 'datetime',
            'raw_response' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PaymentLog::class);
    }

    public function isExpired(): bool
    {
        return $this->expired_at && $this->expired_at->isPast();
    }
}
