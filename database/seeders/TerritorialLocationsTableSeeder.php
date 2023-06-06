<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\TypeLocations;
use App\Models\TerritorialLocations;

class TerritorialLocationsTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file.
	 *
	 * @return void
	 */
	public function run()
	{
		if (TerritorialLocations::count() == 0) {
			$Pais = TypeLocations::where('name', 'País')->firstOrFail();//País
			$Departamento = TypeLocations::where('name', 'Departamento')->firstOrFail();//Departamento
			$Municipio = TypeLocations::where('name', 'Municipio')->firstOrFail();//Municipio

			TerritorialLocations::insert([
				[
					'name'							=> 'Colombia',
					'type_location_id'	=> $Pais->id,
				],
				[
					'name'							=> 'USA',
					'type_location_id'	=> $Pais->id,
				],
				[
					'name'							=> 'España',
					'type_location_id'	=> $Pais->id,
				],
				[
					'name'							=> 'Valle del cauca',
					'type_location_id'	=> $Departamento->id,
				],
				[
					'name'							=> 'Cali',
					'type_location_id'	=> $Municipio->id,
				]
			]);
		}
	}
}
