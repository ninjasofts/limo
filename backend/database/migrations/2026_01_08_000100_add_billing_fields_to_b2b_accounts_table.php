<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('b2b_accounts', function (Blueprint $table) {
            $table->enum('billing_cycle', ['monthly'])->default('monthly')->after('status');
            $table->unsignedSmallInteger('net_days')->default(14)->after('billing_cycle'); // Net 14 default
            $table->unsignedTinyInteger('invoice_day')->nullable()->after('net_days'); // if set, e.g. 1st of month
        });
    }

    public function down(): void
    {
        Schema::table('b2b_accounts', function (Blueprint $table) {
            $table->dropColumn(['billing_cycle', 'net_days', 'invoice_day']);
        });
    }
};
