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
        Schema::create('b2b_accounts', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->id();
    $table->string('company_name');
    $table->enum('company_type', ['corporate', 'travel_agency'])->default('corporate');

    $table->string('vat_number')->nullable();
    $table->string('billing_email')->nullable();

    $table->enum('discount_type', ['none', 'percent', 'fixed'])->default('none');
    $table->decimal('discount_value', 10, 2)->default(0);

    $table->decimal('credit_limit', 10, 2)->default(0);
    $table->string('currency', 3)->default('DKK');

    $table->enum('status', ['active', 'blocked'])->default('active');

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('b2b_accounts');
    }
};
