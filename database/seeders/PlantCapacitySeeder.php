<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlantCapacitySeeder extends Seeder
{
    public function run(): void
    {
        $data = [

            // Excavator (plant_type_id = 1)
            1 => ['1.7T', '3T', '5T', '6T', '8T', '13T', '14T', '16T', '20T', '21T', '22T', '23T', '26T', '28T', '30T', 'Compaction Wheel'],

            // Roller (2)
            2 => ['DPU', '1.5T', '2T', '3T', '4T', '7T', '8T', '10T', '12T', '14T', '815 Compactor', 'Multi Tyred', '2 Tier'],

            // Grader (3)
            3 => ['Small', '120 Size', '140 Size'],

            // Watercart (4)
            4 => ['1000L Trailer', '5-8kl', '8-12kl', '12-20kl'],

            // Loader (5)
            5 => ['0-1.5m3', '1.5-3m3', '3m3-5m3'],

            // Skidsteer (6)
            6 => ['Wheeled', 'Tracked'],

            // Tipper Truck (7)
            7 => ['Semi Tipper', 'Truck & Dog', 'Truck & Quad', 'Tandem', '8T Tipper', '6T Tipper', '3.5T Tipper', '2T Tipper'],

            // Stabiliser (8)
            8 => ['1m', '1.5m', '2m'],

            // Jet Patcher (9)
            9 => ['2000L', '3000L'],

            // Flocon Asphalt (10)
            10 => ['6T', '8T', '12T'],

            // Trucks (11)
            11 => ['Flat Bed Truck', 'Civil Truck', 'Concrete Truck'],

            // Trailers (12)
            12 => ['Test Trailer', 'Float Trailer', 'Cage Trailer'],

            // Minor Plant (13)
            13 => ['DPU', 'Leg Rammer', 'Generator', 'Vibrating Plate', 'Laser Level', 'Pipe Laser', 'Survey Rover', 'UTS', 'Quick Cut', 'Fuel Tanks', 'Containers', 'Quick Fills'],
        ];

        $insertData = [];

        foreach ($data as $plantTypeId => $capacities) {
            foreach ($capacities as $name) {
                $insertData[] = [
                    'plant_type_id' => $plantTypeId,
                    'name' => $name,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('plant_capacities')->insert($insertData);
    }
}
