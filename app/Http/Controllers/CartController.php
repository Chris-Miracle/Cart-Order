<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Obiefy\API\Facades\API;

class CartController extends Controller
{
    public function addToCart($id)
    {
        $product = Product::findOrfail($id);

        if(!$product) {

            abort(404);

        }

        $cart = session()->get('cart');

        // if cart is empty then this the first product
        if(!$cart) {

            $cart = [
                    $id => [
                        "id" => $product->id,
                        "title" => $product->title,
                        "quantity" => 1,
                        "desc" => $product->desc,
                    ]
            ];

            session()->put('cart', $cart);

            return API::response(200,'Cart has been updated', $cart);

        }

        // if cart not empty then check if this product exist then increment quantity
        if(isset($cart[$id])) {

            $cart[$id]['quantity']++;

            session()->put('cart', $cart);

            return API::response(200,'Cart item already exist, Product Quantity updated', $cart);

        }

        // if item not exist in cart then add to cart with quantity = 1
        $cart[$id] = [
            "id" => $product->id,
            "title" => $product->title,
            "quantity" => 1,
            "desc" => $product->desc,
        ];

        session()->put('cart', $cart);

        return API::response(200,'Cart has been added', $cart);
    }
}
