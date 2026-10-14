<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'nama_penumpang' => ['required', 'string', 'max:100'],
            'hp' => ['required', 'string', 'min:9', 'max:20'],
            'alamat_jemput' => ['nullable', 'string', 'max:255'],
            'shareloc' => ['nullable', 'string', 'max:500'],
            'jumlah_kursi' => ['required', 'integer', 'min:1', 'max:10'],
            'no_kursi' => ['nullable', 'array'],
            'no_kursi.*' => ['integer', 'min:1'],
            'metode' => ['nullable', 'in:cash'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Pembayaran hanya bayar di tempat (tanpa payment gateway).
        $this->merge(['metode' => 'cash']);
    }

    public function messages(): array
    {
        return [
            'schedule_id.required' => 'Jadwal keberangkatan tidak valid.',
            'schedule_id.exists' => 'Jadwal keberangkatan tidak ditemukan.',
            'nama_penumpang.required' => 'Nama penumpang wajib diisi.',
            'hp.required' => 'Nomor HP wajib diisi.',
            'hp.min' => 'Nomor HP minimal 9 digit.',
            'jumlah_kursi.required' => 'Jumlah kursi wajib diisi.',
            'jumlah_kursi.min' => 'Minimal pesan 1 kursi.',
            'jumlah_kursi.max' => 'Maksimal 10 kursi per booking.',
            'no_kursi.array' => 'Nomor kursi tidak valid.',
            'metode.required' => 'Metode pembayaran wajib dipilih.',
            'metode.in' => 'Metode pembayaran tidak valid.',
        ];
    }
}
