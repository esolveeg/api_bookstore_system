<?php

use App\Branch;
use App\Customer;
use App\Expense;
use App\Order;
use App\OrderProduct;
use App\OutComingOrderProduct;
use App\OutcomingOrder;
use App\Permission;
use App\Product;
use App\Role;
use App\Stock;
use App\Transaction;
use App\TransactionProduct;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/chart/{branch?}' , function($branch = null){
  $chart_data = Order::select(
    DB::raw('YEAR(created_at) as year'),
    DB::raw('MONTH(created_at) as month'),
    DB::raw('SUM(total) as total')
  )->groupBy('month')->where('branch_id' , $branch)->get();
  return $chart_data;
});

Route::get('points/{id?}' , function($id = null){
  $customer = Customer::find($id);
  $points = Order::select(
    DB::raw('SUM(total) as total')
  )->where('customer_id',$id)->where('payment' , '!=' , 'points')->get();
  $orderPoints = Order::select(
    DB::raw('SUM(total) as total')
  )->where('customer_id',$id)->where('payment' , 'points')->get();
  $po = (int)$points[0]['total'] - (int)$orderPoints[0]['total'];
    return $po;
});
Route::get('/bestbranch' , function(){
  $branches = Branch::all();
  $data = [];
  
  $chart_data = Order::select(
    DB::raw('branch_id as branch'),
    DB::raw('SUM(total) as total')
  )->groupBy('branch')->orderBy('total' , 'desc')->where('branch_id','!=',null)->get();
  foreach ($chart_data as $d) {
    # code...
    $branch = Branch::find($d->branch);

    array_push($data , ['name' => $branch->name , 'total' => $d->total]);
  }
  return $data;
});

Route::get('/expenses/{branch?}' , function($branch = null){
  $chart_data = Expense::select(
    DB::raw('YEAR(created_at) as year'),
    DB::raw('MONTH(created_at) as month'),
    DB::raw('SUM(value) as value')
  )->groupBy('month')->where('branch_id' , $branch)->get();
  return $chart_data;
});


Route::get('/bestseller/{branch?}' , function($branch = null){
 $chart_data = OrderProduct::select(
    DB::raw('product_id'),
    DB::raw('SUM(qty) as totalQty')
  )->groupBy('product_id')->orderBy('totalQty' , 'DESC')->take(3)->get();
 $products = [];
 foreach($chart_data as $d){
  $product = Product::find($d->product_id);
  array_push($products , ['name'=>$product->name , 'qty'=>$d->totalQty]);
 }
 return $products;
});

