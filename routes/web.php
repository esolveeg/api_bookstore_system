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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
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

Route::get('prs' , function(){
    $products = DB::select("SELECT p.id , p.name title , p.isbn , p.price FROM products p JOIN order_product op ON op.product_id = p.id");
    foreach($products as $product){
        $point = Stock::select([ 'qty'])->where('product_id' , $product->id)->where('branch_id' , 1)->orderBy('created_at' , 'DESC')->first();
        $uptown = Stock::select(['qty'])->where('product_id' , $product->id)->where('branch_id' , 4)->orderBy('created_at' , 'DESC')->first();
        if($uptown == null){
            $uptown = ['branch_id' => 3 , 'qty' => 0];
        } else {
            $uptown->branch_id = 3;
        }
        if($point == null){
            $point = ['branch_id' => 4 , 'qty' => 0];
        } else {
            $point->branch_id = 4;
        }
        //$uptown = DB::select("SELECT s.qty , s.branch_id ,  FROM stocks s WHERE s.branch_id = 4 AND s.product_id = ? ORDER BY s.created_at DESC" , [$product->id]);
        $product->stock = [
                $point,
                $uptown
            ];
    }
   
    return $products;
});

Route::get('prs_other' , function(){
   //$products = DB::select("SELECT p.id , p.name title , p.isbn , p.price FROM products p");
    $productsCount = DB::select("SELECT COUNT(*) FROM products p");
    dd($productsCount);
    foreach($products as $product){
        $point = Stock::select([ 'qty'])->where('product_id' , $product->id)->where('branch_id' , 1)->orderBy('created_at' , 'DESC')->first();
        $uptown = Stock::select(['qty'])->where('product_id' , $product->id)->where('branch_id' , 4)->orderBy('created_at' , 'DESC')->first();
        if($uptown == null){
            $uptown = ['branch_id' => 3 , 'qty' => 0];
        } else {
            $uptown->branch_id = 3;
        }
        if($point == null){
            $point = ['branch_id' => 4 , 'qty' => 0];
        } else {
            $point->branch_id = 4;
        }
        //$uptown = DB::select("SELECT s.qty , s.branch_id ,  FROM stocks s WHERE s.branch_id = 4 AND s.product_id = ? ORDER BY s.created_at DESC" , [$product->id]);
        $product->stock = [
                $point,
                $uptown
            ];
    }
   
    return $products;
});
Route::get('/c' , function(){
    $admin = User::create([
            "name" => "admin user",
            "email" => "ahmed@readerscorner.co",
            "password" => bcrypt(123456),
        ]);
        $adminRole =Role::where("slug" , "admin")->first()->id;
        $admin->roles()->attach($adminRole);
});
Route::post('trackproduct' , function(Request $request){
   // transactions query "SELECT tp.qty , t.from_branch , t.branch_id FROM products p JOIN transaction_product tp ON tp.product_id = p.id JOIN transactions t ON tp.transaction_id = t.id WHERE p.id = 11405 "
   if(isset($request->to)){
       $to = $request->to . ' 23:59:59';
   }else{
       $to = now()->toDateTimeString('Y-m-d');
   }
   if(isset($request->from)){
       $from = $request->from . ' 00:00:00';
   }else{
       $from = "2010-03-18 13:36:21";
   }
   $id = Product::where('isbn' , $request->isbn)->first()->id;
   if(isset($request->branch)){
     $transactions = DB::select("SELECT t.created_at, t.id , tp.qty , fb.name from_branch , tb.name branch_id FROM products p JOIN transaction_product tp ON tp.product_id = p.id JOIN transactions t ON tp.transaction_id = t.id JOIN branches fb ON fb.id = t.from_branch JOIN branches tb ON tb.id = t.branch_id  WHERE (p.id = ? )  AND ( t.created_at > ? ) AND ( t.created_at < ? ) AND (fb.id = ? OR tb.id = ? )" , [$id , $from , $to , $request->branch , $request->branch]);
     $orders = DB::select("SELECT o.created_at , o.id , op.qty , b.name branch FROM products p JOIN order_product op ON op.product_id = p.id JOIN orders o ON op.order_id = o.id  JOIN branches b ON b.id = o.branch_id  WHERE p.id = ?  AND o.created_at > ? AND o.created_at < ? AND b.id = ? " , [$id , $from , $to , $request->branch]);     
     $outcoming = DB::select("SELECT o.created_at , o.id , op.qty , b.name branch FROM products p JOIN outcoming_order_product op ON op.product_id = p.id JOIN outcoming_orders o ON op.outcoming_order_id = o.id  JOIN branches b ON b.id = o.branch_id  WHERE p.id = ?  AND o.created_at > ? AND o.created_at < ? AND b.id = ? " , [$id , $from , $to , $request->branch]);     
   }else{
      $transactions = DB::select("SELECT t.created_at , t.id , tp.qty , fb.name from_branch , tb.name branch_id FROM products p JOIN transaction_product tp ON tp.product_id = p.id JOIN transactions t ON tp.transaction_id = t.id JOIN branches fb ON fb.id = t.from_branch JOIN branches tb ON tb.id = t.branch_id  WHERE p.id = ?  AND t.created_at > ? AND t.created_at < ? " , [$id , $from , $to]);
        $orders = DB::select("SELECT o.created_at , o.id , op.qty , b.name branch FROM products p JOIN order_product op ON op.product_id = p.id JOIN orders o ON op.order_id = o.id  JOIN branches b ON b.id = o.branch_id  WHERE p.id = ?  AND o.created_at > ? AND o.created_at < ? " , [$id , $from , $to]);     
        $outcoming = DB::select("SELECT o.created_at , o.id , op.qty , b.name branch FROM products p JOIN outcoming_order_product op ON op.product_id = p.id JOIN outcoming_orders o ON op.outcoming_order_id = o.id  JOIN branches b ON b.id = o.branch_id  WHERE p.id = ?  AND o.created_at > ? AND o.created_at < ? " , [$id , $from , $to]);     
   }
  
    return view('trackproduct')->with(['transactions' => $transactions , 'orders' => $orders , 'outcoming' => $outcoming]); 
   // orders query "SELECT op.qty , o.branch_id FROM products p JOIN order_product op ON op.product_id = p.id JOIN orders o ON op.order_id = o.id WHERE p.id = 11405 " 
});
Route::get('trackisbn' , function(){
      $branches = DB::table('branches')->get();
     return view('trackisbn')->with(['branches' => $branches]);
   // transactions query "SELECT tp.qty , t.from_branch , t.branch_id FROM products p JOIN transaction_product tp ON tp.product_id = p.id JOIN transactions t ON tp.transaction_id = t.id WHERE p.id = 11405 "
   // orders query "SELECT op.qty , o.branch_id FROM products p JOIN order_product op ON op.product_id = p.id JOIN orders o ON op.order_id = o.id WHERE p.id = 11405 " 
});
Route::get('checkbranch' , function(){
    $branches = DB::table('branches')->get();
    return view('checkbranch')->with(['branches' => $branches]);
});
Route::post('checkbranch' , function(Request $request){
   
    $products = DB::select('SELECT p.id , p.name , p.isbn , b.name branch_name , s.qty quantity FROM stocks s JOIN branches b ON b.id = s.branch_id JOIN products p ON p.id = s.product_id WHERE s.branch_id = ? AND s.qty < 0' , [$request->branch]);

    return view('minus')->with(['products' => $products]);
});
Route::get('/uploa' , function(){
    $res = Http::get('https://readerscorner.co/public/api/products');
    foreach (json_decode($resp->body()) as $product) {
            dd($product);
            $productt = Product::create([
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'isbn' => $product->isbn,
                    'details' => $product->details,
                    'description' => $product->description,
                    'image' => $product->image,
                    'price' => $product->price / 100,
                    'language_id' => $language,
                    'author_id' => $author,
                ]);
  
            
    
        }
    return back();
});


