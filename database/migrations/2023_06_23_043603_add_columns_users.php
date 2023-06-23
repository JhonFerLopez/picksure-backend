<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
<<<<<<< HEAD
            $table->string('phone', 15);
            $table->string('country', 20);
            $table->string('city', 20);
=======
            $table->integer('phone');
            $table->string('country');
            $table->string('city');
>>>>>>> 1919510 (auth)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
