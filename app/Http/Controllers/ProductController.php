<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()->orderBy('updated_at', 'desc')->paginate(16);
        // dd($products);
        return view('product.index', [
            'products' => $products
        ]);
    }
    public function view(Product $product)
    {
//        echo '<pre>';
//        var_dump($product);
//        echo '</pre>';
        return view('product.view', ['product' => $product] );
    }
}
