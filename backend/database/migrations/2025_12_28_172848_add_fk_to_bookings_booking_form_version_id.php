<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        $table
            ->foreign('booking_form_version_id')
            ->references('id')
            ->on('booking_form_versions')
            ->restrictOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings_booking_form_version_id', function (Blueprint $table) {
            //
        });
    }
};
