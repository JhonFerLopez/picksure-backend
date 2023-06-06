<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\TypeLocations;

class TypeLocationsTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file.
	 *
	 * @return void
	 */
	public function run()
	{
		if (TypeLocations::count() == 0) {

			TypeLocations::create(['name'	=> 'País']);
			TypeLocations::create(['name'	=> 'Departamento']);
			TypeLocations::create(['name'	=> 'Municipio']);
			TypeLocations::create(['name'	=> 'Provincia']);
			TypeLocations::create(['name'	=> 'Estado']);
		}
	}
}
