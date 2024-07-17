<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1)
 * @method static whereNull(string $string)
 */
class Category extends Model
{
    use HasFactory;

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->whereNotNull('parent_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_id')->whereNull('parent_id');
    }
}
