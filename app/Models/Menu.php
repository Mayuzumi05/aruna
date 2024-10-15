<?php

namespace App\Models;
use App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = "menus";
    protected $primaryKey = "id";
    protected $fillable = ['name', 'image', 'price', 'category_id'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function category() {
        return $this->belongsTo(Category::class);
    }
}