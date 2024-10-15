<?php

namespace App\Models;
use App\Models\Unit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = "categories";
    protected $primaryKey = "id";
    protected $fillable = ['name'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function menus() {
        return $this->hasMany(Menu::class);
    }
}