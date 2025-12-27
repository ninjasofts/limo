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
        Schema::create('booking_form_agreements', function (Blueprint $table) {
    $table->id();

    $table->foreignId('booking_form_id')->constrained()->cascadeOnDelete();

    $table->string('label');
    $table->text('content')->nullable();
    $table->boolean('required')->default(true);

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_form_agreements');
    }
};
