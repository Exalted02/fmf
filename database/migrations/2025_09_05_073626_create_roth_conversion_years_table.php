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
        Schema::create('roth_conversion_years', function (Blueprint $table) {
            $table->id();
			$table->integer('sl_no')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('year')->nullable();
			$table->tinyInteger('rmd_age')->default(0)->comment('1=73/75');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roth_conversion_years');
    }
};
