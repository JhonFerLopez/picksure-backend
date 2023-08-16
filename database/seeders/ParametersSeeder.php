<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Parameters;

class ParametersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        if (Parameters::count() == 0) {

            Parameters::create([
                'name_parameter'         => 'max_upload_images',
                'value_parameter'        => '5',
            ]);

            Parameters::create([
                'name_parameter'         => 'max_pautantes',
                'value_parameter'        => '3',
            ]);
        }
    }
}
