<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlantTypeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id'=>1,'name'=>'Excavator','status'=>1,'created_at'=>'2023-07-14 19:16:51','updated_at'=>'2023-07-25 16:21:55'],
            ['id'=>2,'name'=>'Roller','status'=>1,'created_at'=>'2023-07-14 19:16:51','updated_at'=>'2023-07-14 19:16:51'],
            ['id'=>3,'name'=>'Grader','status'=>1,'created_at'=>'2023-07-14 19:16:51','updated_at'=>'2023-07-14 19:16:51'],
            ['id'=>4,'name'=>'Watercart','status'=>1,'created_at'=>'2023-07-14 19:16:51','updated_at'=>'2023-07-14 19:16:51'],
            ['id'=>5,'name'=>'Loader','status'=>1,'created_at'=>'2023-07-14 19:16:51','updated_at'=>'2023-07-14 19:16:51'],
            ['id'=>6,'name'=>'Skidsteer','status'=>1,'created_at'=>'2023-07-14 19:16:51','updated_at'=>'2023-07-14 19:16:51'],
            ['id'=>7,'name'=>'Tipper Truck','status'=>1,'created_at'=>'2023-07-14 19:16:51','updated_at'=>'2023-07-14 19:16:51'],
            ['id'=>8,'name'=>'Stabiliser','status'=>1,'created_at'=>'2023-07-14 19:16:51','updated_at'=>'2023-07-14 19:16:51'],
            ['id'=>9,'name'=>'Jet Patcher','status'=>1,'created_at'=>'2023-07-14 19:16:51','updated_at'=>'2023-07-14 19:16:51'],
            ['id'=>10,'name'=>'Flocon Asphalt','status'=>1,'created_at'=>'2023-07-14 19:16:51','updated_at'=>'2023-07-14 19:16:51'],
            ['id'=>11,'name'=>'Trucks','status'=>1,'created_at'=>'2023-07-14 19:16:51','updated_at'=>'2023-07-14 19:16:51'],
            ['id'=>12,'name'=>'Trailers','status'=>1,'created_at'=>'2023-07-14 19:16:51','updated_at'=>'2023-07-14 19:16:51'],
            ['id'=>13,'name'=>'Minor Plant','status'=>1,'created_at'=>'2023-07-14 19:16:51','updated_at'=>'2023-07-14 19:16:51'],
        ];

        DB::table('plant_types')->insert($data);
    }
}