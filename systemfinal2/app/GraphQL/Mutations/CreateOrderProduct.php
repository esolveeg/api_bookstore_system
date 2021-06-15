<?php

namespace App\GraphQL\Mutations;

use App\Order;
use App\OrderProduct;
use App\Stock;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateOrderProduct
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
        // TODO implement the resolver
        $args['branch'] = isset($args['branch']) ? $args['branch'] : null;
        $order = Order::find($args['order']);
        $products = DB::table('products')
                    ->whereIn('id', $args['products'])
                    ->select('id')->get();
        $ids = [];
        $qtys= [];
        foreach($products as $product){
            $qty = Stock::where('product_id' , $product->id)->where('branch_id' , $args['branch'])->orderBy('created_at' , 'DESC')->first();
           $qty =  isset($qty) ? $qty->qty:0;
            array_push($qtys , $qty);
            array_push($ids, $product->id);
        }
        for($i=0;$i<count($qtys);$i++){
            $realQty = Stock::where('product_id' , $args['products'][$i])->where('branch_id' , $args['branch'])->orderBy('created_at' , 'DESC')->first();
             $realQty =  isset($realQty) ? $realQty->qty:0;
           $qty = $realQty - $args['qtys'][$i];
           $product_id = $args['products'][$i];
           Stock::create([
            'product_id' => $product_id,
            'branch_id' => $args['branch'],
            'qty' => $qty,
           ]);
           OrderProduct::create([
            'product_id' => $product_id,
            'order_id' => $args['order'],
            'qty' =>  $args['qtys'][$i]
           ]);
        }


        $order->save();
        return $order;
    }
}
