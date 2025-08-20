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
        Schema::create('current_financial_accounts', function (Blueprint $table) {
            $table->id();
			$table->integer('sl_no')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('account_owner')->nullable()->comment('1=Husband, 2 =Wife, 3=Joint');
			$table->string('account_title')->nullable();
			$table->integer('tax_qualification')->nullable()->comment('1=IRA ,2=non-qualified');
			$table->double('account_value', 15, 2)->nullable();
			$table->tinyInteger('status')->default(1)->comment('1=active ,0=inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('current_financial_accounts');
    }
};
