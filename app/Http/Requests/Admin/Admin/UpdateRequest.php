<?php

namespace App\Http\Requests\Admin\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
      
        return [
            'name' => 'required|max:100',
            'phone' => 'required|unique:admins,phone,' . $this->admin,
            'password' => 'nullable|min:8',
            'image' => 'mimes:jpeg,jpg,png,gif',
        ];
    }
}
