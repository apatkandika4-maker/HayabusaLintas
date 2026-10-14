<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama_penumpang');
            $table->string('hp');
            $table->text('alamat_jemput')->nullable();
            $table->foreignId('schedule_id')->constrained('schedules')->cascadeOnDelete();
            $table->unsignedInteger('jumlah_kursi');
            $table->text('no_kursi')->nullable();
            $table->unsignedInteger('total_harga');
            $table->string('status')->default('pending');
            $table->string('bukti_bayar')->nullable();
            $table->string('metode')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
