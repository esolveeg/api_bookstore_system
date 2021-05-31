<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderProduct;
use App\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $data = $this->getRequestData($request);

        DB::transaction(function () use ($data) {
            $newOrder = Order::create($data['order']);
            $branchId = $data['order']['branch_id'];

            $this->storeOrderData($data['cart'], $branchId, $newOrder->id);
            $this->clearCart($data['user']);
        });

        return ['success' => true, 'message' => 'order created successfully'];

    }
    public function editOrder(Request $request, $id)
    {
        $id = (int) $id;
        $user = $request->user()->id;
        $order = DB::select('SELECT * FROM order_view WHERE id = ? LIMIT 1', [$id])[0];
        $this->clearCart($user);
        if ($order->discount !== null) {
            DB::delete('DELETE FROM discount_cart WHERE `user_id` = ? ', [$user]);
            DB::insert('INSERT INTO discount_cart (`user_id` , `discount`) VALUES(? , ?)', [$user, $order->discount]);
        }
        $products = DB::select('SELECT p.name , p.id product_id , p.price , op.qty , (op.qty * p.price) subtotal , (SELECT s.qty FROM stocks s WHERE s.product_id = p.id AND s.branch_id = o.branch_id ORDER BY s.id DESC LIMIT 1) stock FROM order_product op JOIN orders o ON o.id = op.order_id JOIN products p ON p.id = op.product_id WHERE op.order_id = ? ', [$id]);
        foreach ($products as $product) {
            DB::insert('call addToCart(?, ? , ?) ',
                [
                    $product->product_id,
                    $user,
                    $product->qty,
                ]
            );
        }

        return response()->json($order);
    }
    public function updateOrder(Request $request, $id)
    {
        $id = (int) $id;

        // dd($id);
        $order = DB::select('SELECT * FROM order_view WHERE id = ? LIMIT 1', [$id])[0];
        if ($order->branch_id !== null) {
            $products = DB::select('SELECT p.name , p.id product_id , p.price , op.qty , (op.qty * p.price) subtotal , (SELECT s.qty FROM stocks s WHERE s.product_id = p.id AND s.branch_id = o.branch_id ORDER BY s.id DESC LIMIT 1) stock FROM order_product op JOIN orders o ON o.id = op.order_id JOIN products p ON p.id = op.product_id WHERE op.order_id = ? ', [$id]);
        } else {
            $products = DB::select('SELECT p.name , p.id product_id , p.price , op.qty , (op.qty * p.price) subtotal , (SELECT s.qty FROM stocks s WHERE s.product_id = p.id AND s.branch_id IS NULL ORDER BY s.id DESC LIMIT 1) stock FROM order_product op JOIN orders o ON o.id = op.order_id JOIN products p ON p.id = op.product_id WHERE op.order_id = ? ', [$id]);

        }
        DB::transaction(function () use ($order, $products, $id, $request) {
            //rollback the old order
            $this->storeOrderData($products, $order->branch_id, $id, true);

            $data = $this->getRequestData($request);
            // //update order

            DB::table('orders')
                ->where('id', $id)
                ->update($data['order']);
            $this->storeOrderData($data['cart'], $data['order']['branch_id'], $id);

            $this->clearCart($data['user']);

        });
        //ceckout

        return ['success' => true, 'message' => 'order updated successfully'];

    }
    public function rollBackOrder(Request $request, $id)
    {
        $id = (int) $id;

        // dd($id);
        $order = DB::select('SELECT * FROM order_view WHERE id = ? LIMIT 1', [$id])[0];
        if ($order->branch_id !== null) {
            $products = DB::select('SELECT p.name , p.id product_id , p.price , op.qty , (op.qty * p.price) subtotal , (SELECT s.qty FROM stocks s WHERE s.product_id = p.id AND s.branch_id = o.branch_id ORDER BY s.id DESC LIMIT 1) stock FROM order_product op JOIN orders o ON o.id = op.order_id JOIN products p ON p.id = op.product_id WHERE op.order_id = ? ', [$id]);
        } else {
            $products = DB::select('SELECT p.name , p.id product_id , p.price , op.qty , (op.qty * p.price) subtotal , (SELECT s.qty FROM stocks s WHERE s.product_id = p.id AND s.branch_id IS NULL ORDER BY s.id DESC LIMIT 1) stock FROM order_product op JOIN orders o ON o.id = op.order_id JOIN products p ON p.id = op.product_id WHERE op.order_id = ? ', [$id]);

        }
        DB::transaction(function () use ($order, $products, $id) {
            //rollback the old order
            $this->storeOrderData($products, $order->branch_id, $id, false, true);
            DB::update("UPDATE orders SET deleted_at = now() WHERE id = ?", [$id]);

        });
        //ceckout
        return ['success' => true, 'message' => 'order rolled back successfully'];

    }
    public function rollBackOrderProucts(Request $request, $id)
    {
        $id = (int) $id;

        // dd($id);
        $order = DB::select('SELECT * FROM order_view WHERE id = ? LIMIT 1', [$id])[0];
        $products = DB::select('SELECT p.name , p.id product_id , p.price , op.qty , (op.qty * p.price) subtotal , (SELECT s.qty FROM stocks s WHERE s.product_id = p.id AND s.branch_id = o.branch_id ORDER BY s.id DESC LIMIT 1) stock FROM order_product op JOIN orders o ON o.id = op.order_id JOIN products p ON p.id = op.product_id WHERE op.order_id = ? ', [$id]);

        DB::transaction(function () use ($order, $products, $id) {
            //rollback the old order
            $this->storeOrderData($products, $order->branch_id, $id, true);
            DB::update("UPDATE orders SET deleted_at = now() WHERE id = ?", [$id]);

        });
        //ceckout
        return ['success' => true, 'message' => 'order rolled back successfully'];

    }
    public function getOrder(Request $request, $id)
    {
        $id = (int) $id;
        // dd($id);
        $order = DB::select('SELECT * FROM order_view WHERE id = ? LIMIT 1', [$id])[0];
        $products = DB::select('SELECT p.isbn , p.name , p.id product_id , p.price , op.qty , (op.qty * p.price) subtotal , (SELECT s.qty FROM stocks s WHERE s.product_id = p.id AND s.branch_id = o.branch_id ORDER BY s.id DESC LIMIT 1) stock FROM order_product op JOIN orders o ON o.id = op.order_id JOIN products p ON p.id = op.product_id WHERE op.order_id = ? ', [$id]);
        if($order->branch_id == 5 || $order->branch_id == 6){
            foreach($products as $product){
                  $product->price = $this->roundUpToAny($product->price + ($product->price * 25 / 100));
            }
        }
        return response()->json(['order' => $order, 'products' => $products]);

    }

    public function deleteOrder($id)
    {
        $id = (int) $id;
        DB::delete('DELETE FROM orders WHERE id = ? ', [$id]);
        return ['success' => true, 'message' => 'order deleted successfully'];

    }
    private function mapCart($cart, $branch, $force = false)
    {
        return array_map(function ($item) use ($branch, $force) {
            if ($branch == null) {
                $stock = DB::select('SELECT s.qty FROM stocks s WHERE s.product_id = ? AND ISNULL(s.branch_id) ORDER BY s.id DESC LIMIT 1', [$item->product_id]);
            } else {
                $stock = DB::select('SELECT s.qty FROM stocks s WHERE s.product_id = ? AND s.branch_id = ? ORDER BY s.id DESC LIMIT 1', [$item->product_id, $branch]);
            }
            $stock = isset($stock[0]) ? $stock[0]->qty : 0;
            $item->stock = $stock;

            if ($stock < $item->qty && !$force) {
                throw new \Exception('the product ' . $item->name . ' is no longer avilable in the stock with the quantity of ' . $item->qty . ' and we have only now ' . $stock . ' of it');
            }
            
            if($branch == 5 || $branch == 6){
                $item->price = $this->roundUpToAny($item->price + ($item->price * 25 / 100));
            }
            return $item;
        }, $cart);
    }
    
    protected function roundUpToAny($n, $x = 5)
    {
        return (ceil($n) % $x === 0) ? ceil($n) : round(($n + $x / 2) / $x) * $x;
    }

    private function getRequestData($request)
    {
        $payment = isset($request->payment) ? $request->payment : 'cash';
        $user = $request->user()->id;
        $cart = DB::select('call getCart(?) ',
            [
                $user,
            ]
        );

        // dd($cart);
        if (count($cart) == 0) {
            throw new \Exception('Sorry! No items on your cart.');
        }

        $total = DB::select('call getTotal(?) ',
            [
                $user,
            ]
        );

        $discount = DB::select('SELECT d.discount FROM discount_cart d WHERE d.user_id = ?', [$user]);

        $total = $total[0]->total;
        $branch = DB::select("SELECT e.branch_id id FROM users u JOIN employees e ON e.id = u.employee_id WHERE u.id = ? ", [$user]);
        $branch = isset($branch[0]) ? $branch[0]->id : null;
        if ($branch === null) {
            if (isset($request->branch_id)) {
                $branch = $request->branch_id;
            }
        }
        // $branch = $branch === null && isset($request->branch_id) ? (int) $request->branch_id : null;
        // dd($branch);
        if($branch == 5 || $branch == 6){
                $total = $this->roundUpToAny($total + ($total * 25 / 100));
        }
        $cart = $this->mapCart($cart, $branch, $request->force);
        if (isset($discount[0])) {
            $discount = $discount[0]->discount;
            $total = $total - ($total * $discount / 100);
        } else {
            $discount = 0;
        }
        $order = [
            'note' => $request->note,
            'created_by' => $user,
            'payment' => $payment,
            'discount' => $discount,
            'total' => $total,
            'customer_id' => $request->customer_id,
            'branch_id' => $branch,
        ];

        return ['payment' => $payment, 'user' => $user, 'cart' => $cart, 'total' => $total, 'discount' => $discount, 'branch' => $branch, 'order' => $order];
    }

    private function storeOrderData($cart, $branch, $id, $edit = false, $refund = false)
    {
        foreach ($cart as $item) {

            $productId = $item->product_id;

            $qtyAactual = $item->qty;
            $qtyAfter = $edit === true || $refund === true ? $item->stock + $qtyAactual : $item->stock - $qtyAactual;
            
            //update stock
            Stock::create([
                'product_id' => $productId,
                'branch_id' => $branch,
                'qty' => $qtyAfter,
            ]);
            //update orderProduct
            if ($edit === true) {
                DB::delete('DELETE FROM order_product WHERE `order_id` = ? ', [$id]);
            } else if ($refund === true) {

            } else {
                OrderProduct::create([
                    'product_id' => $productId,
                    'order_id' => $id,
                    'qty' => $qtyAactual,
                ]);
            }

        }
    }

    private function clearCart($user)
    {
        DB::delete('DELETE FROM cart WHERE `user_id` = ? ', [$user]);
        DB::delete('DELETE FROM discount_cart WHERE `user_id` = ? ', [$user]);
    }

}
