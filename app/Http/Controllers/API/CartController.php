<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        if (auth('sanctum')->check()) {

            //Here, you can change user-id to: user-name, user-regno, etc......
            $user_id = auth('sanctum')->user()->id;
            //.....

            $product_id = $request->product_id;
            $product_qty = $request->product_qty;

            $productCheck = Product::where('id',$product_id)->first();

            if($productCheck) {
                //if(cart::where('column name in cart-table', column value- in specific table)).......format of code below 
                if(Cart::where('product_id', $product_id)->where('user_id', $user_id)->exists()) {

                    return response()->json([
                        'status' => 409,
                        'message' => $productCheck->name.' is Already Added To Cart',
                    ]);
                } else {
                    $cartItem =  new Cart;
                    $cartItem->user_id = $user_id;
                    $cartItem->product_id = $product_id;
                    $cartItem->product_qty = $product_qty;
                    $cartItem->save();

                    return response()->json([
                        'status' => 201,
                        'message' => 'Added To Cart'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Product Not Found'
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to Add Cart'
            ]);
        }
    }
}
