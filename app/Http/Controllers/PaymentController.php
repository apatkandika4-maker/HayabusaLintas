<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadBuktiRequest;
use App\Models\Booking;

class PaymentController extends Controller
{
    public function upload(UploadBuktiRequest $request, string $kode)
    {
        $booking = Booking::where('kode', $kode)->firstOrFail();

        if (in_array($booking->status, ['verified', 'done', 'cancelled'], true)) {
            return redirect()->route('tiket', $booking->kode)
                ->with('gagal', 'Booking ini sudah tidak dapat diubah pembayarannya.');
        }

        $data = $request->validated();
        $update = ['metode' => $data['metode']];

        if ($request->hasFile('bukti_bayar')) {
            $update['bukti_bayar'] = $request->file('bukti_bayar')->store('bukti', 'public');
            $update['status'] = 'paid';
        }

        $booking->update($update);

        return redirect()->route('tiket', $booking->kode)
            ->with('sukses', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }
}
