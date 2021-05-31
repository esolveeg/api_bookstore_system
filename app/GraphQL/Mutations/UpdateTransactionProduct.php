<?php

namespace App\GraphQL\Mutations;

use App\Stock;
use App\Transaction;
use App\TransactionProduct;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateTransactionProduct
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
      $branch = $transaction->branch_id;
      $newBranch = $args['branch'];
      $realProducts = $transaction->products;

      // reset transaction
      foreach($realProducts as $pro){
        $qty = Stock::where('product_id' , $pro->id)->where('branch_id' , $branch)->orderBy('created_at' , 'DESC')->first();
        $qty = isset($qty) ? $qty->qty : 0; 
        $storeQty = Stock::where('product_id' , $pro->id)->where('branch_id' , null)->orderBy('created_at' , 'DESC')->first();
        $storeQty = isset($storeQty) ? $storeQty->qty : 0; 

        $transactionQty = TransactionProduct::where('product_id' , $pro->id)->where('transaction_id' , $args['transaction'])->first();
        $transactionQty = isset($transactionQty) ? $transactionQty->qty : 0; 
        
        Stock::create([
          'product_id' => $pro->id,
          'branch_id' => $branch,
          'qty' => $qty - $transactionQty,
        ]);
        Stock::create([
          'product_id' => $pro->id,
          'branch_id' => null,
          'qty' => $storeQty + $transactionQty,
        ]);
      }
      DB::table('transaction_product')->where('transaction_id', $args['transaction'])->delete();

      //recreate
      $now = now();
      $created_at = Carbon::parse($now)->addSeconds(10);
      for($i=0;$i<count($args['products']);$i++){
        $productId= $args['products'][$i];
        $qty= $args['qtys'][$i];
        $branchQty = Stock::where('product_id' , $productId)->where('branch_id' , $args['branch'])->orderBy('created_at' , 'DESC')->first();
        $branchQty = isset($branchQty) ? $branchQty->qty : 0; 

        $storeQty = Stock::where('product_id' , $productId)->where('branch_id' , null)->orderBy('created_at' , 'DESC')->first();
        $storeQty = isset($storeQty) ? $storeQty->qty : 0; 

        Stock::create([
          'product_id' => $productId,
          'branch_id' => $args['branch'],
          'qty' => $qty + $branchQty,
        ]);
        Stock::create([
          'product_id' => $productId,
          'branch_id' => null,
          'qty' => $storeQty - $qty,
          'created_at' => $created_at
        ]);
        TransactionProduct::create([
          'product_id' => $productId,
          'qty' => $qty,
          'transaction_id' => $args['transaction']
        ]);
      }
      //edit transaction
      $transaction->branch_id = $args['branch'];
      $transaction->save();
      return $transaction;

    }
}
