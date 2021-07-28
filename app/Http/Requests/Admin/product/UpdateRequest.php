<?php

namespace App\Http\Requests\Admin\product;

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
            'name' => 'required',
            'slug' => 'required',
             'image' => 'mimetypes:image/jpeg,image/png|max:2048',
            'image2' => 'max:10048',
            'branch_id' => 'required',
            'cate_id' => 'required',
            'price' => 'required|regex:/^\d*(\.\d{2})?$/',
            'competitive_price' => 'required|regex:/^\d*(\.\d{2})?$/',
            'short_desc' => 'required',
        ];
    }
    public function  messages()
    {
        return [
            'name.required' => 'Mời chọn tên người dùng!',
            'name.max' => 'tên người dùng không quá 100 ký tự!',
            'name.min' => 'tên người dùng  ít nhất 4 ký tự!',
            'phone_number.required' => 'Mời nhập số điện thoại',
            'phone_number.min' => 'số điện thoại ít nhất 9 chữ số',

            'phone_number.numeric' => 'số điện thoại không đúng',
            'profile.required' => 'Mời nhập mô tả người dùng!',
            'profile.max' => 'Mô tả  không quá 500 ký tự!',
            'profile.min' => 'Mô tả  ít nhất 4 ký tự!',
            'image.mimetypes' => 'Ảnh không đúng định dang:jpeg /png /jpg ',
            'image.max' => 'Kích thước ảnh tối đa 2048 kb',
        ];
    }
}
