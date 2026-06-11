<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Infrastruktur Utama',
                'description' => 'Kategori untuk infrastruktur sumber daya air utama',
                'color' => '#3b82f6',
                'icon' => 'fas fa-building',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Sistem Distribusi',
                'description' => 'Kategori untuk sistem distribusi air',
                'color' => '#10b981',
                'icon' => 'fas fa-network-wired',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Pengolahan Air',
                'description' => 'Kategori untuk fasilitas pengolahan air',
                'color' => '#f59e0b',
                'icon' => 'fas fa-filter',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Konservasi',
                'description' => 'Kategori untuk upaya konservasi air',
                'color' => '#8b5cf6',
                'icon' => 'fas fa-leaf',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Pemantauan',
                'description' => 'Kategori untuk sistem pemantauan kualitas air',
                'color' => '#ef4444',
                'icon' => 'fas fa-chart-line',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Kesehatan',
                'description' => 'Kategori terkait aspek kesehatan air dan lingkungan',
                'color' => '#ff0f0f',
                'icon' => 'fas fa-heart',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('categories')->insertBatch($data);
    }
}