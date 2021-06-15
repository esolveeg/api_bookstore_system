<?php

namespace App\GraphQL\Mutations;

use App\Discount;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateDiscountProduct
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
        $discount = Discount::find($args['discount']);
        $products = DB::table('products')
                    ->whereIn('id', $args['products'])
                    ->select('id')->get();
        $ids = [];
        foreach($products as $product){
            array_push($ids, $product->id);
        }

        $discount->products()->sync($ids);
        $discount->save();
        return $discount;
    }
}
