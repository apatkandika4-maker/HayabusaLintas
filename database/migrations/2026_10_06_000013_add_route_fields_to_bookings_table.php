<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Lepas foreign key schedule_id lalu jadikan nullable (booking kini berbasis rute).
        Schema::table('bookings', function (Blueprint $table) {
            try {
                $table->dropForeign(['schedule_id']);
            } catch (\Throwable $e) {
                // abaikan bila FK tidak ada (SQLite)
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('schedule_id')->nullable()->change();
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'route_id')) {
                $table->unsignedBigInteger('route_id')->nullable()->after('schedule_id');
            }
            if (! Schema::hasColumn('bookings', 'tanggal')) {
                $table->date('tanggal')->nullable()->after('route_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'route_id')) {
                $table->dropColumn('route_id');
            }
            if (Schema::hasColumn('bookings', 'tanggal')) {
                $table->dropColumn('tanggal');
            }
        });
    }
};
