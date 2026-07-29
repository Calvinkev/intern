<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'delivery_personnel_id',
        'status',
        'pickup_address',
        'delivery_address',
        'pickup_latitude',
        'pickup_longitude',
        'delivery_latitude',
        'delivery_longitude',
        'assigned_at',
        'accepted_at',
        'picked_up_at',
        'delivered_at',
        'distance',
        'delivery_fee',
        'notes',
    ];

    protected $casts = [
        'pickup_latitude' => 'decimal:8',
        'pickup_longitude' => 'decimal:8',
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
        'distance' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'assigned_at' => 'datetime',
        'accepted_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryPersonnel()
    {
        return $this->belongsTo(User::class, 'delivery_personnel_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopePickedUp($query)
    {
        return $query->where('status', 'picked_up');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function canBeAccepted(): bool
    {
        return $this->status === 'assigned';
    }

    public function canBePickedUp(): bool
    {
        return $this->status === 'accepted';
    }

    public function canBeDelivered(): bool
    {
        return $this->status === 'picked_up';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }
}
