<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sku_code',
        'name',
        'description',
        'price',
        'stock_quantity',
        'low_stock_threshold',
        'image_path',
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_category');
    }
}
