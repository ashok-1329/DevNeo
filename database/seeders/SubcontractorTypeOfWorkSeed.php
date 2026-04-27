<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubcontractorTypeOfWork;

class SubcontractorTypeOfWorkSeed extends Seeder
{
    public function run(): void
    {
        $services = [
            ['id'=>1,'name'=>'Wet Hire - Plant & Operator Only','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>2,'name'=>'Vegetation Clearing','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>3,'name'=>'Concrete Kerb','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>4,'name'=>'Concrete Footpath','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>5,'name'=>'Concrete General','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>6,'name'=>'Electrical','created_at'=>'2023-07-07 21:50:50','updated_at'=>'2023-08-08 08:42:32'],
            ['id'=>7,'name'=>'NBN','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>8,'name'=>'Water & Sewer Reticulation','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>9,'name'=>'Hydro Vacuuming','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>10,'name'=>'Traffic Management','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>11,'name'=>'Asphalt','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>12,'name'=>'Spraysealing','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>13,'name'=>'Structures','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>14,'name'=>'Craneage','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>15,'name'=>'Concrete Cutting','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>16,'name'=>'Asphalt Cutting','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>17,'name'=>'Landscaping','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>18,'name'=>'Haulage','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>19,'name'=>'Plant Shift','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>20,'name'=>'Surveying','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>21,'name'=>'Materials Testing','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>22,'name'=>'GPS / UTS / Machine Control','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>23,'name'=>'Hydro Seeding','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>24,'name'=>'Concrete Pumping','created_at'=>'2023-07-07 21:50:50'],
            ['id'=>25,'name'=>'CCTV','created_at'=>'2023-07-07 21:50:50'],
        ];

        foreach ($services as $service) {
            SubcontractorTypeOfWork::create($service);
        }
    }
}