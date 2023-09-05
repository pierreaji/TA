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
        Schema::table('users_sales_document', function (Blueprint $table) {
            $table->tinyInteger('approved_status')->default(0);
            $table->boolean('is_renew')->default(false);
            $table->text('reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_sales_document', function (Blueprint $table) {
            //
        });
    }
};
