<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'title',
        'image',
        'product_category_id',
        'price',
        'brand',
        'weight',
        'description',
        'tag_number',
        'discount',
        'tags',
        'tex',
        'stock',
    ];
    public function product_category()
    {
        return $this->belongsTo(ProductCategory::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class,'product_tags');
    }
}
