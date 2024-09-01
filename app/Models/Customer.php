<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $street
 * @property string $city
 * @property string $area
 * @property string $postal_code
 * @property string $country_code
 * @method static firstOrCreate(array $array, array $array1)
 */
class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'street',
        'city',
        'area',
        'postal_code',
        'country_code',
    ];

    protected $appends = ['full_address'];

    /**
     * Get the orders for the customer.
     */
    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Retrieve the full address as a single string.
     *
     * @return string
     */
    public function getFullAddressAttribute(): string
    {
        return "{$this->street}, {$this->city}, {$this->area}, {$this->postal_code}, {$this->country_code}";
    }
}
