<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImageProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('imageproducts')->insert([
            [
                "name" => "",
                "img_url" => "storage/posts/post1.jpg",
                "user_id" => 1,
            ],
            [
                "name" => "",
                "img_url" => "storage/posts/post2.jpg",
                "user_id" => 1,
            ]
        ]);
    }
}
