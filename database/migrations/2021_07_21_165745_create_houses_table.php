<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('');
            $table->string('distict')->default('');
            $table->string('address')->default('');
            $table->string('lat')->default('');
            $table->string('lng')->default('');
            $table->string('houseHolds')->default('');
            $table->string('persons')->default('');
            $table->string('floors')->default('');
            $table->string('progress')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('houses');
    }
}
