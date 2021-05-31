<?php

namespace App\GraphQL\Mutations;

use App\Role;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateRole
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
        $role=Role::find($args['id']);
        $args['uids'] = isset($args['uids']) ? $args['uids'] : null;
        
        if($role->slug !== $args['slug'] && Role::where('slug' ,$args['slug'])->first()){
            return 'error';
        }
        $args['description'] = isset($args['description']) ? $args['description'] : null;
        $role->name = $args['name'];
        $role->slug = $args['slug'];
        $role->description = $args['description'];
        if($args['users']){
            $users = DB::table('users')
                    ->whereIn('id', $args['users'])
                    ->select('id')->get();
                    $ids = [];
                    foreach($users as $user){
                        array_push($ids, $user->id);
                    }
            $role->users()->sync($ids);
        }

        if($args['permissions']){
            //dd('hi');
            $permissions = DB::table('permissions')
                    ->whereIn('id', $args['permissions'])
                    ->select('id')->get();
                    $pids = [];
                    foreach($permissions as $permission){
                        array_push($pids, $permission->id);
                    }
            $role->permissions()->sync($pids);
        }
        
        $role->save();
        return $role;
    }
}
