<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Url;
use App\Xpath;
use App\Content;

class CreateParserTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Url::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->string('url')->unique();
        });

        Schema::create(Xpath::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('url_id');
            $table->string('xpath')->unique();

            $table->foreign('url_id')
                ->references('id')->on(Url::TABLE)
                ->onDelete('cascade');
        });

        Schema::create(Content::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('xpath_id');
            $table->text('content');

            $table->foreign('xpath_id')
                ->references('id')->on(Xpath::TABLE)
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Content::TABLE);
        Schema::dropIfExists(Xpath::TABLE);
        Schema::dropIfExists(Url::TABLE);
    }
}
