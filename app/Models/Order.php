<?php

namespace App\Models;
use App\Models\OrderItem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = "orders";
    protected $primaryKey = "id";
    protected $fillable = ['total', 'status'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
