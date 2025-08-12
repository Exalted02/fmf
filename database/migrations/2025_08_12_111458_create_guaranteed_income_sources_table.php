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
        Schema::create('guaranteed_income_sources', function (Blueprint $table) {
            $table->id();
			$table->integer('sl_no')->nullable();
			$table->integer('user_id')->nullable();
			$table->string('client_name')->nullable();
			$table->double('income_amount', 8, 2)->nullable();
			$table->integer('type')->nullable();
			$table->integer('frequency')->nullable();
			$table->double('cola', 8, 2)->nullable()->comment('Cost of Living Adjustment in %');
			$table->integer('start_age')->nullable();
			$table->integer('end_age')->nullable();
			$table->tinyInteger('status')->default(1)->comment('1=active ,0=inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guaranteed_income_sources');
    }
};
