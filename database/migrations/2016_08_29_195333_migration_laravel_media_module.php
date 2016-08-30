<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrationLaravelMediaModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( ! Schema::hasTable('media_categories')) {
            Schema::create('media_categories', function (Blueprint $table) {
                $table->increments('id');

                $table->integer('parent_id')->nullable();
                $table->integer('lft')->nullable();
                $table->integer('rgt')->nullable();
                $table->integer('depth')->nullable();

                // kategoriye bağlı olarak medyalar fotoğraf mı? video mu?
                $table->string('type')->default('photo'); // [photo,video]

                $table->string('name');
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('medias')) {
            Schema::create('medias', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('category_id')->unsigned();
                $table->foreign('category_id')->references('id')->on('media_categories')->onDelete('cascade');

                $table->string('title');
                $table->string('description');
                $table->boolean('is_publish')->default(0);
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('media_photos')) {
            Schema::create('media_photos', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('media_id')->unsigned();
                $table->foreign('media_id')->references('id')->on('medias')->onDelete('cascade');

                $table->string('photo');

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('media_videos')) {
            Schema::create('media_videos', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('media_id')->unsigned();
                $table->foreign('media_id')->references('id')->on('medias')->onDelete('cascade');

                $table->string('video');

                $table->engine = 'InnoDB';
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('media_videos');
        Schema::drop('media_photos');
        Schema::drop('medias');
        Schema::drop('media_categories');
    }
}
