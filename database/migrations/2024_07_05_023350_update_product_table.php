<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('size'); // Видалення поля size
            $table->integer('height')->after('count')->nullable(); // Додавання поля height
            $table->integer('length')->after('height')->nullable(); // Додавання поля height
            $table->integer('width')->after('length')->nullable(); // Додавання поля width
            $table->integer('depth')->after('width')->nullable(); // Додавання поля depth
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('size')->nullable(); // Відновлення поля size
            $table->dropColumn('height'); // Видалення поля height
            $table->dropColumn('length'); // Видалення поля height
            $table->dropColumn('width'); // Видалення поля width
            $table->dropColumn('depth'); // Видалення поля depth
        });
    }
}
