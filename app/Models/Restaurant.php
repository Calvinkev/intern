<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'address',
        'phone',
        'email',
        'logo',
        'cover_image',
        'latitude',
        'longitude',
        'status',
        'opening_time',
        'closing_time',
        'delivery_fee',
        'min_order_amount',
        'estimated_delivery_time',
        'is_featured',
        'rating',
        'total_reviews',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'delivery_fee' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_featured' => 'boolean',
        'opening_time' => 'datetime:H:i:s',
        'closing_time' => 'datetime:H:i:s',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function foods()
    {
        return $this->hasMany(Food::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function deliveries()
    {
        return $this->hasManyThrough(Delivery::class, Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function isOpen(): bool
    {
        $now = now();
        $opening = $this->opening_time ? \Carbon\Carbon::parse($this->opening_time) : null;
        $closing = $this->closing_time ? \Carbon\Carbon::parse($this->closing_time) : null;

        if (!$opening || !$closing) {
            return false;
        }

        return $now->between($opening, $closing);
    }
}
