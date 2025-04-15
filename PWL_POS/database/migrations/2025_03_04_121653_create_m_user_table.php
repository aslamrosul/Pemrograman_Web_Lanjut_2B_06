<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('m_user', function (Blueprint $table) {
            $table->id('user_id');
            $table->unsignedBigInteger('level_id');
            $table->string('username', 20);
            $table->string('nama', 100);
            $table->string('password', 255);
            $table->string('profile_photo')->nullable();
            $table->timestamps();

            $table->foreign('level_id')->references('level_id')->on('m_level');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_user');
    }
};
