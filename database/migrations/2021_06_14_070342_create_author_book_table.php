<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorsBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors_books', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('book_id')->unsigned();
            $table->bigInteger('author_id')->unsigned();
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('books');
            $table->foreign('author_id')->references('id')->on('authors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authors_books', function (Blueprint $table) {
            $table->dropForeign('authors_books_book_id_foreign');
            $table->dropForeign('authors_books_author_id_foreign');
            $table->dropIfExists();
        });
    }
}
