<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where(string $string, string $string1)
 * @method static whereNull(string $string)
 */
class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'parent_id',
        'image_name',
        'filter_name',
    ];

    /**
     * @return HasMany
     */
    public function subcategories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->whereNotNull('parent_id');
    }

    /**
     * @return HasMany
     */
    public function categories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->whereNull('parent_id');
    }
}
