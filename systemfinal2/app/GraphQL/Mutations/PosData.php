<?php

namespace App\GraphQL\Mutations;

use App\Order;
use App\User;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class PosData
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
        $user = User::find($args['id']);
        $target = $user->employee->target;
        $salesQuery = Order::select(
    DB::raw('YEAR(created_at) as year'),
    DB::raw('MONTH(created_at) as month'),
    DB::raw('SUM(total) as total')
  )->groupBy('month')->whereMonth('created_at', Carbon::now()->month)->where('created_by' , $args['id'])->get();

        $sales= isset($salesQuery[0]) ? $salesQuery[0]['total'] : 0;
        $sales  > $target ? $over = $sales - $target : $rest = $target-$sales;
        isset($over) ? $rest = 0 : $over = 0; 
        return [
            "rest" => $rest,
            "sales" => $sales,
            "target" => $target,
            "over" => $over,
        ];
    }
}
