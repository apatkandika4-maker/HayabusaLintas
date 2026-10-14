<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'origin' => ['required', 'integer', 'exists:cities,id'],
            'dest' => ['required', 'integer', 'exists:cities,id', 'different:origin'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'origin.required' => 'Kota asal wajib dipilih.',
            'origin.exists' => 'Kota asal tidak valid.',
            'dest.required' => 'Kota tujuan wajib dipilih.',
            'dest.exists' => 'Kota tujuan tidak valid.',
            'dest.different' => 'Kota tujuan harus berbeda dengan kota asal.',
            'tanggal.required' => 'Tanggal berangkat wajib diisi.',
            'tanggal.date' => 'Tanggal berangkat tidak valid.',
            'tanggal.after_or_equal' => 'Tanggal berangkat tidak boleh hari yang sudah lewat.',
        ];
    }
}
