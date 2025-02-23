<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::dropIfExists('lists');
}

public function down()
{
    Schema::create('lists', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('board_id');
        $table->json('card')->nullable();
        $table->timestamps();
    });
}

};
