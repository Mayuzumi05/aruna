<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $cart = $request->input('cart');
        $totalPrice = 0;

        // Buat order baru
        $order = new Order();
        $order->status = 'pending'; // Set status awal sebagai pending
        $order->save();

        // Loop item di keranjang dan simpan sebagai OrderItem
        foreach ($cart as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->menu_id = $cartItem['id'];
            $orderItem->quantity = $cartItem['quantity'];
            $orderItem->price = $cartItem['price'];
            $orderItem->total_price = $cartItem['price'] * $cartItem['quantity'];
            $orderItem->save();

            $totalPrice += $orderItem->total_price;
        }

        // Update harga total di order
        $order->price = $totalPrice;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Checkout berhasil!',
            'order_id' => $order->id,
        ]);
    }
}
