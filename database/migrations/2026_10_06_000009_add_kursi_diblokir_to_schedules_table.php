<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Nomor kursi yang ditandai admin sebagai terisi (walk-in / pesan via WA),
            // di luar booking yang tercatat di web. Disimpan sebagai JSON array of int.
            $table->json('kursi_diblokir')->nullable()->after('sisa_kursi');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('kursi_diblokir');
        });
    }
};
