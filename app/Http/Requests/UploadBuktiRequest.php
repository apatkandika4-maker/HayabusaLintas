<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadBuktiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'metode' => ['required', 'in:qris,transfer,cash'],
            'bukti_bayar' => ['required_unless:metode,cash', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'metode.required' => 'Metode pembayaran wajib dipilih.',
            'metode.in' => 'Metode pembayaran tidak valid.',
            'bukti_bayar.required_unless' => 'Bukti pembayaran wajib diunggah untuk QRIS/transfer.',
            'bukti_bayar.image' => 'Bukti pembayaran harus berupa gambar.',
            'bukti_bayar.max' => 'Ukuran bukti pembayaran maksimal 2MB.',
        ];
    }
}
