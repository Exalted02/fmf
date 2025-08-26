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
        Schema::table('current_financial_accounts', function (Blueprint $table){
           $table->integer('age_income_start')->nullable()->after('tax_qualification');
           $table->double('annual_income_value', 15, 2)->nullable()->after('account_value');
			
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fmf_current_financial_accounts', function (Blueprint $table) {
            //
        });
    }
};
