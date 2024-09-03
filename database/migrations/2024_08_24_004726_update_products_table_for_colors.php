<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsTableForColors extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_colors')->default(false)->after('count');
            $table->renameColumn('count', 'quantity');
            $table->unsignedInteger('quantity')->nullable()->change();
            $table->decimal('price', 8, 2)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('quantity', 'count');
            $table->integer('count')->nullable()->change();
            $table->decimal('price', 8, 2)->default(0.00)->change();
            $table->dropColumn('has_colors');
        });
    }

}
