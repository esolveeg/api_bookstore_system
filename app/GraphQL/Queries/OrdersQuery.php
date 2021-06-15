<?php

namespace App\GraphQL\Queries;

use App\Order;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class OrdersQuery
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
    $orders = new Order;
    if(isset($args['branch_id'])){
        if($args['branch_id'] == 'store'){
          $orders = $orders->where('branch_id' , null);
        }else{
          $orders = $orders->where('branch_id' , $args['branch_id']);
        }
    }
    if(isset($args['created_by'])){
        $orders = $orders->where('created_by' , $args['created_by']);
    }
    if(isset($args['from'])){
        if(isset($args['to'])){
          $orders = $orders->whereRaw(
              "(created_at >= ? AND created_at <= ?)", 
              [$args['from']." 00:00:00", $args['to']." 23:59:59"]);
          // $orders = $orders->whereDate('created_at' ,'>' , $args['to']);
        }else{
          $orders = $orders->where('created_at','>=',$args['from']." 00:00:00");
        }
    }
    if(isset($args['to'])){
      $orders = $orders->where('created_at','<=',$args['to']." 23:59:59");
      
      // $orders = $orders->whereDate('created_at' ,'>' , $args['to']);
    }
    if(isset($args['search']) && $args['search'] ){
      $orders  = $orders->where('note', 'LIKE', '%'.$args['search'].'%');
    } 
    if(isset($args['orderBy'])){
       isset($args['orderFunc'])? "" : $args['orderFunc'] ='ASC';
      $orders  = $orders->orderBy($args['orderBy'], $args['orderFunc']);
    } 
    if(isset($args['first'])){
      if($args['first'] == 'all'){
        // $orders  = $orders->get();
        $orders  = $orders->paginate(10);

      }else{
        if(isset($args['page'])){
          $orders = $orders->paginate($args['first'], ['*'], 'page', $args['page']);
        }else{
        $orders  = $orders->paginate($args['first']);

        }
      }
    }else{
      if(isset($args['page'])){
          $orders = $orders->paginate(10, ['*'], 'page', $args['page']);
      }else{
          $orders = $orders->paginate(10);

      }
    }
    return response()->json($orders ,200);

    // if(isset($args['created_by'])){
    //     $orders = $orders->where('created_by' , $args['created_by']);
    // }
  
    // if(isset($args['branch_id'])){
    //     if($args['branch_id'] == 'store'){
    //       $orders = $orders->where('branch_id' , null);
    //     }else{
    //       $orders = $orders->where('branch_id' , $args['branch_id']);
    //     }
    // }
    // if(isset($args['from'])){
    //     if(isset($args['to'])){
    //       $orders = $orders->whereRaw(
    //           "(created_at >= ? AND created_at <= ?)", 
    //           [$args['from']." 00:00:00", $args['to']." 23:59:59"]);
    //       // $orders = $orders->whereDate('created_at' ,'>' , $args['to']);
    //     }else{
    //       $orders = $orders->where('created_at','>=',$args['from']." 00:00:00");
    //     }
    // }
    // if(isset($args['to'])){
    //   $orders = $orders->where('created_at','<=',$args['to']." 23:59:59");
      
    //   // $orders = $orders->whereDate('created_at' ,'>' , $args['to']);
    // }
    // if(isset($args['search'])){
    //   $orders  = $orders->where('note', 'LIKE', '%'.$args['search'].'%')
    //   ->orWhere('total', 'LIKE', '%'.$args['search'].'%')
    //   ->orWhere('created_at', 'LIKE', '%'.$args['search'].'%')  ;
    // } 
    // if(isset($args['orderBy'])){
    //    isset($args['orderFunc'])? "" : $args['orderFunc'] ='ASC';
    //   $orders  = $orders->orderBy($args['orderBy'], $args['orderFunc']);
    // } 
    // $orders = $orders->where('branch_id' , 2);
    // if(isset($args['first'])){
    //   if($args['first'] == 'all'){
    //     $orders  = $orders->get();
    //   }else{
    //     if(isset($args['page'])){
    //       $orders = $orders->paginate($args['first'], ['*'], 'page', $args['page']);
    //     }else{
    //     $orders  = $orders->paginate($args['first']);

    //     }
    //   }
    // }else{
    //   if(isset($args['page'])){
    //       $orders = $orders->paginate(10, ['*'], 'page', $args['page']);
    //   }else{
    //       $orders = $orders->paginate(10);

    //   }
    // }
  
    // return $orders;
    }


}
