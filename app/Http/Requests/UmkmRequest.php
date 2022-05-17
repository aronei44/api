<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UmkmRequest extends FormRequest
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
            'nama' => 'required',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            'kampung' => 'required',
            'desa' => 'required',
            'alamat' => 'required',
            'deskripsi' => 'required',
            'bidang_usaha' => 'required',
            'no_hp' => 'required|numeric',
            'user_id' => 'nullable'
        ];
    }
}
