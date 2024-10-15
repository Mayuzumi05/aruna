<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

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
}
