<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static where(string $string, mixed $value)
 */
class ProductColor extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'color', 'quantity'];

    // Зв'язок з моделлю Product
    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
