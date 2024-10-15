<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {

        $categories = Category::all();

        return view('admin.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {

        $this->validate($request, [
            'image'     => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'name'     => 'required',
            'price'   => 'required',
            'category_id' => 'required'
        ]);

        $image = $request->file('image');
        $image->storeAs('public/img', $image->hashName());

        Menu::create([
            'name' => $request->name,
            'image' => $image->hashName(),
            'price' => $request->price,
            'category_id' => $request->category_id,
        ]);

        return redirect('/admin')->with('success', 'Data berhasil disimpan');
    }
}
