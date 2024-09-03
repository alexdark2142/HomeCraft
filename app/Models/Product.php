<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static findOrFail(mixed $get)
 * @method static find(mixed $get)
 * @method static create(array $array)
 */
class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'quantity',
        'length',
        'height',
        'width',
        'depth',
        'material',
        'description',
        'category_id',
        'subcategory_id',
        'price',
        'has_colors'
    ];

    /**
     * @return HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    /**
     * @return HasOne
     */
    public function subcategory(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'subcategory_id');
    }

    public function gallery(): HasMany
    {
        return $this->hasMany(Gallery::class, 'product_id', 'id');
    }

    public function colors(): HasMany
    {
        return $this->hasMany(ProductColor::class);
    }

    // Перевірка наявності кольорів
    public function hasColors(): bool
    {
        return $this->colors()->exists();
    }
}
