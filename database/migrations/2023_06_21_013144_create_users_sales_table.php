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
        Schema::create('users_sales_document', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->enum('type', ['Motorcycle', 'Car'])->nullable();
            $table->string('skck')->nullable();
            $table->string('ktp')->nullable();
            $table->string('sim')->nullable();
            $table->string('stnk')->nullable();
            $table->string('pas_foto')->nullable();
            $table->string('sertifikat')->nullable();
            $table->string('agreement')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_sales_document');
    }
};
