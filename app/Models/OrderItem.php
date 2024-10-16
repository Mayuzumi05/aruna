<?php

namespace App\Models;
use App\Models\Order;
use App\Models\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = "order_items";
    protected $primaryKey = "id";
    protected $fillable = ['order_id', 'menu_id', 'quantity', 'price', 'total_price'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Relasi ke order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
