<?php

namespace Database\Seeders;

use App\Models\ZoneUser as ModelsZoneUser;
use Illuminate\Container\Attributes\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZoneUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $zones = [
        //     ['zone_name' => 'dkv'],
        //     ['zone_name' => 'sarpras'],
        //     ['zone_name' => 'sija'],
        // ];

        // DB::table('zone_users')->insert($zones);
        ModelsZoneUser::create(['zone_name' => 'dkv']);
        ModelsZoneUser::create(['zone_name' => 'sarpras']);
        ModelsZoneUser::create(['zone_name' => 'sija']);
    }
}
