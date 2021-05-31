<?php

namespace App;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
class Order extends Model
{
    //
    protected $dates = ['created_at','updated_at'];
    protected $guarded = [];
    public function filtered($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Builder
    {
        $orders = DB::table('orders');
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
        return $orders;
    }
    //relations  

    public function branch()
    {
    	return $this->belongsTo('App\Branch');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User' , 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo('App\User' , 'updated_by');
    }

    public function productsPivot()
    {
        return $this->hasMany('App\OrderProduct' , 'order_id');
    }
    public function customer()
    {
    	return $this->belongsTo('App\Customer');
    }

    public function products()
    {
    	return $this->belongsToMany('App\Product')->withPivot('qty');
    }

    public static function search($value){
        return self::where('total', 'like', '%' . $value . '%');
    }
}
