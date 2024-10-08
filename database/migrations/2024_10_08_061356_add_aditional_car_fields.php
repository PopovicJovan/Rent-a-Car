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
        Schema::table('cars', function (Blueprint $table) {
            $table->integer('fuelConsumption')->default('0');
            $table->string('gear')->default("manual");
            $table->integer('passengers')->default("5");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn('fuelConsumption');
            $table->dropColumn('gear');
            $table->dropColumn('passengers');
        });
    }
};
