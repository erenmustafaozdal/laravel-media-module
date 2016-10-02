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

                $table->boolean('datatable_filter')->default(1);
                $table->boolean('datatable_tools')->default(1);
                $table->boolean('datatable_fast_add')->default(1);
                $table->boolean('datatable_group_action')->default(1);
                $table->boolean('datatable_detail')->default(1);
                $table->boolean('description_is_editor')->default(0);
                $table->boolean('config_propagation')->default(0); // ayarlar alt kategorilere yayılsın mı
                $table->integer('photo_width')->default(0); // photo width for aspect ratio
                $table->integer('photo_height')->default(0); // photo height for aspect ratio

                // kategoriye bağlı olarak medyalar fotoğraf mı? video mu?
                $table->string('type')->default('photo'); // [photo,video,mixed]
                $table->boolean('has_description')->default(0);

                $table->string('name');
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('media_category_thumbnails')) {
            Schema::create('media_category_thumbnails', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('category_id')->unsigned();
                $table->foreign('category_id')->references('id')->on('media_categories')->onDelete('cascade');

                $table->string('slug');
                $table->integer('photo_width')->nullable();
                $table->integer('photo_height')->nullable();

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('media_category_columns')) {
            Schema::create('media_category_columns', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('category_id')->unsigned();
                $table->foreign('category_id')->references('id')->on('media_categories')->onDelete('cascade');

                $table->string('name');
                $table->string('type')->default('text');

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('medias')) {
            Schema::create('medias', function (Blueprint $table) {
                $table->increments('id');

                $table->string('title');
                $table->longText('description');
                $table->boolean('is_publish')->default(0);
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('media_media_category_column')) {
            Schema::create('media_media_category_column', function (Blueprint $table) {
                $table->integer('column_id')->unsigned()->index();
                $table->foreign('column_id')->references('id')->on('media_category_columns')->onDelete('cascade');

                $table->integer('media_id')->unsigned()->index();
                $table->foreign('media_id')->references('id')->on('medias')->onDelete('cascade');

                $table->string('value');

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('media_media_category')) {
            Schema::create('media_media_category', function (Blueprint $table) {
                $table->integer('media_category_id')->unsigned()->index();
                $table->foreign('media_category_id')->references('id')->on('media_categories')->onDelete('cascade');

                $table->integer('media_id')->unsigned()->index();
                $table->foreign('media_id')->references('id')->on('medias')->onDelete('cascade');
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
        Schema::drop('media_media_category');
        Schema::drop('media_media_category_column');
        Schema::drop('media_category_columns');
        Schema::drop('media_category_thumbnails');
        Schema::drop('medias');
        Schema::drop('media_categories');
    }
}