Route::post('/lavistafull' , function(Request $request){
    $product = Product::where('isbn' , $request->isbn)->first();
    if(!isset($product->id)){
         $product = Product::create([
                "name"  =>$request->name,
                "isbn"  =>$request->isbn,
                "purchased_price"  =>$request->price / 2,
                "price"  =>$request->price,]);
    }
    Stock::create([
        'product_id' => $product->id,
        'qty' => $request->qty,
        'branch_id' => 6,
        ]);
    ;
    return back();
});
Route::post('/lavista' , function(Request $request){
    $product = Product::where('isbn' , $request->isbn)->first();
    $qty = Stock::where('product_id' , $product->id)->where('branch_id' , 6)->orderBy('created_at' , 'DESC')->first();
    $qty = isset($qty->qty) ? $qty->qty : 0;

    if(isset($product->id)){
         Stock::create([
        'product_id' => $product->id,
        'qty' => $request->qty + $qty,
        'branch_id' =>6,
        ]);
    ;
    return back();
    }else{
        return redirect('/lavistafull');    
    }
   
});

Route::post('/productfull/{branch}' , function(Request $request , $branch){
    $product = Product::where('isbn' , $request->isbn)->first();
    if(!isset($product->id)){
         $product = Product::create([
                "name"  =>$request->name,
                "isbn"  =>$request->isbn,
                "purchased_price"  =>$request->price / 2,
                "price"  =>$request->price,]);
    }
    Stock::create([
        'product_id' => $product->id,
        'qty' => $request->qty,
        'branch_id' => $branch,
        ]);
    ;
    return back();
});
Route::get('/productfull/{branch}' , function(Request $request , $branch){
    
   return view('productfull')->with(['branch' => $branch]);
})->name('productfull');
Route::get('/product/{branch}' , function(Request $request , $branch){
    
   return view('product')->with(['branch' => $branch]);
})->name('product');
Route::get('/getbranch' , function(Request $request){
    
    $branches = DB::table('branches')->get();
    return view('getbranch')->with(['branches' => $branches]);
});
Route::post('/getbranch' , function(Request $request){
    
    if(isset($request->full)){
        return redirect()->route('productfull', ['branch' => $request->branch]);
    }else{
        return redirect()->route('product', ['branch' => $request->branch]);
    }
    return view('getbranch')->with(['branches' => $branches]);
});

