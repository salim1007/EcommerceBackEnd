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
            $product_name = $request->product_name;

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
                    $cartItem->product_name = $product_name;
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
    public function viewCart(Request $request){
        if(auth('sanctum')->check()){
            $user_id = auth('sanctum')->user()->id;
            $cartItems = Cart::where('user_id', $user_id)->get();

            return response()->json([
                'status'=>200,
                'cart'=>$cartItems,
            ]);

        }else{
            return response()->json([
                'status'=>401,
                'message'=>'Login to View to Cart Data',
            ]);
        }
    }
    public function updateQty($cart_id, $scope){
        if(auth('sanctum')->check()){
            $user_id = auth('sanctum')->user()->id;
            $cartItem = Cart::where('id',$cart_id)->where('user_id', $user_id)->first();
            if($scope == "inc"){
                $cartItem->product_id += 1;
            }else if($scope == "dec"){
                $cartItem->product_id -= 1;
            }

            $cartItem->update();

            return response()->json([
                'status'=>200,
                'message'=>'Quantity Updated',
            ]);

        }else{
            return response()->json([
                'status'=>401,
                'message'=>'Login to Continue',
            ]);
        }
    }
    public function delete($cart_id){
        if(auth('sanctum')->check()){
            $user_id = auth('sanctum')->user()->id;
            $cartItem = Cart::where('id',$cart_id)->where('user_id', $user_id)->first();
            if($cartItem){
                $cartItem->delete();
                return response()->json([
                    'status'=>200,
                    'message'=>'Cart Item Removed Successfully',
                ]);

            }else{
                return response()->json([
                    'status'=>404,
                    'message'=>'Cart Item Not Found',
                ]);
            }


        }else{
            return response()->json([
                'status'=>401,
                'message'=>'Login to Continue',
            ]);
        }
    }
}
