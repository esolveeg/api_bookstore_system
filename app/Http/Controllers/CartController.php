<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function getCart(Request $request)
    {
        $user = $request->user();
        $cart = DB::select('call getCart(?) ',
            [
                $user->id,
            ]
        );
        if ($cart == []) {
            return response()->json('no items on your cart');
        }

        $total = DB::select('call getTotal(?) ',
            [
                $user->id,
            ]
        );

        $subtotal = $total[0]->total;
        $discount = DB::select('SELECT d.discount FROM discount_cart d WHERE d.user_id = ?', [$user->id]);
        $branch = \App\Employee::find($request->user()->employee_id)->branch_id;
        //dd($branch);
        
        
        if($branch == 5 || $branch == 6){
            foreach($cart as $product){
                $product->price = $this->roundUpToAny($product->price + ($product->price * 25 / 100));
            }
            $subtotal = $this->roundUpToAny($subtotal + ($subtotal * 25 / 100));
        }
        $response = ['products' => $cart, 'subtotal' => $subtotal, 'discount' => 0, 'discountValue' => 0, 'total' => $subtotal];
        if (isset($discount[0])) {
            $discount = $discount[0]->discount;
            $discountVal = $subtotal * $discount / 100;
            $total = $subtotal - $discountVal;
            
            
            $response = ['products' => $cart, 'subtotal' => $subtotal, 'discount' => $discount, 'discountValue' => $discountVal, 'total' => $total];
        }
        

        return response()->json($response);
    }
    
    protected function roundUpToAny($n, $x = 5)
    {
        return (ceil($n) % $x === 0) ? ceil($n) : round(($n + $x / 2) / $x) * $x;
    }
    public function addDiscountToCart(Request $request)
    {
        $user = $request->user()->id;
        DB::delete('DELETE FROM discount_cart WHERE `user_id` = ? ', [$user]);
        DB::insert('INSERT INTO discount_cart (`user_id` , `discount`) VALUES(? , ?)', [$user, $request->discount]);
        return $this->getCart($request);

    }

    public function addToCart(Request $request)
    {

        $cart = DB::insert('call addToCart(?, ? , ?) ',
            [
                $request->product,
                $request->user()->id,
                isset($request->qty) ? $request->qty : 1,

            ]
        );

        return $this->getCart($request);
    }

    public function updateCart(Request $request)
    {
        if(isset($request->qty)){
            $user = $request->user()->id;
        DB::delete('DELETE FROM cart WHERE product_id = ? AND `user_id` = ? ', [$request->product, $user]);
        DB::insert('call addToCart(?, ? , ?) ',
            [
                $request->product,
                $request->user()->id,
                $request->qty,

            ]
        );
        }
        return $this->getCart($request);
        
        
    }

    public function deleteFromCart(Request $request, $product)
    {
        $user = $request->user()->id;

        DB::delete('DELETE FROM cart WHERE `user_id` = ? AND product_id = ?', [$user, $product]);

        return $this->getCart($request);

    }
    public function destroyCart(Request $request)
    {
        $user = $request->user()->id;
        DB::delete('DELETE FROM discount_cart WHERE `user_id` = ? ', [$user]);

        DB::delete('DELETE FROM cart WHERE `user_id` = ?', [$user]);

        return response()->json(['success' => 'true', 'message' => 'cart destroyed sucessfully']);

    }

}
