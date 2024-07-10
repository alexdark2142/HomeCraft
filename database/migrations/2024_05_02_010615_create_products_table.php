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
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedTinyInteger('category_id');
                $table->unsignedTinyInteger('subcategory_id');
                $table->unsignedTinyInteger('count');
                $table->string('material')->nullable();
                $table->integer('height')->nullable();
                $table->integer('length')->nullable();
                $table->integer('width')->nullable();
                $table->integer('depth')->nullable();
                $table->decimal('price', 8, 2);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::dropIfExists('products');
        }
    }
};
