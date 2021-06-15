<?php

namespace App\GraphQL\Mutations;

use App\Order;
use App\OrderProduct;
use App\Stock;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateOrderProduct
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $args['branch'] = isset($args['branch']) ? $args['branch'] : null;
        $args['note'] = isset($args['note']) ? $args['note'] : null;
        $args['discount'] = isset($args['discount']) ? $args['discount'] : null;
        $args['customer_id'] = isset($args['customer_id']) ? $args['customer_id'] : null;
        $order = Order::find($args['order']);

        $order->customer_id = $args['customer_id'];
        $order->branch_id = $args['branch'];
        $order->note = $args['note'];
        $order->payment = $args['payment'];
        $order->discount = $args['discount'];
        $order->updated_by = $args['created_by'];
        $order->total = $args['total'];

        $realProducts = $order->products;
        $realBranch = $order->branch_id;
        DB::table('order_product')->where('order_id', $args['order'])->delete();
        foreach($realProducts as $product){
            $qty = Stock::where('product_id' , $product->id)->where('branch_id' , $realBranch)->orderBy('created_at' , 'DESC')->first()->qty + $product->pivot->qty;
            Stock::create([
                'product_id' => $product->id,
                'branch_id' => $realBranch,
                'qty' => $qty,
              ]);
        }
         $now = now();
        $created_at = Carbon::parse($now)->addSeconds(10);
        for($i=0;$i<count($args['qtys']);$i++){
        $realQty = Stock::where('product_id' , $args['products'][$i])->where('branch_id' , $realBranch)->orderBy('created_at' , 'DESC')->first();
        $realQty =  isset($realQty) ? $realQty->qty:0;
          $qty = $realQty - $args['qtys'][$i];
          $product_id = $args['products'][$i];
          Stock::create([
            'product_id' =>  $product_id,
            'branch_id' => $args['branch'],
            'qty' => $qty,
            'created_at' => $created_at
          ]);

          OrderProduct::create([
            'product_id' => $product_id,
            'order_id' => $args['order'],
            'qty' => $args['qtys'][$i]
          ]);
        }
        $order->save();
        return $order;
        
        // $realQtys = [];
        // $realIds = [];
        // $currentRealQtys = [];
        
        // DB::table('order_product')->where('order_id', $args['order'])->delete();
        // foreach($realProducts as $product){
        //     $qty = Stock::where('product_id' , $product->id)->where('branch_id' , $realBranch)->orderBy('created_at' , 'DESC')->first()->qty;
        //     array_push($currentRealQtys , $qty);
        //     array_push($realQtys , $product->pivot->qty);
        //     array_push($realIds, $product->id);

        // }
        // for($i=0;$i<count($realQtys);$i++){
        //   $qty = $realQtys[$i] + $currentRealQtys[$i];
        //   Stock::create([
        //     'product_id' => $realProducts[$i]->id,
        //     'branch_id' => $realBranch,
        //     'qty' => $qty,
        //   ]);
        // }
        // $products = DB::table('products')
        //             ->whereIn('id', $args['products'])
        //             ->select('id')->get();
        // $ids = [];
        // $qtys= [];
        // foreach($products as $product){
        //     $qty = Stock::where('product_id' , $product->id)->where('branch_id' , $args['branch'])->orderBy('created_at' , 'DESC')->first()->qty;
        //     array_push($qtys , $qty);
        //     array_push($ids, $product->id);
        // }
        // $now = now();
        // $created_at = Carbon::parse($now)->addSeconds(10);
        // for($i=0;$i<count($qtys);$i++){
        //     $realQty = Stock::where('product_id' , $products[$i]->id)->where('branch_id' , $args['branch'])->orderBy('created_at' , 'DESC')->first();
        //      $realQty =  isset($realQty) ? $realQty->qty:0;
        //   $qty = $realQty - $args['qtys'][$i];
        //   $product_id = $args['products'][$i];
        //   Stock::create([
        //     'product_id' =>  $product_id,
        //     'branch_id' => $args['branch'],
        //     'qty' => $qty,
        //     'created_at' => $created_at
        //   ]);

        //   OrderProduct::create([
        //     'product_id' => $product_id,
        //     'order_id' => $args['order'],
        //     'qty' => $args['qtys'][$i]
        //   ]);
        // }
        // $order->save();
        // return $order;
    }
}
