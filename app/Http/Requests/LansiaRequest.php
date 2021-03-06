<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LansiaRequest extends FormRequest
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
            //
            'email'         => 'required|unique',
            'posyandu_kode' => 'required',
            'lansia_kode'   => 'required',
            'lansia_nik'    => 'required',
        ];
    }
}
