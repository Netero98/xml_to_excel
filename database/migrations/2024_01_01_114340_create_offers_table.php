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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('id_foreign')->unique()->nullable(false);
            $table->text('name');
            $table->string('category');
            $table->string('sub_category');
            $table->string('sub_sub_category')->nullable(true);
            $table->text('url');
            $table->integer('price');
            $table->integer('oldprice')->nullable(true);
            $table->string('currency_id');
            $table->text('picture')->nullable(true);
            $table->text('vendor')->nullable(true);
            $table->boolean('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
