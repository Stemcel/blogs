<?php

namespace App\Http\Controllers\Api;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Zizaco\Entrust\Entrust;

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

        $user = User::where('name', '=', 'zhw1')->first();

        //调用EntrustUserTrait提供的attachRole方法
        $user->attachRole(2); // 参数可以是Role对象，数组或id
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
        $user = User::where('id', '=', '32')->first();
        // $a = $user->hasRole('owner'); // false
        // $b = $user->hasRole('admin'); // true
        // $c = $user->can('edit-user'); // true
        // $d = $user->can('create-post'); // true
        // dd($a);
        // dd($b);
        // dd($c);
        // dd($d);
        //如果用户拥有以上任意一个角色或权限都会返回true，如果你想检查用户是否拥有所有角色及权限，可以传递true作为第二个参数到相应方法：
        // $user->hasRole(['owner', 'admin']); // true
        // $user->hasRole(['owner', 'admin'], true); // false
        // $user->can(['edit-user', 'create-post']); // true
        // $user->can(['edit-user', 'create-post'], true); // false

        //ability方法
        //通过使用ability方法来实现更加高级的检查，这需要三个参数（角色、权限、选项），同样角色和权限既可以是字符串也可以是数组：
        $options = array(
            'validate_all' => true,
            'return_type' => 'both'
        );
        list($validate, $allValidations) = $user->ability(
            array('admin', 'owner'),
            array('create-post', 'edit-user'),
            $options
        );
        var_dump($validate);
        // bool(false)
        var_dump($allValidations);
        // array(4) {
        //     ['role'] => bool(true)
        //     ['role_2'] => bool(false)
        //     ['create-post'] => bool(true)
        //     ['edit-user'] => bool(false)
        // }

        /*
        $role = Role::findOrFail(1); // 获取给定权限
        dd($role);
        // 正常删除
        $role->delete();

        // 强制删除
        $role->users()->sync([]); // 删除关联数据
        $role->perms()->sync([]); // 删除关联数据

        $role->forceDelete(); // 不管透视表是否有级联删除都会生效
        */
    }
}
