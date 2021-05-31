<?php

namespace App\GraphQL\Queries;

use App\Stock;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Product
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
       $product = \App\Product::find($args['id']);
        $args['branch'] = isset($args['branch']) ? $args['branch'] : null;
        
        $qty = Stock::where('product_id' , $args['id'])->where('branch_id' , $args['branch'])->orderBy('created_at' , 'DESC')->first();
        $product->qty = isset($qty) ? $qty->qty : 0;
            
       
        
        return $product;
    }
}
