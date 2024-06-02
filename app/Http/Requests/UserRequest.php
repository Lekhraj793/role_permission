<?php

namespace App\Http\Requests;

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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(isset($this->id) && !empty($this->id)) {
            return [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.$this->id,
                'password' => 'required|min:5',
                'roles' => 'required',
                'phone' => 'required|numeric|unique:users,phone,'.$this->id,
            ];
        } else {
            return [
                'name'=>'required',
                'password'=>'required|min:5',
                'roles'=>'required',
                'phone' => 'required|numeric|unique:users',
                'email' => 'required|email|unique:users|max:255',
            ];
        }
    }
}
