<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'property_type_id',
        'created_by',
        'title',
        'slug',
        'description',
        'price_per_night',
        'capacity',
        'bedrooms',
        'bathrooms',
        'address',
        'city',
        'country',
        'latitude',
        'longitude',
        'status',
    ];

    // --- İlişkiler ---

    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'property_amenity');
    }

    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // --- Yardımcı Fonksiyonlar ---

    public function coverImage()
    {
        return $this->images()->where('is_cover', true)->first();
    }

    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
}