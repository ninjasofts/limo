<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('b2b_invoice_id')
                ->nullable()
                ->after('b2b_account_id')
                ->constrained('invoices')
                ->nullOnDelete();

            $table->timestamp('billed_at')->nullable()->after('b2b_invoice_id');

            $table->index(['b2b_account_id', 'billed_at']);
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['billed_at']);
            $table->dropConstrainedForeignId('b2b_invoice_id');
        });
    }
};
