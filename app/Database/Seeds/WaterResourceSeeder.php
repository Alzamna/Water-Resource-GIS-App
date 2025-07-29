<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WaterResourceSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Sumur Dalam Cipinang',
                'type' => 'deep-well',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'description' => 'Sumur dalam untuk kebutuhan air bersih warga Cipinang',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Reservoir Kelapa Gading',
                'type' => 'reservoir',
                'latitude' => -6.1574,
                'longitude' => 106.9073,
                'description' => 'Reservoir air untuk kawasan Kelapa Gading',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Saluran Pembuang Mangga Dua',
                'type' => 'drainage',
                'latitude' => -6.1378,
                'longitude' => 106.8220,
                'description' => 'Saluran pembuangan air hujan di kawasan Mangga Dua',
                'status' => 'maintenance',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Jaringan Irigasi Sawah Senayan',
                'type' => 'irrigation',
                'latitude' => -6.2276,
                'longitude' => 106.7997,
                'description' => 'Jaringan irigasi untuk sawah di kawasan Senayan',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Sumur Pompa Tanah Abang',
                'type' => 'deep-well',
                'latitude' => -6.1865,
                'longitude' => 106.8220,
                'description' => 'Sumur pompa untuk kebutuhan air di Tanah Abang',
                'status' => 'inactive',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Reservoir Pluit',
                'type' => 'reservoir',
                'latitude' => -6.1133,
                'longitude' => 106.7877,
                'description' => 'Reservoir air untuk kawasan Pluit dan sekitarnya',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Saluran Pembuang Ancol',
                'type' => 'drainage',
                'latitude' => -6.1287,
                'longitude' => 106.8456,
                'description' => 'Saluran pembuangan air di kawasan Ancol',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Jaringan Irigasi Kebun Raya',
                'type' => 'irrigation',
                'latitude' => -6.5971,
                'longitude' => 106.7997,
                'description' => 'Jaringan irigasi untuk Kebun Raya Bogor',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('water_resources')->insertBatch($data);
    }
} 