<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // ✅ add this line
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory; 

    protected $fillable = ['name', 'description', 'price', 'stock', 'id_category'];

    protected $primaryKey = 'id_item'; 

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category', 'id_category');
    }
}
