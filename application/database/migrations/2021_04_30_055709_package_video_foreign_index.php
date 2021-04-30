<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PackageVideoForeignIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_video', function (Blueprint $table) {
            $table->foreign('video_id')->references('id')->on('videos');
            $table->foreign('package_id')->references('id')->on('packages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_video', function (Blueprint $table) {
            $table->dropForeign('video_id');
            $table->dropForeign('package_id');
        });
    }
}
