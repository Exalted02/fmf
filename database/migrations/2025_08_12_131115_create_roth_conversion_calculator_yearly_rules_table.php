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
        Schema::create('roth_conversion_calculator_yearly_rules', function (Blueprint $table) {
            $table->id();
			$table->integer('roth_id')->nullable();
			$table->double('investment_amount', 8, 2)->nullable();
			$table->integer('bonus')->nullable();
			$table->integer('assumed_return')->nullable();
			$table->tinyInteger('status')->default(1)->comment('1=active ,0=inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roth_conversion_calculator_yearly_rules');
    }
};
