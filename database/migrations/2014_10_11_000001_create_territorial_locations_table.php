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
		Schema::create('territorial_locations', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->bigIncrements('id');
			$table->string('name');
			$table->string('code_iso')->nullable();
		  $table->unsignedInteger('type_location_id');
			$table->foreign('type_location_id')->references('id')->on('type_locations');
			$table->unsignedBigInteger('territorial_locations_parent_id')->unsigned()->nullable()->default(null);
			$table->foreign('territorial_locations_parent_id')->references('id')->on('territorial_locations');
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
		Schema::dropIfExists('territorial_locations');
	}
};
