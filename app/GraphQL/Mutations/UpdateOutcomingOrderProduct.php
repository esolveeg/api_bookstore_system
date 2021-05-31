<?php

namespace App\GraphQL\Mutations;

use App\OutComingOrderProduct;
use App\OutcomingOrder;
use App\Stock;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateOutcomingOrderProduct
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
        $order = OutcomingOrder::find($args['outcomingOrder']);
        $realProducts = $order->products;
        $realBranch = $order->branch_id;
        $realQtys = [];
        $realIds = [];
        $currentRealQtys = [];
        DB::table('outcoming_order_product')->where('outcoming_order_id', $args['outcomingOrder'])->delete();
        foreach($realProducts as $product){
            $qty = Stock::where('product_id' , $product->id)->where('branch_id' , $realBranch)->orderBy('created_at' , 'DESC')->first()->qty;
            array_push($currentRealQtys , $qty);
            array_push($realQtys , $product->pivot->qty);
            array_push($realIds, $product->id);

        }
        for($i=0;$i<count($realQtys);$i++){
           $qty = $currentRealQtys[$i] - $realQtys[$i];
           Stock::create([
            'product_id' => $realProducts[$i]->id,
            'branch_id' => $realBranch,
            'qty' => $qty,
           ]);
        }
        $products = DB::table('products')
                    ->whereIn('id', $args['products'])
                    ->select('id')->get();
        $ids = [];
        $qtys= [];
        foreach($products as $product){
            $qty = Stock::where('product_id' , $product->id)->where('branch_id' , $args['branch'])->orderBy('created_at' , 'DESC')->first()->qty;
            array_push($qtys , $qty);
            array_push($ids, $product->id);
        }
        $now = now();
        $created_at = Carbon::parse($now)->addSeconds(10);
        for($i=0;$i<count($qtys);$i++){
           $qty = $qtys[$i] + $args['qtys'][$i];
           $product_id = $products[$i]->id;
           Stock::create([
            'product_id' => $product_id,
            'branch_id' => $args['branch'],
            'qty' => $qty,
            'created_at' => $created_at
           ]);
           OutComingOrderProduct::create([
            'product_id' => $product_id,
            'outcoming_order_id' => $args['outcomingOrder'],
            'qty' => $args['qtys'][$i]
           ]);
        }


        $order->save();
        return $order;
    }
}
