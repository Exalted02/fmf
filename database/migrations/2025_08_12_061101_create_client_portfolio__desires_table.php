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
        Schema::create('client_portfolio_desires', function (Blueprint $table) {
            $table->id();
			$table->integer('user_id')->nullable();
			$table->string('client_name')->nullable();
			$table->integer('client_age')->nullable();
			$table->string('partner_name')->nullable();
			$table->integer('partner_age')->nullable();
			$table->double('current_portfolio_value', 15, 2)->nullable();
			$table->double('desired_gross_income_retirement', 8, 2)->nullable();
			$table->integer('desired_retirement_age')->nullable();
			$table->integer('COLA')->nullable()->comment('Cost of Living Adjustment');
			$table->integer('cola_age')->nullable()->comment('Age to Begin COLA Adjustment');
			$table->integer('assumed_return')->nullable();
			$table->string('RIPG')->nullable()->comment('1=Income,2=Tax Reduction,3=Legacy');
			$table->tinyInteger('status')->default(1)->comment('1=active ,0=inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_portfolio__desires');
    }
};
