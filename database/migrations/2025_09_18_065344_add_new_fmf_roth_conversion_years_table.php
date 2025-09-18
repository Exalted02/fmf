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
        Schema::table('roth_conversion_years', function (Blueprint $table) {
            $table->text('show_specific_year')->nullable()->after('rmd_age');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fmf_roth_conversion_years', function (Blueprint $table) {
            //
        });
    }
};
