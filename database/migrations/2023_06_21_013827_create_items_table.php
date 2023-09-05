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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_category');
            $table->unsignedBigInteger('id_distributor');
            $table->string('name');
            $table->bigInteger('distributor_price');
            $table->bigInteger('sale_price');
            $table->enum('type', ['Pack', 'Carton'])->default('Pack');
            $table->timestamps();

            $table->foreign('id_category')->references('id')->on('category')->onDelete('cascade');
            $table->foreign('id_distributor')->references('id')->on('distributor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
