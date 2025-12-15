<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class StoreKebunRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lokasi' => 'required|string|max:255',
            'luas_kebun' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',

            'tanam' => 'nullable|array',
            'tanam.jenis_tanaman' => 'required_with:tanam|string|max:255',
            'tanam.jumlah_tanaman' => 'required_with:tanam|integer|min:1',
            'tanam.tanggal_tanam' => 'required_with:tanam|date',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
