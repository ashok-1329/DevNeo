<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubcontractorNameList;

class SubcontractorNameListSeed extends Seeder
{
    public function run(): void
    {
        $companies = [
            ['id'=>1,'name'=>'APEX Vac Solutions','rep_name'=>'Levi Gilfillan','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>2,'name'=>'Adelaide Pipeline Maintenance Services','rep_name'=>'Hayden Burley','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>3,'name'=>'CME Group','rep_name'=>'Jayden Moyes','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>4,'name'=>'Plumbing and Pipeline Solutions','rep_name'=>'Levi Lock','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>5,'name'=>'Creative Payments','rep_name'=>'Lachlan Baudin','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>6,'name'=>'WP Concreting','rep_name'=>'Hamish Kelsall','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>7,'name'=>'JD Tree Climbers','rep_name'=>'Dean Reynolds','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>8,'name'=>'Adelaide Kerbing','rep_name'=>"Michael O'Malley",'created_at'=>'2023-07-07 15:53:50'],
            ['id'=>9,'name'=>'AK Concrete','rep_name'=>'William Hardess','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>10,'name'=>'Select Power Services','rep_name'=>'Oliver Reeks','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>11,'name'=>'Makesafe Traffic Management (SA)','rep_name'=>'Taj Dampier','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>12,'name'=>'Distro-Tex','rep_name'=>'Will Bate','created_at'=>'2023-07-07 15:55:13'],
            ['id'=>13,'name'=>'Seacon Australia','rep_name'=>'Jonathan Trumper','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>14,'name'=>'Max Crane and Equipment Hire','rep_name'=>'Christopher Gibbons','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>15,'name'=>'Fleurieu Cranes','rep_name'=>"Luca O'Doherty",'created_at'=>'2023-07-07 15:53:50'],
            ['id'=>16,'name'=>'Supersealing','rep_name'=>'Hugo Belbin','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>17,'name'=>'HSG','rep_name'=>'Jett Drummond','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>18,'name'=>'Top Coat Asphalt','rep_name'=>'Austin Benny','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>19,'name'=>'Bahrs Back Yards','rep_name'=>'Zachary Feakes','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>22,'name'=>'Breakaway Concreting & Drilling','rep_name'=>'Tristan Trefl','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>23,'name'=>'Statewide Hydrojet','rep_name'=>'Nicholas Faucett','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>24,'name'=>'Enviro Sweep','rep_name'=>'Ashton Sani','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>25,'name'=>'Schulze Kerbing','rep_name'=>'Jett Bustard','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>26,'name'=>'Adelaide Landscaping & Property Maintenance','rep_name'=>'Aidan Tomholt','created_at'=>'2023-07-07 15:53:50'],
            ['id'=>27,'name'=>'Page Excavations','rep_name'=>'Tyler Fenton','created_at'=>'2023-07-07 15:53:50'],
        ];

        foreach ($companies as $company) {
            SubcontractorNameList::create($company);
        }
    }
}