Route::post('/product/{branch}' , function(Request $request , $branch){
    $product = Product::where('isbn' , $request->isbn)->first();
    $qty = Stock::where('product_id' , $product->id)->where('branch_id' , $branch)->orderBy('created_at' , 'DESC')->first();
    $qty = isset($qty->qty) ? $qty->qty : 0;

    if(isset($product->id)){
         Stock::create([
        'product_id' => $product->id,
        'qty' => $request->qty + $qty,
        'branch_id' =>$branch,
        ]);
    ;
    return back();
    }else{
        return redirect('/productfull');    
    }
   
});
Route::post('/uptownfull' , function(Request $request){
    $product = Product::where('isbn' , $request->isbn)->first();
    if(!isset($product->id)){
         $product = Product::create([
                "name"  =>$request->name,
                "isbn"  =>$request->isbn,
                "purchased_price"  =>$request->price / 2,
                "price"  =>$request->price,]);
    }
    Stock::create([
        'product_id' => $product->id,
        'qty' => $request->qty,
        'branch_id' => 4,
        ]);
    ;
    return back();
});
Route::post('/uptown' , function(Request $request){
    $product = Product::where('isbn' , $request->isbn)->first();
    $qty = Stock::where('product_id' , $product->id)->where('branch_id' , 6)->orderBy('created_at' , 'DESC')->first();
    $qty = isset($qty->qty) ? $qty->qty : 0;

    if(isset($product->id)){
         Stock::create([
        'product_id' => $product->id,
        'qty' => $request->qty + $qty,
        'branch_id' =>4,
        ]);
    ;
    return back();
    }else{
        return redirect('/uptownfull');    
    }
   
});


