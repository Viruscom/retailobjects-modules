<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetailObjectTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retail_object_translation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('retail_object_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('announce')->nullable();
            $table->text('description')->nullable();
            $table->string('url');
            $table->string('address')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->string('phone')->nullable()->default(null);
            $table->string('map_iframe')->nullable()->default(null);
            $table->boolean('visible')->default(true);
            $table->timestamps();

            $table->unique(['retail_object_id', 'locale']);
            $table->foreign('retail_object_id')->references('id')->on('retail_objects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retail_object_translation');
    }
}
