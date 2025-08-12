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
        Schema::create('roth_conversion_calculators', function (Blueprint $table) {
            $table->id();
			$table->integer('sl_no')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('conversion_start_age')->nullable();
			$table->integer('conversion_finish_age')->nullable();
			$table->double('conversion_annual_fee', 8, 2)->nullable();
			$table->integer('rmd_start_age')->nullable();
			$table->integer('rmd_finish_age')->nullable();
			$table->integer('rmd_tax_free_income')->nullable();
			$table->tinyInteger('status')->default(1)->comment('1=active ,0=inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roth_conversion_calculators');
    }
};
