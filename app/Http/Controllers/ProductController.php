<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('game', 'category')->where('available', true);

        if ($request->has('game_id')) {
            $query->where('game_id', $request->game_id);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        return response()->json($query->get());
    }

    public function show(Product $product)
    {
        return response()->json($product->load('game', 'category'));
    }
}