Route::post('/marasifull' , function(Request $request){
    $product = Product::where('isbn' , $request->isbn)->first();
    if(!isset($product->id)){
         $product = Product::create([
                "name"  =>$request->name,
                "isbn"  =>$request->isbn,
                "purchased_price"  =>$request->price / 2,
                "price"  =>$request->price,]);
    }
    Stock::create([
        'product_id' => $product->id,
        'qty' => $request->qty,
        'branch_id' => 5,
        ]);
    ;
    return back();
});
Route::post('/marasi' , function(Request $request){
    $product = Product::where('isbn' , $request->isbn)->first()->id;
    Stock::create([
        'product_id' => $product,
        'qty' => $request->qty,
        'branch_id' =>5,
        ]);
    ;
    return back();
});
Route::post('/point' , function(Request $request){
    $product = Product::where('isbn' , $request->isbn)->first()->id;
     $qty = Stock::where('product_id' , $product)->where('branch_id' , 1)->orderBy('created_at' , 'DESC')->first();
    $qty = isset($qty->qty) ? $qty->qty : 0;
    Stock::create([
        'product_id' => $product,
        'qty' => $request->qty + $qty,
        'branch_id' =>1,
        ]);
    ;
    return back();
});
Route::get('/uploadfull' , function(){
    return view('welcome2');
});
Route::get('/tessssssst' , function(){
    return view('welcome');
});
Route::get('/lavistafull' , function(){
    return view('lavistafull');
});
Route::get('/lavista' , function(){
    return view('lavista');
});
Route::get('/uptownfull' , function(){
    return view('uptownfull');
});
Route::get('/uptown' , function(){
    return view('uptown');
});
Route::get('/marasifull' , function(){
    return view('marasifull');
});
Route::get('/marasi' , function(){
    return view('marasi');
});
Route::get('/point' , function(){
    return view('point');
});
Route::get('any' , function(){
        $products2 =array(
	 array('12250','3',),
	 array('12246','0',),
	 array('12245','5',),
	 array('12244','0',),
	 array('12241','1',),
	 array('12235','0',),
	 array('12234','2',),
	 array('12222','1',),
	 array('12215','0',),
	 array('12196','0',),
	 array('12170','1',),
	 array('12169','1',),
	 array('12168','1',),
	 array('12166','3',),
	 array('12165','2',),
	 array('12162','0',),
	 array('12161','0',),
	 array('12143','4',),
	 array('12135','3',),
	 array('12131','2',),
	 array('12094','1',),
	 array('12087','1',),
	 array('12080','0',),
	 array('12063','1',),
	 array('12057','3',),
	 array('12056','2',),
	 array('12054','1',),
	 array('12034','3',),
	 array('11965','2',),
	 array('11960','7',),
	 array('11894','10',),
	 array('11892','4',),
	 array('11890','1',),
	 array('11887','4',),
	 array('11886','4',),
	 array('11760','35',),
	 array('11759','11',),
	 array('11757','11',),
	 array('11754','1',),
	 array('11752','1',),
	 array('11563','1',),
	 array('7446','0',),
	 array('7438','0',),
	 array('6248','1',),
	 array('5977','0',),
	 array('5976','0',),
	 array('5648','1',),
	 array('5646','1',),
	 array('5581','1',),
	 array('4745','1',),
	 array('3799','0',),
	 array('2428','3',),
	 array('2419','1',),
	 array('2418','1',),
	 array('2417','1',),
	 array('2416','1',),
	 array('2415','1',),
	 array('2414','1',),
	 array('2034','1',),
	 array('1931','0',),
	 array('1891','0',),
	 array('1649','0',),
	 array('1617','0',),
	 array('1488','1',),
	 array('1363','0',),
	 array('609','0',),
	 array('606','0',),
	 array('605','0',),
);
  
        foreach ($products2 as $product) {
          
           $id = $product[0];
           $qty = $product[1];
            if($product !== []){
                DB::delete("DELETE FROM stocks WHERE branch_id = 5 AND product_id = ? " , [$id]);
              $pr = Product::find($id);
              Stock::create([
                        "product_id" => $pr->id,
                        "branch_id" =>5,
                        "qty" => $qty
                      ]);
              
            
            // $pr = Product::where('isbn' , $product['ISBN'])->first();
            
            // if(!$pr){
            //   $dbproduct = Product::create([
            //     "name"  =>strval($product['Name']),
            //     "isbn"  =>strval($product['ISBN']),
            //     "purchased_price"  =>(int)$product['Price'] * 50 /100,
            //     "price"  =>(int)$product['Price'],
            //   ]);
            //   if($product['qty'] == null ){
            //     $product['qty'] =  0;
            //   }
            //   Stock::create([
            //     "product_id" => $dbproduct->id,
            //     "branch_id" =>4,
            //     "qty" => 0
            //   ]);
              
            //   Stock::create([
            //     "product_id" => $dbproduct->id,
            //     "branch_id" =>3,
            //     "qty" => 0
            //   ]);
            //   Stock::create([
            //     "product_id" => $dbproduct->id,
            //     "branch_id" =>2,
            //     "qty" => 0
            //   ]);
              
            //   Stock::create([
            //     "product_id" => $dbproduct->id,
            //     "branch_id" =>5,
            //     "qty" => $product['qty']
            //   ]);
            //   Stock::create([
            //       "product_id" => $dbproduct->id,
            //       "qty" => 0
            //   ]);
               
            // }else{
            //     if($product['qty'] == null ){
            //     $product['qty'] =  0;
            //   }
            //     Stock::create([
            //     "product_id" => $pr->id,
            //     "branch_id" =>5,
            //     "qty" => $product['qty']
            //   ]);
            // }
            
            }
        
        
    } 
    
});
Route::get('/chart/{branch?}' , function($branch = null){
  $chart_data = Order::select(
    DB::raw('YEAR(created_at) as year'),
    DB::raw('MONTH(created_at) as month'),
    DB::raw('DAY(created_at) as day'),
    DB::raw('SUM(total) as total')
  )->groupBy('day')->where('branch_id' , $branch)->get();
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

