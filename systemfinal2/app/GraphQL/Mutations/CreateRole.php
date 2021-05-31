<?php

namespace App\GraphQL\Mutations;

use App\Role;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateRole
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
        $args['description'] = isset($args['description']) ? $args['description'] : null;
    $args['uids'] = isset($args['uids']) ? $args['uids'] : null;
        $role = Role::create([
            'name' => $args['name'],
            'slug' => $args['slug'],
            'description' => $args['description'],
            'level' => 1
        ]);
        $permissions = DB::table('permissions')
                    ->whereIn('id', $args['permissions'])
                    ->select('id')->get();
        $ids = [];
        foreach($permissions as $permission){
            array_push($ids, $permission->id);
        }
        if($args['users']){
        $users = DB::table('users')
                    ->whereIn('id', $args['users'])
                    ->select('id')->get();
        $uids = [];
        foreach($users as $user){
            array_push($uids, $user->id);
        }
        }
        


        $role->permissions()->sync($ids);
        isset($uids) ? $role->users()->sync($uids) : "";
        return $role;
        // TODO implement the resolver
    }
}
