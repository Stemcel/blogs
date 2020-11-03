<?php

namespace App\Http\Controllers\Api;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class RbacTestController extends Controller
{
    /**
     * 生成角色
     */
    public function test()
    {
        // $owner = new Role();
        // $owner->name = 'owner';
        // $owner->display_name = 'Project Owner';
        // $owner->description = 'User is the owner of a given project';
        // $owner->save();

        // $admin = new Role();
        // $admin->name = 'admin';
        // $admin->display_name = 'User Administrator';
        // $admin->description = 'User is allowed to manage and edit other users';
        // $admin->save();

        $user = User::where('name', '=', 'zhw')->first();

        //调用EntrustUserTrait提供的attachRole方法
        $user->attachRole(1); // 参数可以是Role对象，数组或id
        // 或者也可以使用Eloquent原生的方法
        //$user->roles()->attach($admin->id); //只需传递id即可
    }

    public function permiss()
    {
        $createPost = new Permission();
        $createPost->name = 'create-post';
        $createPost->display_name = 'Create Posts';
        $createPost->description = 'create new blog posts';
        $createPost->save();

        $editUser = new Permission();
        $editUser->name = 'edit-user';
        $editUser->display_name = 'Edit Users';
        $editUser->description = 'edit existing users';
        $editUser->save();

        //查找角色绑定权限
        $role = Role::where('id', '=', '1')->first();
        $role->attachPermission($createPost);
        //等价于 $owner->perms()->sync(array($createPost->id));
        $role = Role::where('id', '=', '2')->first();
        $role->attachPermissions(array($createPost, $editUser));
        //等价于 $admin->perms()->sync(array($createPost->id, $editUser->id));
    }

    /**
     * 检查权限
     * 
     */
    public function check()
    {
        $user = User::where('id', '=', '1');
        $a = $user->hasRole('owner'); // false
        $b = $user->hasRole('admin'); // true
        $c = $user->can('edit-user'); // true
        $d = $user->can('create-post'); // true
        var_dump($a);
        var_dump($b);
        var_dump($c);
        var_dump($d);
    }
}
