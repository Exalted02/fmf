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
        Schema::table('current_financial_accounts', function (Blueprint $table) {
           $table->tinyInteger('tax_free_type')->nullable()->comment('1=percent 2=dollar value')->after('account_value');
           $table->string('tax_free_percent')->nullable()->after('annual_income_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('current_financial_accounts', function (Blueprint $table) {
            //
        });
    }
};
