<?php

namespace App\GraphQL\Mutations;

use App\Stock;
use App\Transaction;
use App\TransactionProduct;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateTransactionProduct
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
        $transaction = Transaction::find($args['transaction']);
        $branchId = $transaction->branch_id;
        $products = DB::table('products')
                    ->whereIn('id', $args['products'])
                    ->select('id')->get();
        $ids = [];
        $qtys= [];
        foreach($products as $product){
            $qty = Stock::where('product_id' , $product->id)->where('branch_id' , null)->orderBy('created_at' , 'DESC')->first()->qty;
            array_push($qtys , $qty);
            array_push($ids, $product->id);
        }
        for($i=0;$i<count($qtys);$i++){
           $qty = $qtys[$i] - $args['qtys'][$i];
           $branchQty = Stock::where('product_id' , $products[$i]->id)->where('branch_id' , $branchId)->orderBy('created_at' , 'DESC')->first();
           $branchQty = isset($branchQty) ? $branchQty->qty : 0; 
           $branchQtyFinal =  $args['qtys'][$i] + $branchQty;
           Stock::create([
            'product_id' => $products[$i]->id,
            'qty' => $qty,
           ]);
           Stock::create([
            'product_id' => $products[$i]->id,
            'branch_id' => $branchId,
            'qty' => $branchQtyFinal,
           ]);

           TransactionProduct::create([
            'product_id' => $products[$i]->id,
            'qty' => $args['qtys'][$i],
            'transaction_id' => $args['transaction']
           ]);
        }

        $transaction->save();
        return $transaction;
    }
}
