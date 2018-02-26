<?php

namespace App\Http\Requests;
// 表单请求验证
use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 权限验证
     * @return bool
     */
    public function authorize()
    {
//        return false;
        return true; // 意味着所有权限都可以通过
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' .Auth::id(),
            'email' => 'required|email',
            'introduction' => 'max:80',
        ];
    }

    /**
     * @return array
     * 表单请求验证 消息提示信息
     */
    public function messages()
    {
        return [
            'name.unique' => '用户名已被占用，请重新填写',
            'name.regex' => '用户名只支持中英文、数字、横杆和下划线',
            'name.between' => '用户名必须介于 3 - 25 个字符之间',
            'name.required' => '用户名不能为空',
        ];
    }
}
