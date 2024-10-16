<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use PDF;

class CashierController extends Controller
{
    public function index() {
        if (request()->ajax()) {
            $menus = Menu::all();
            return response()->json($menus);
        }
    
        $menus = Menu::all();
        $categories = Category::all();
        return view('cashier.index', compact('menus', 'categories'));
    }
    

    public function filterByCategory(Category $category) {
        if (request()->ajax()) {
            $menus = Menu::where('category_id', $category->id)->get();
            return response()->json($menus);
        }
    
        // Jika bukan request AJAX, tetap kembalikan tampilan HTML
        $menus = Menu::where('category_id', $category->id)->get();
        $categories = Category::all();
        return view('cashier.index', compact('menus', 'categories', 'category'));
    }

    public function checkout(Request $request)
    {
        try {
            $cart = $request->input('cart');

            // Validasi keranjang apakah kosong
            if (!$cart || count($cart) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kosong.',
                ], 400);
            }

            $totalPrice = 0;

            // Buat order baru
            $order = new Order();
            $order->status = 'Sudah Dibayar'; // Set status awal sebagai pending
            $order->save();

            // Loop item di keranjang dan simpan sebagai OrderItem
            foreach ($cart as $cartItem) {
                if (!isset($cartItem['id'], $cartItem['quantity'], $cartItem['price'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data item keranjang tidak lengkap.',
                    ], 400);
                }

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
            $order->total = $totalPrice;
            $order->save();

            // Generate PDF receipt
            $pdf = PDF::loadView('receipt', ['order' => $order, 'cartItems' => $cart])
              ->setPaper([0, 0, 226.77, 1000]);
            return $pdf->download('receipt-order-' . $order->id . '.pdf');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat checkout: ' . $e->getMessage(),
            ], 500);
        }
    }

}
