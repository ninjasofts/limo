<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('b2b_account_id')->nullable()->constrained()->nullOnDelete();

            $table->string('invoice_number')->unique();

            $table->enum('status', ['draft','issued','paid','void','overdue'])->default('draft');

            $table->string('currency', 3)->default('USD');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            $table->timestamp('issued_at')->nullable();
            $table->timestamp('due_at')->nullable();

            $table->string('pdf_path')->nullable(); // storage path

            $table->timestamps();

            $table->index(['booking_id', 'status']);
            $table->index(['b2b_account_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
