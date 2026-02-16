<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'client_id',
        'service_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'price_cents_snapshot',
        'notes',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'status' => AppointmentStatus::class,
            'cancelled_at' => 'datetime',
            'price_cents_snapshot' => 'integer',
        ];
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class)->withTrashed();
    }

    public function occupiesSlot(): bool
    {
        return in_array($this->status, AppointmentStatus::occupying());
    }

    public function formattedPrice(): string
    {
        return 'R$ ' . number_format($this->price_cents_snapshot / 100, 2, ',', '.');
    }
}
