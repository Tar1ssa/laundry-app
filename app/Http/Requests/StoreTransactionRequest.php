<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize()
    {
        // sesuaikan jika perlu cek permission
        return true;
    }

    public function rules()
    {
        return [
            'id_customer' => 'required|integer',
            'order_date' => 'required|date',
            'id_service' => 'required|array|min:1',
            'id_service.*' => 'required|integer|distinct',
            'qty' => 'required|array',
            'qty.*' => 'required|integer|min:1',
            'subtotali' => 'required|array',
            'subtotali.*' => 'required|numeric|min:0',
            // tambahan aturan yang relevan
            'total' => 'required|numeric|min:0',
            'order_pay' => 'nullable|numeric|min:0',
            'order_change' => 'nullable|numeric|min:0',
            'note' => 'sometimes|array',
            'note.*' => 'nullable|string',
            'trans_code' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'id_customer.required' => 'Nama customer tidak dapat kosong.',
            'order_date.required' => 'Tanggal order tidak dapat kosong.',
            // tambahkan pesan lain bila perlu
        ];
    }
}
