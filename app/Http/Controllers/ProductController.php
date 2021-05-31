<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderProduct;
use App\OutcomingOrder;
use App\OutcomingOrderProduct;
use App\Stock;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Client;
use stdClass;

class ProductController extends Controller
{
    public function deleteById(Request $request)
    {
        if ($request->table == 'orders') {
            $products = DB::select('CALL getOrderProducts(?)', [$request->id]);
            $branch = Order::find($request->id);
            $branch = isset($branch->branch_id) ? $branch->branch_id : "";
            foreach ($products as $product) {
                $realQty = Stock::where('product_id', $product->id)->where('branch_id', $branch)->orderBy('created_at', 'DESC')->first();
                $realQty = isset($realQty->qty) ? $realQty->qty : 0;
                $qty = $realQty + $product->qty;
                $stock = [
                    'product_id' => $product->id,
                    'qty' => $qty,
                ];
                if ($branch !== '') {
                    $stock['branch_id'] = $branch;
                }
                Stock::create($stock);
            }
        }
        if ($request->table == 'transactions') {
            $products = DB::select('CALL getTransactionProducts(?)', [$request->id]);

            $branch = \App\Transaction::find($request->id);
            $from_branch = isset($branch->from_branch) ? $branch->from_branch : "";
            $branch = isset($branch->branch_id) ? $branch->branch_id : "";
            foreach ($products as $product) {
                $realQty = Stock::where('product_id', $product->id)->where('branch_id', $from_branch)->orderBy('created_at', 'DESC')->first();
                $realQty = isset($realQty->qty) ? $realQty->qty : 0;
                $qty = $realQty + $product->qty;
                $toRealQty = Stock::where('product_id', $product->id)->where('branch_id', $branch)->orderBy('created_at', 'DESC')->first();
                $toRealQty = isset($toRealQty->qty) ? $toRealQty->qty : 0;
                $toQty = $toRealQty - $product->qty;
                $stock = [
                    'product_id' => $product->id,
                    'qty' => $qty,
                ];
                $toStock = [
                    'product_id' => $product->id,
                    'qty' => $toQty,
                ];
                if ($branch !== '') {
                    $toStock['branch_id'] = $branch;
                }
                if ($from_branch !== '') {
                    $stock['branch_id'] = $from_branch;
                }

                Stock::create($stock);
                Stock::create($toStock);
            }
        }
        DB::delete('CALL deleteById(? , ? )', [$request->table, $request->id]);
        return response()->json(['message' => 'deleted_sucessfullt']);
    }
    public function editorder(Request $request, $id)
    {
        $order = Order::find($id);
        $products = DB::select('CALL getOrderProducts(?)', [$id]);
        $branch = isset($request->branch) ? $request->branch : '';
        $old_branch = $order->branch_id;
        foreach ($products as $product) {
            $product_id = $product->id;
            $realQty = Stock::where('product_id', $product_id)->where('branch_id', $old_branch)->orderBy('created_at', 'DESC')->first();
            $realQty = isset($realQty->qty) ? $realQty->qty : 0;
            $qty = $realQty + $product->qty;
            $stock = [
                'product_id' => $product_id,
                'qty' => $qty,
            ];

            if ($branch !== '') {
                $stock['branch_id'] = $old_branch;
            }
            Stock::create($stock);
        }
        sleep(2);
        DB::delete('DELETE FROM order_product WHERE order_id = ? ', [$id]);

        foreach ($request->products as $product) {
            $product_id = $product['id'];
            $realQty = Stock::where('product_id', $product_id)->where('branch_id', $branch)->orderBy('created_at', 'DESC')->first();
            $realQty = isset($realQty->qty) ? $realQty->qty : 0;
            $qty = $realQty - $product['qty'];
            $stock = [
                'product_id' => $product_id,
                'qty' => $qty,
            ];
            if ($branch !== '') {
                $stock['branch_id'] = $branch;
            }
            Stock::create($stock);

            $orderProduct = [
                'product_id' => $product_id,
                'qty' => $product['qty'],
                'order_id' => $order->id,
            ];
            \App\OrderProduct::create($orderProduct);
        }

        $order->branch_id = $request->branch;
        $order->payment = $request->payment;
        $order->discount = $request->discount;
        $order->total = $request->total;
        $order->customer_id = $request->customer_id;
        $order->note = $request->note;
        $order->save();
        return $order;

    }
    public function loginn(Request $request)
    {

        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|max:255',
        ]);
        if (User::where('email', $request->email)->count() == 0) {
            throw ValidationException::withMessages(['email' => 'email_not_found']);
        }

        return $this->loginAction($request);
    }
    public function getOrders(Request $request)
    {
        $user = $request->user()->id;
        $sort = isset($request->sort_by) ? $request->sort_by : "";
        $search = isset($request->search) ? $request->search : "";
        $branch = DB::select("SELECT e.branch_id id FROM users u JOIN employees e ON e.id = u.employee_id WHERE u.id = ? ", [$user]);
        $branch = isset($branch[0]) ? $branch[0]->id : null;
         if ($branch === null) {
            if (isset($request->branch)) {
                $branch = (int) $request->branch;
            }
        }
        $orders = DB::select('CALL getOrders(? , ? , ? , ? , ? , ? , ? , ? , ?)', [
            $request->offset,
            $request->no,
            $sort,
            $search,
            $request->sort_func,
            $branch,
            $request->from,
            $request->to,
            $request->product,
        ]);
        $count = DB::select('CALL getOrdersCount(? , ? ,? , ? )', [

            $search,

            $branch,
            $request->from,
            $request->to,

        ]);

        return response()->json(['data' => $orders, 'count' => $count[0]->total_rows]);

    }

    public function getTransactions(Request $request)
    {
        $sort = isset($request->sort_by) ? $request->sort_by : "";
        $search = isset($request->search) ? $request->search : "";

        $orders = DB::select('CALL getTransactions(? , ? , ? , ? , ? , ? , ? , ? , ? , ?)', [
            $request->offset,
            $request->no,
            $sort,
            $search,
            $request->sort_func,
            $request->branch,
            $request->to,
            $request->from,
            $request->product,
            $request->from_branch,

        ]);
        $count = DB::select('CALL getTransactionsCount(? , ? ,? , ? , ? )', [

            $search,
            $request->branch,
            $request->from,
            $request->to,
            $request->from_branch,

        ]);

        return response()->json(['data' => $orders, 'count' => $count[0]->total_rows]);

    }
    public function getBranches(Request $request)
    {
        $sort = isset($request->sort_by) ? $request->sort_by : "";
        $search = isset($request->search) ? $request->search : "";

        $orders = DB::select('CALL getBranches(? , ? , ? , ? , ? , ? ,  ?)', [
            $request->offset,
            $request->no,
            $sort,
            $search,
            $request->sort_func,
            $request->from,
            $request->to,
        ]);
        $count = DB::select('CALL getBranchesCount(?)', [
            $search,
        ]);

        return response()->json(['data' => $orders, 'count' => $count[0]->total_rows]);

    }
    public function getProduct($isbn, Request $request)
    {
        
        $product = \App\Product::where('isbn', $isbn)->first();
        if (isset($request->user()->employee_id)) {
            
            $branch = \App\Employee::find($request->user()->employee_id)->branch_id;
          
            // dd($branch);
            if ($branch == 5 || $branch == 6) {
                if ($product->isbn != '1112221') {
                    $product->price = $this->roundUpToAny($product->price + ($product->price * 20 / 100));
                    
                }
            }
        }
        return $product;

    }
    public function getFullProduct($id)
    {
        $product = \App\Product::where('id', $id)->first();
        $branches = DB::select("SELECT id , `name` FROM branches ");
        $stock = [];
        $object = $this->loadStock($id, null, 'main branch');
        array_push($stock, $object);
        foreach ($branches as $branch) {
            $object = $this->loadStock($id, $branch->id, $branch->name);
            array_push($stock, $object);
        }

        $result = ['product' => $product, 'stock' => $stock];
        return $result;

    }
    private function loadStock($id, $branch, $name)
    {
        if ($branch !== null) {
            $qty = DB::select("SELECT s.qty FROM stocks s  WHERE s.product_id = ? AND s.branch_id = ? ORDER BY s.id DESC LIMIT 1", [$id, $branch]);
        } else {
            $qty = DB::select("SELECT s.qty FROM stocks s  WHERE s.product_id = ? AND s.branch_id IS NULL ORDER BY s.id DESC LIMIT 1", [$id]);

        }

        $qty = isset($qty[0]) ? $qty[0]->qty : 0;
        $object = new stdClass();
        $object->qty = $qty;
        $object->branch = $name;
        return $object;
    }
    public function getOrder($id, Request $request)
    {
        $order = \App\Order::find($id);
        $products = DB::select('CALL getOrderProducts(?)', [$id]);
        if (isset($request->user()->employee_id)) {
            $branch = \App\Employee::find($request->user()->employee_id)->branch_id;
            // dd($branch);
            if ($branch == 5 || $branch == 6) {
                $products = array_map(function ($product) {
                    if ($product->isbn !== '1112221') {
                        $product->price = $this->roundUpToAny($product->price + ($product->price * 20 / 100));
                    }
                    return $product;
                }, $products);
            }
        }
        return response()->json(['order' => $order, 'products' => $products]);
    }
    public function getOutOrder($id, Request $request)
    {
        $order = OutcomingOrder::find($id);
        $products = DB::select('CALL getOutOrderProducts(?)', [$id]);
        return response()->json(['order' => $order, 'products' => $products]);
    }
    public function checkout(Request $request)
    {
        $request->validate([
            'payment' => 'required',
            'total' => 'required',
        ]);

        $products = [['id' => 1, 'qty' => 199], ['id' => 1200, 'qty' => 1]];
        $branch = DB::select('SELECT b.name , b.id FROM branches b INNER JOIN employees e ON b.id = e.branch_id WHERE e.id = ?', [$request->user()->employee_id]);
        $order = [
            'note' => $request->note,
            'created_by' => $request->user()->id,
            'payment' => $request->payment,
            'discount' => $request->discount,
            'total' => $request->total - $request->discount,
            'customer_id' => $request->customer_id,
        ];
        if ($request->branch) {
            $order['branch_id'] = $request->branch;
            $branch = $request->branch;
        } else {
            $branch == [] ? '' : $order['branch_id'] = $branch[0]->id;
            $branch = $branch != [] ? $branch[0]->id : '';
        }
        $discount = 0;
        $order = Order::create($order);
        foreach ($request->products as $product) {
            $realQty = Stock::where('product_id', $product['id'])->where('branch_id', $branch)->orderBy('created_at', 'DESC')->first();

            $realQty = isset($realQty->qty) ? $realQty->qty : 0;
            $qty = $realQty - $product['qty'];
            OrderProduct::create([
                'product_id' => $product['id'],
                'order_id' => $order['id'],
                'qty' => $product['qty'],
            ]);
            $stock = [
                'product_id' => $product['id'],
                'qty' => $qty,
            ];
            if ($branch !== '') {
                $stock['branch_id'] = $branch;
            }
            Stock::create($stock);
        }

        $products = DB::select('CALL getOrderProducts(?)', [$order->id]);
        if (isset($request->user()->employee_id)) {
            $branch = \App\Employee::find($request->user()->employee_id)->branch_id;
            // dd($branch);
            if ($branch == 5 || $branch == 6) {
                $products = array_map(function ($product) {
                    if ($product->isbn !== '1112221') {
                        $product->price = $this->roundUpToAny($product->price + ($product->price * 20 / 100));
                    }
                    return $product;
                }, $products);
            }
        }
        $orderResp = ['order' => $order, 'products' => $products];
        return $orderResp;
    }
    public function createOutOrder(Request $request)
    {
        $request->validate([
            'products' => 'required',
            'total' => 'required',
        ]);
        $branch = $request->branch;
        $order = [
            'note' => $request->note,
            'branch_id' => $branch,
            'total' => $request->total - $request->discount,

        ];
        $order = OutcomingOrder::create($order);
        foreach ($request->products as $product) {
            $realQty = Stock::where('product_id', $product['id'])->where('branch_id', $branch)->orderBy('created_at', 'DESC')->first();
            $realQty = isset($realQty->qty) ? $realQty->qty : 0;
            $qty = $realQty + $product['qty'];
            OutcomingOrderProduct::create([
                'product_id' => $product['id'],
                'outcoming_order_id' => $order['id'],
                'qty' => $product['qty'],
            ]);
            $stock = [
                'product_id' => $product['id'],
                'qty' => $qty,
            ];
            if ($branch !== '') {
                $stock['branch_id'] = $branch;
            }
            Stock::create($stock);
        }

        $products = DB::select('CALL getOutOrderProducts(?)', [$order->id]);

        $orderResp = ['order' => $order, 'products' => $products];
        return $orderResp;
    }
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|max:255',
        ]);
        if (User::where('email', $request->email)->count() == 0) {
            throw ValidationException::withMessages(['email' => 'email_not_found']);
        }
        return $this->loginAction($request);
    }
    protected function loginAction($request)
    {
        $passwordGrantClient = Client::find(env('PASSPORT_CLIENT_ID', 2));

        $data = [
            'grant_type' => 'password',
            'client_id' => $passwordGrantClient->id,
            'client_secret' => $passwordGrantClient->secret,
            'username' => $request['email'],
            'password' => $request['password'],
            'scope' => '*',
        ];

        $tokenRequest = Request::create('oauth/token', 'post', $data);
        $response = app()->handle($tokenRequest);

        if ($response->getStatusCode() >= 400) {
            throw ValidationException::withMessages(['password' => 'password_not_correct']);
        }

        return $response;
    }
    protected function roundUpToAny($n, $x = 5)
    {
        return (ceil($n) % $x === 0) ? ceil($n) : round(($n + $x / 2) / $x) * $x;
    }
    public function getProducts(Request $request)
    {
        $sort = isset($request->sort_by) ? $request->sort_by : "";
        $search = isset($request->search) ? $request->search : "";

        $products = DB::select('CALL getProducts(? , ? , ? , ? , ? , ? , ? )', [
            $request->offset,
            $request->no,
            $sort,
            $search,
            $request->sort_func,
            $request->branch,
            $request->bestsellers,
        ]);

        $count = DB::select('CALL getProductsCount(? , ? )', [

            $search,

            $request->branch,

        ]);
        if (isset($request->user()->employee_id)) {
            $branch = \App\Employee::find($request->user()->employee_id)->branch_id;
            // dd($branch);
            if ($branch == 5 || $branch == 6) {

                $products = array_map(function ($product) {
                    if ($product->isbn != '1112221') {
                        $product->price = $this->roundUpToAny($product->price + ($product->price * 20 / 100));
                    }
                    return $product;
                }, $products);
            }
        }
        // if($request->user()->employee)

        return response()->json(['data' => $products, 'count' => $count[0]->total_rows]);
    }

    public function getUserPermsissions(Request $request)
    {
        $permissions = DB::select('CALL getUserPermissions(?)', [$request->user()->id]);
        $branch = DB::select('SELECT b.id FROM branches b INNER JOIN employees e ON b.id = e.branch_id WHERE e.id = ?', [$request->user()->employee_id]);
        if ($branch == []) {
            $branch = '';
        } else {
            $branch = $branch[0]->id;
        }
        $pers = [];
        foreach ($permissions as $permission) {
            array_push($pers, $permission->slug);
        }
        return response()->json(['permissions' => $pers, 'branch' => $branch]);
    }

}
