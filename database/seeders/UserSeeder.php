<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ZoneUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data zone berdasarkan nama
        $dkvZone = ZoneUser::where('zone_name', 'dkv')->first();
        $sarprasZone = ZoneUser::where('zone_name', 'sarpras')->first();
        $sijaZone = ZoneUser::where('zone_name', 'sija')->first();

        // Tambahkan user dengan id_zone yang sesuai
        User::insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'admin',
                'id_zone' => null,
            ],
            [
                'name' => 'Teknisi DKV',
                'email' => 'dkv@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'teknisi',
                'id_zone' => $dkvZone->id ?? null,
            ],
            [
                'name' => 'Teknisi Sarpras',
                'email' => 'sarpras@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'teknisi',
                'id_zone' => $sarprasZone->id ?? null,
            ],
            [
                'name' => 'Teknisi SIJA',
                'email' => 'sija@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'teknisi',
                'id_zone' => $sijaZone->id ?? null,
            ],
        ]);
    }
}
