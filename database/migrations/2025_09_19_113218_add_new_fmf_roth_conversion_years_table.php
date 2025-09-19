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
            $table->integer('wife_roth_year')->nullable()->after('year');
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
