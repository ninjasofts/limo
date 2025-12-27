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
        Schema::create('booking_form_fields', function (Blueprint $table) {
    $table->id();

    $table->foreignId('booking_form_id')->constrained()->cascadeOnDelete();

    $table->string('label');
    $table->string('name');
    $table->enum('type', [
        'text', 'email', 'number', 'textarea',
        'select', 'checkbox', 'radio', 'date', 'time'
    ]);

    $table->json('options')->nullable(); // for select, radio
    $table->boolean('required')->default(false);
    $table->integer('sort_order')->default(0);

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_form_fields');
    }
};
