<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus foreign key fleet_id lalu jadikan nullable (armada tidak dipakai lagi).
        Schema::table('schedules', function (Blueprint $table) {
            try {
                $table->dropForeign(['fleet_id']);
            } catch (\Throwable $e) {
                // Abaikan jika FK sudah tidak ada (mis. SQLite).
            }
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('fleet_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('fleet_id')->nullable(false)->change();
        });
    }
};
