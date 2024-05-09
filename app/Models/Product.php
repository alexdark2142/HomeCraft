<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'img',
        'category_id',
        'subcategory_id',
        'price',
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
}
