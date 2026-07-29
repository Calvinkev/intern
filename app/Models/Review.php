<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'food_id',
        'restaurant_id',
        'order_id',
        'rating',
        'comment',
        'is_approved',
        'approved_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    public function approve(): void
    {
        $this->is_approved = true;
        $this->approved_at = now();
        $this->save();
    }

    protected static function booted()
    {
        static::created(function ($review) {
            if ($review->food) {
                $avgRating = $review->food->reviews()->approved()->avg('rating') ?? 0;
                $review->food->update([
                    'rating' => round($avgRating, 2),
                    'total_reviews' => $review->food->reviews()->approved()->count(),
                ]);
            }
            if ($review->restaurant) {
                $avgRating = $review->restaurant->reviews()->approved()->avg('rating') ?? 0;
                $review->restaurant->update([
                    'rating' => round($avgRating, 2),
                    'total_reviews' => $review->restaurant->reviews()->approved()->count(),
                ]);
            }
        });
    }
}
