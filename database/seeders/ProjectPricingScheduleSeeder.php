<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectPricingScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Project 45
            ['id'=>111,'project_id'=>45,'code_id'=>null,'item'=>'1','description'=>'Insurance Contribution','qty'=>1,'unit'=>'ITEM','rate'=>10,'amount'=>10,'code'=>'I','created_at'=>'2024-09-16 06:53:41','updated_at'=>'2024-09-16 06:53:41'],
            ['id'=>112,'project_id'=>45,'code_id'=>null,'item'=>'1.1','description'=>'Preliminaries','qty'=>1,'unit'=>'ITEM','rate'=>10,'amount'=>10,'code'=>'P','created_at'=>'2024-09-16 06:53:41','updated_at'=>'2024-09-16 06:53:41'],
            ['id'=>113,'project_id'=>45,'code_id'=>null,'item'=>'1.1','description'=>'Earthworks and Demo','qty'=>1,'unit'=>'ITEM','rate'=>10,'amount'=>10,'code'=>'1','created_at'=>'2024-09-16 06:53:41','updated_at'=>'2024-09-16 06:53:41'],
            ['id'=>114,'project_id'=>45,'code_id'=>null,'item'=>'1.1','description'=>'Electrical & Sports Lighting Works','qty'=>1,'unit'=>'M','rate'=>10,'amount'=>10,'code'=>'2','created_at'=>'2024-09-16 06:53:41','updated_at'=>'2024-09-16 06:53:41'],
            ['id'=>115,'project_id'=>45,'code_id'=>null,'item'=>'1.1','description'=>'Stormwater Installation','qty'=>1,'unit'=>'ITEM','rate'=>10,'amount'=>10,'code'=>'3','created_at'=>'2024-09-16 06:53:41','updated_at'=>'2024-09-16 06:53:41'],
            ['id'=>116,'project_id'=>45,'code_id'=>null,'item'=>'1.1','description'=>'Portable Water & Irrigation Water','qty'=>1,'unit'=>'ITEM','rate'=>10,'amount'=>10,'code'=>'4','created_at'=>'2024-09-16 06:53:41','updated_at'=>'2024-09-16 06:53:41'],
            ['id'=>117,'project_id'=>45,'code_id'=>null,'item'=>'1.1','description'=>'Sewer & Pump Station','qty'=>1,'unit'=>'ITEM','rate'=>10,'amount'=>10,'code'=>'5','created_at'=>'2024-09-16 06:53:41','updated_at'=>'2024-09-16 06:53:41'],
            ['id'=>118,'project_id'=>45,'code_id'=>null,'item'=>'1.1','description'=>'Road & Carparks','qty'=>1,'unit'=>'ITEM','rate'=>10,'amount'=>10,'code'=>'6','created_at'=>'2024-09-16 06:53:41','updated_at'=>'2024-09-16 06:53:41'],
            ['id'=>119,'project_id'=>45,'code_id'=>null,'item'=>'1.1','description'=>'CTQR Paths','qty'=>1,'unit'=>'ITEM','rate'=>10,'amount'=>10,'code'=>'7','created_at'=>'2024-09-16 06:53:41','updated_at'=>'2024-09-16 06:53:41'],
            ['id'=>120,'project_id'=>45,'code_id'=>null,'item'=>'1.1','description'=>'Soccer Pitch Prep & Irrigation','qty'=>1,'unit'=>'ITEM','rate'=>10,'amount'=>10,'code'=>'8','created_at'=>'2024-09-16 06:53:41','updated_at'=>'2024-09-16 06:53:41'],
            ['id'=>121,'project_id'=>45,'code_id'=>null,'item'=>'1.1','description'=>'Landscaping & Signage','qty'=>1,'unit'=>'ITEM','rate'=>10,'amount'=>10,'code'=>'9','created_at'=>'2024-09-16 06:53:41','updated_at'=>'2024-09-16 06:53:41'],

            // Project 46
            ['id'=>144,'project_id'=>46,'code_id'=>null,'item'=>'1','description'=>'Insurance Contribution','qty'=>1,'unit'=>'ITEM','rate'=>10,'amount'=>10,'code'=>'I','created_at'=>'2024-09-16 07:09:02','updated_at'=>'2024-09-16 07:09:02'],
            ['id'=>145,'project_id'=>46,'code_id'=>null,'item'=>'1.1','description'=>'Preliminaries','qty'=>1,'unit'=>'ITEM','rate'=>10,'amount'=>10,'code'=>'P','created_at'=>'2024-09-16 07:09:02','updated_at'=>'2024-09-16 07:09:02'],
            // you can continue same pattern...
        ];

        DB::table('project_pricing_schedules')->insert($data);
    }
}