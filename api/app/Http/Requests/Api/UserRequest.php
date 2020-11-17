<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return false;
        return true;
    }

    public function rules()
    {
        switch ($this->method()) {
            case 'GET': {
                    return [
                        'id' => ['required,exists:shop_user,id']
                    ];
                }
            case 'POST': {
                    return [
                        'name' => ['required', 'max:12', 'unique:users,name'],
                        'password' => ['required', 'max:16', 'min:6','confirmed'],
                        'password_confirmation' => ['required'],
                        'email' => ['required', 'email','unique:users,email']
                    ];
                }
            case 'PUT':
            case 'PATCH':
            case 'DELETE':
            default: {
                    return [];
                }
        }
    }

    /**
     * 获取已定义验证规则的错误消息。
     */
    public function messages()
    {
        return [
            'id.required' => '用户ID必须填写',
            'id.exists' => '用户不存在',
            'name.unique' => '用户名已经存在',
            'name.required' => '用户名不能为空',
            'name.max' => '用户名最大长度为12个字符',
            'password.required' => '密码不能为空',
            'password.max' => '密码长度不能超过16个字符',
            'password.min' => '密码长度不能小于6个字符',
            'password_confirmation.required' => '验证密码不能为空',
            'password.confirmed' => '两次密码不一致',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式有误',
            'email.unique' => '邮箱已经存在',
        ];
    }
    /**
     * 获取验证错误的自定义属性。
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //'email' => 'email address',
        ];
    }
}
