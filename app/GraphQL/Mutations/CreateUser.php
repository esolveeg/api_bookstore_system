<?php

namespace App\GraphQL\Mutations;

use App\Role;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateUser
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
        $args['employee'] = isset($args['employee']) ? $args['employee'] : null;
        $user = User::create([
            'name' => $args['name'],
            'email' => $args['email'],
            'employee_id' => $args['employee'],
            'password' => bcrypt($args['password']), 
        ]);
        $roles = DB::table('roles')
                    ->whereIn('slug', $args['roles'])
                    ->select('id')->get();
                    $ids = [];
                    foreach($roles as $role){
                        array_push($ids, $role->id);
                    }
        $user->roles()->sync($ids);
        return $user;
    }
}
