<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('isbn');
            $table->bigInteger('publisher_id')->unsigned();
            $table->bigInteger('country_id')->unsigned();
            $table->integer('number_of_pages');
            $table->date('release_date');
            $table->timestamps();

            $table->foreign('publisher_id')->references('id')->on('publishers');
            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
//            $table->dropForeign('books_publisher_id_foreign');
//            $table->dropForeign('books_country_id_foreign');
            $table->dropForeign(['publisher_id']);
            $table->dropForeign(['country_id']);
        });

        Schema::dropIfExists('books');
    }
}
