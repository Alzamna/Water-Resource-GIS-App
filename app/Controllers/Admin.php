<?php

namespace App\Controllers;

use App\Models\WaterResourceModel;
use App\Models\CategoryModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    protected $waterResourceModel;
    protected $categoryModel;
    protected $userModel;

    public function __construct()
    {
        $this->waterResourceModel = new WaterResourceModel();
        $this->categoryModel = new CategoryModel();
        $this->userModel = new UserModel();
    }

    public function dashboard()
    {
        // Get statistics
        $stats = $this->getDashboardStats();
        
        // Get recent activities
        $recent_activities = $this->getRecentActivities();
        
        // Get chart data
        $chart_data = $this->getChartData();

        $data = [
            'title' => 'Dashboard - GIS Admin Portal',
            'current_page' => 'dashboard',
            'stats' => $stats,
            'recent_activities' => $recent_activities,
            'chart_data' => $chart_data,
            'breadcrumb' => [
                ['title' => 'Dashboard']
            ]
        ];

        return view('admin/dashboard', $data);
    }

    public function dashboardData()
    {
        $stats = $this->getDashboardStats();
        $activities = $this->getRecentActivities();

        return $this->response->setJSON([
            'success' => true,
            'stats' => $stats,
            'activities' => $activities
        ]);
    }

    public function dashboardStats()
    {
        $stats = $this->getDashboardStats();

        return $this->response->setJSON([
            'success' => true,
            'stats' => $stats
        ]);
    }

    public function dashboardActivities()
    {
        $activities = $this->getRecentActivities();

        return $this->response->setJSON([
            'success' => true,
            'activities' => $activities
        ]);
    }

    private function getDashboardStats()
    {
        try {
            // Get current month stats
            $currentMonth = date('Y-m');
            $lastMonth = date('Y-m', strtotime('-1 month'));

            // Total counts
            $total_maps = $this->waterResourceModel->countAll();
            $total_categories = $this->categoryModel->countAll();
            $total_users = $this->userModel->countAll();
            $total_content = $total_maps + $total_categories; // Simple content count

            // Growth calculations (simplified)
            $last_month_maps = $this->waterResourceModel
                ->where('DATE_FORMAT(created_at, "%Y-%m")', $lastMonth)
                ->countAllResults();
            
            $current_month_maps = $this->waterResourceModel
                ->where('DATE_FORMAT(created_at, "%Y-%m")', $currentMonth)
                ->countAllResults();

            $maps_growth = $last_month_maps > 0 
                ? round((($current_month_maps - $last_month_maps) / $last_month_maps) * 100, 1)
                : 0;

            // Similar calculations for other entities
            $last_month_categories = $this->categoryModel
                ->where('DATE_FORMAT(created_at, "%Y-%m")', $lastMonth)
                ->countAllResults();
            
            $current_month_categories = $this->categoryModel
                ->where('DATE_FORMAT(created_at, "%Y-%m")', $currentMonth)
                ->countAllResults();

            $categories_growth = $last_month_categories > 0 
                ? round((($current_month_categories - $last_month_categories) / $last_month_categories) * 100, 1)
                : 0;

            $last_month_users = $this->userModel
                ->where('DATE_FORMAT(created_at, "%Y-%m")', $lastMonth)
                ->countAllResults();
            
            $current_month_users = $this->userModel
                ->where('DATE_FORMAT(created_at, "%Y-%m")', $currentMonth)
                ->countAllResults();

            $users_growth = $last_month_users > 0 
                ? round((($current_month_users - $last_month_users) / $last_month_users) * 100, 1)
                : 0;

            $content_growth = round(($maps_growth + $categories_growth) / 2, 1);

            return [
                'total_content' => $total_content,
                'total_users' => $total_users,
                'total_maps' => $total_maps,
                'total_categories' => $total_categories,
                'content_growth' => $content_growth,
                'users_growth' => $users_growth,
                'maps_growth' => $maps_growth,
                'categories_growth' => $categories_growth,
                'storage_usage' => rand(40, 60) // Simulated storage usage
            ];
        } catch (\Exception $e) {
            log_message('error', 'Dashboard stats error: ' . $e->getMessage());
            return [
                'total_content' => 0,
                'total_users' => 0,
                'total_maps' => 0,
                'total_categories' => 0,
                'content_growth' => 0,
                'users_growth' => 0,
                'maps_growth' => 0,
                'categories_growth' => 0,
                'storage_usage' => 0
            ];
        }
    }

    private function getRecentActivities()
    {
        try {
            $activities = [];

            // Get recent maps
            $recent_maps = $this->waterResourceModel
                ->orderBy('created_at', 'DESC')
                ->limit(3)
                ->find();

            foreach ($recent_maps as $map) {
                $activities[] = [
                    'title' => 'Lokasi baru ditambahkan',
                    'description' => $map['name'],
                    'time' => $this->timeAgo($map['created_at']),
                    'icon' => 'map-marker-alt',
                    'color' => 'sky',
                    'badge' => [
                        'text' => ucfirst($map['type']),
                        'color' => $this->getTypeColor($map['type'])
                    ]
                ];
            }

            // Get recent categories
            $recent_categories = $this->categoryModel
                ->orderBy('created_at', 'DESC')
                ->limit(2)
                ->find();

            foreach ($recent_categories as $category) {
                $activities[] = [
                    'title' => 'Kategori baru dibuat',
                    'description' => $category['name'],
                    'time' => $this->timeAgo($category['created_at']),
                    'icon' => 'tag',
                    'color' => 'emerald'
                ];
            }

            // Sort by time
            usort($activities, function($a, $b) {
                return strtotime($b['time']) - strtotime($a['time']);
            });

            return array_slice($activities, 0, 5);
        } catch (\Exception $e) {
            log_message('error', 'Recent activities error: ' . $e->getMessage());
            return [];
        }
    }

    private function getChartData()
    {
        try {
            // Activity chart data (last 6 months)
            $activity_labels = [];
            $activity_data = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $month = date('Y-m', strtotime("-$i months"));
                $monthName = date('M', strtotime("-$i months"));
                
                $count = $this->waterResourceModel
                    ->where('DATE_FORMAT(created_at, "%Y-%m")', $month)
                    ->countAllResults();
                
                $activity_labels[] = $monthName;
                $activity_data[] = $count;
            }

            // Status distribution
            $status_active = $this->waterResourceModel->where('status', 'active')->countAllResults();
            $status_maintenance = $this->waterResourceModel->where('status', 'maintenance')->countAllResults();
            $status_inactive = $this->waterResourceModel->where('status', 'inactive')->countAllResults();

            return [
                'activity' => [
                    'labels' => $activity_labels,
                    'data' => $activity_data
                ],
                'status' => [
                    'labels' => ['Aktif', 'Maintenance', 'Tidak Aktif'],
                    'data' => [$status_active, $status_maintenance, $status_inactive]
                ]
            ];
        } catch (\Exception $e) {
            log_message('error', 'Chart data error: ' . $e->getMessage());
            return [
                'activity' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'data' => [0, 0, 0, 0, 0, 0]
                ],
                'status' => [
                    'labels' => ['Aktif', 'Maintenance', 'Tidak Aktif'],
                    'data' => [0, 0, 0]
                ]
            ];
        }
    }

    private function timeAgo($datetime)
    {
        $time = time() - strtotime($datetime);

        if ($time < 60) return 'Baru saja';
        if ($time < 3600) return floor($time/60) . ' menit yang lalu';
        if ($time < 86400) return floor($time/3600) . ' jam yang lalu';
        if ($time < 2592000) return floor($time/86400) . ' hari yang lalu';
        if ($time < 31536000) return floor($time/2592000) . ' bulan yang lalu';
        return floor($time/31536000) . ' tahun yang lalu';
    }

    private function getTypeColor($type)
    {
        $colors = [
            'deep-well' => 'blue',
            'reservoir' => 'green',
            'drainage' => 'yellow',
            'irrigation' => 'purple',
            'other' => 'gray'
        ];

        return $colors[$type] ?? 'gray';
    }

    // Other existing methods...
    public function maps()
    {
        $data = [
            'title' => 'Manajemen Peta - GIS Admin Portal',
            'current_page' => 'maps',
            'include_leaflet' => true
        ];

        return view('admin/maps', $data);
    }

    public function getLocations()
    {
        $locations = $this->waterResourceModel->findAll();
        return $this->response->setJSON($locations);
    }

    public function addLocation()
    {
        $data = $this->request->getJSON(true);
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[255]',
            'type' => 'required|in_list[deep-well,reservoir,drainage,irrigation,other]',
            'latitude' => 'required|decimal',
            'longitude' => 'required|decimal',
            'status' => 'required|in_list[active,maintenance,inactive]'
        ]);

        if (!$validation->run($data)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ]);
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        if ($this->waterResourceModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Lokasi berhasil ditambahkan'
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Gagal menambahkan lokasi'
            ]);
        }
    }

    public function updateLocation($id)
    {
        $data = $this->request->getJSON(true);
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[255]',
            'type' => 'required|in_list[deep-well,reservoir,drainage,irrigation,other]',
            'latitude' => 'required|decimal',
            'longitude' => 'required|decimal',
            'status' => 'required|in_list[active,maintenance,inactive]'
        ]);

        if (!$validation->run($data)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ]);
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        if ($this->waterResourceModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Lokasi berhasil diperbarui'
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Gagal memperbarui lokasi'
            ]);
        }
    }

    public function deleteLocation($id)
    {
        if ($this->waterResourceModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Lokasi berhasil dihapus'
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus lokasi'
            ]);
        }
    }

    public function konten()
    {
        $data = [
            'title' => 'Manajemen Konten - GIS Admin Portal',
            'current_page' => 'konten'
        ];

        return view('admin/konten', $data);
    }

    public function users()
    {
        $data = [
            'title' => 'Manajemen User - GIS Admin Portal',
            'current_page' => 'users'
        ];

        return view('admin/users', $data);
    }

    public function settings()
    {
        $data = [
            'title' => 'Pengaturan - GIS Admin Portal',
            'current_page' => 'settings'
        ];

        return view('admin/settings', $data);
    }

    public function profile()
    {
        $data = [
            'title' => 'Profil Admin - GIS Admin Portal',
            'current_page' => 'profile'
        ];

        return view('admin/profile', $data);
    }
}
