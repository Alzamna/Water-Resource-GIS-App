<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\WaterResourceModel;
use App\Models\CategoryModel;
use CodeIgniter\Controller;
use App\Controllers\BaseController;

class AdminController extends BaseController
{
    protected $waterResourceModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->waterResourceModel = new WaterResourceModel();
        $this->categoryModel = new CategoryModel();
    }

    // Tampilkan form login
    public function login()
    {
        // Kalau sudah login, langsung masuk ke dashboard
        if (session()->get('is_admin')) {
            return redirect()->to('/admin/dashboard');
        }
        return view('admin/login');
    }

    // Proses login
    public function loginPost()
    {
        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[6]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username dan password wajib diisi dengan benar.');
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'is_admin' => true,
                'admin_id' => $user['id'],
                'admin_username' => $user['username'],
                'admin_name' => $user['name'] ?? $user['username']
            ]);

            return redirect()->to('/admin/dashboard')->with('success', 'Login berhasil!');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Username atau password salah.');
    }

    // Logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logout berhasil!');
    }

    // Halaman dashboard admin
    public function dashboard()
    {
        // Check if user is logged in
        if (!session()->get('is_admin')) {
            return redirect()->to('/login');
        }

        // Get statistics for dashboard
        $totalLocations = $this->waterResourceModel->countAll();
        $activeLocations = $this->waterResourceModel->where('status', 'active')->countAllResults();
        $maintenanceLocations = $this->waterResourceModel->where('status', 'maintenance')->countAllResults();
        $inactiveLocations = $this->waterResourceModel->where('status', 'inactive')->countAllResults();

        // Get category statistics
        $totalCategories = $this->categoryModel->countAll();
        $activeCategories = $this->categoryModel->where('status', 1)->countAllResults();

        $data = [
            'title' => 'Dashboard CMS - GIS Admin Portal',
            'current_page' => 'dashboard',
            'total_locations' => $totalLocations,
            'active_locations' => $activeLocations,
            'maintenance_locations' => $maintenanceLocations,
            'inactive_locations' => $inactiveLocations,
            'total_categories' => $totalCategories,
            'active_categories' => $activeCategories,
            'admin_name' => session()->get('admin_name')
        ];

        return view('admin/dashboard', $data);
    }

    // Halaman manajemen peta
    public function maps()
    {
        // Check if user is logged in
        if (!session()->get('is_admin')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Manajemen Peta - GIS Admin Portal',
            'current_page' => 'maps',
            'include_leaflet' => true
        ];

        return view('admin/maps', $data);
    }

    // API: Get all locations
    public function getLocations()
    {
        if (!session()->get('is_admin')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $locations = $this->waterResourceModel->findAll();
        return $this->response->setJSON($locations);
    }

    // API: Add new location with photo upload
    public function addLocation()
    {
        if (!session()->get('is_admin')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $data = $this->request->getJSON(true);
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[255]',
            'type' => 'required|in_list[deep-well,reservoir,drainage,irrigation,other]',
            'latitude' => 'required|decimal',
            'longitude' => 'required|decimal',
            'status' => 'required|in_list[active,maintenance,inactive]',
            'description' => 'permit_empty|max_length[1000]'
        ]);

        if (!$validation->run($data)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ]);
        }

        // Handle photo upload if present
        $photoPath = null;
        if ($this->request->getFile('photo') && $this->request->getFile('photo')->isValid()) {
            $photo = $this->request->getFile('photo');
            $photoPath = $this->uploadPhoto($photo);
            
            if (!$photoPath) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Gagal mengupload foto'
                ]);
            }
            
            $data['photo'] = $photoPath;
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

    // API: Update location with photo upload
    public function updateLocation($id)
    {
        if (!session()->get('is_admin')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $data = $this->request->getJSON(true);
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[255]',
            'type' => 'required|in_list[deep-well,reservoir,drainage,irrigation,other]',
            'latitude' => 'required|decimal',
            'longitude' => 'required|decimal',
            'status' => 'required|in_list[active,maintenance,inactive]',
            'description' => 'permit_empty|max_length[1000]'
        ]);

        if (!$validation->run($data)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ]);
        }

        // Get existing location for photo handling
        $existingLocation = $this->waterResourceModel->find($id);
        if (!$existingLocation) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Lokasi tidak ditemukan'
            ]);
        }

        // Handle photo upload if present
        if ($this->request->getFile('photo') && $this->request->getFile('photo')->isValid()) {
            $photo = $this->request->getFile('photo');
            $photoPath = $this->uploadPhoto($photo);
            
            if (!$photoPath) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Gagal mengupload foto'
                ]);
            }
            
            // Delete old photo if exists
            if ($existingLocation['photo'] && file_exists(WRITEPATH . 'uploads/' . $existingLocation['photo'])) {
                unlink(WRITEPATH . 'uploads/' . $existingLocation['photo']);
            }
            
            $data['photo'] = $photoPath;
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

    // API: Delete location
    public function deleteLocation($id)
    {
        if (!session()->get('is_admin')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        // Get location data for photo deletion
        $location = $this->waterResourceModel->find($id);
        
        if ($this->waterResourceModel->delete($id)) {
            // Delete photo file if exists
            if ($location && $location['photo'] && file_exists(WRITEPATH . 'uploads/' . $location['photo'])) {
                unlink(WRITEPATH . 'uploads/' . $location['photo']);
            }
            
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

    // Helper method to upload photo
    private function uploadPhoto($photo)
    {
        // Validate file
        if (!$photo->isValid() || $photo->hasMoved()) {
            return false;
        }

        // Check file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($photo->getMimeType(), $allowedTypes)) {
            return false;
        }

        // Check file size (max 2MB)
        if ($photo->getSize() > 2048000) {
            return false;
        }

        // Create upload directory if not exists
        $uploadPath = WRITEPATH . 'uploads/locations/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generate unique filename
        $fileName = uniqid() . '_' . time() . '.' . $photo->getExtension();
        
        // Move file
        if ($photo->move($uploadPath, $fileName)) {
            return 'locations/' . $fileName;
        }

        return false;
    }

    // API: Get photo
    public function getPhoto($filename)
    {
        $filePath = WRITEPATH . 'uploads/' . $filename;
        
        if (file_exists($filePath)) {
            $mimeType = mime_content_type($filePath);
            return $this->response->setHeader('Content-Type', $mimeType)->setBody(file_get_contents($filePath));
        }
        
        return $this->response->setStatusCode(404);
    }

    // Halaman manajemen konten
    public function konten()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/login');
        }

        $konten = session()->get('konten') ?? 'Selamat datang di sistem manajemen konten GIS!';
        
        $data = [
            'title' => 'Manajemen Konten - GIS Admin Portal',
            'current_page' => 'konten',
            'konten' => $konten
        ];

        return view('admin/konten', $data);
    }

    // Simpan konten
    public function kontenPost()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/login');
        }

        $konten = $this->request->getPost('konten');
        session()->set('konten', $konten);
        
        return redirect()->to('/admin/konten')->with('success', 'Konten berhasil disimpan!');
    }

    // Halaman manajemen users
    public function users()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Manajemen User - GIS Admin Portal',
            'current_page' => 'users'
        ];

        return view('admin/users', $data);
    }

    // Halaman pengaturan
    public function settings()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Pengaturan - GIS Admin Portal',
            'current_page' => 'settings'
        ];

        return view('admin/settings', $data);
    }

    // Halaman profil admin
    public function profile()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $admin = $userModel->find(session()->get('admin_id'));

        $data = [
            'title' => 'Profil Admin - GIS Admin Portal',
            'current_page' => 'profile',
            'admin' => $admin
        ];

        return view('admin/profile', $data);
    }

    // Maps Management
    public function mapsList()
    {
        $data = [
            'title' => 'Daftar Lokasi - GIS Admin Portal',
            'current_page' => 'maps-list',
            'breadcrumb' => [
                ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
                ['title' => 'Manajemen Peta', 'url' => base_url('admin/maps')],
                ['title' => 'Daftar Lokasi']
            ]
        ];

        return view('admin/maps/list', $data);
    }

    public function mapsAdd()
    {
        $data = [
            'title' => 'Tambah Lokasi - GIS Admin Portal',
            'current_page' => 'maps-add',
            'include_leaflet' => true,
            'breadcrumb' => [
                ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
                ['title' => 'Manajemen Peta', 'url' => base_url('admin/maps')],
                ['title' => 'Tambah Lokasi']
            ]
        ];

        return view('admin/maps/add', $data);
    }

    public function mapsEdit($id)
    {
        $data = [
            'title' => 'Edit Lokasi - GIS Admin Portal',
            'current_page' => 'maps-edit',
            'include_leaflet' => true,
            'location_id' => $id,
            'breadcrumb' => [
                ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
                ['title' => 'Manajemen Peta', 'url' => base_url('admin/maps')],
                ['title' => 'Edit Lokasi']
            ]
        ];

        return view('admin/maps/edit', $data);
    }

    // Categories Management
    public function categories()
    {
        $data = [
            'title' => 'Manajemen Kategori - GIS Admin Portal',
            'current_page' => 'categories',
            'breadcrumb' => [
                ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
                ['title' => 'Manajemen Kategori']
            ]
        ];

        return view('admin/categories/list', $data);
    }

    public function categoriesAdd()
    {
        $data = [
            'title' => 'Tambah Kategori - GIS Admin Portal',
            'current_page' => 'categories-add',
            'breadcrumb' => [
                ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
                ['title' => 'Manajemen Kategori', 'url' => base_url('admin/categories')],
                ['title' => 'Tambah Kategori']
            ]
        ];

        return view('admin/categories/add', $data);
    }

    public function categoriesEdit($id)
    {
        $data = [
            'title' => 'Edit Kategori - GIS Admin Portal',
            'current_page' => 'categories-edit',
            'category_id' => $id,
            'breadcrumb' => [
                ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
                ['title' => 'Manajemen Kategori', 'url' => base_url('admin/categories')],
                ['title' => 'Edit Kategori']
            ]
        ];

        return view('admin/categories/edit', $data);
    }

    // API: Get categories with statistics
    public function getCategories()
    {
        if (!session()->get('is_admin')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        try {
            $page = $this->request->getGet('page') ?? 1;
            $search = $this->request->getGet('search') ?? '';
            $status = $this->request->getGet('status') ?? '';
            $limit = 12;
            $offset = ($page - 1) * $limit;

            // Build query
            $builder = $this->categoryModel->builder();
            
            if (!empty($search)) {
                $builder->groupStart()
                       ->like('name', $search)
                       ->orLike('description', $search)
                       ->groupEnd();
            }
            
            if ($status !== '') {
                $builder->where('is_active', $status);
            }

            // Get total count
            $totalCount = $builder->countAllResults(false);
            
            // Get categories with pagination
            $categories = $builder->orderBy('created_at', 'DESC')
                                ->limit($limit, $offset)
                                ->get()
                                ->getResultArray();

            // Calculate pagination
            $totalPages = ceil($totalCount / $limit);
            $showingStart = $offset + 1;
            $showingEnd = min($offset + $limit, $totalCount);

            // Get statistics
            $stats = $this->categoryModel->getCategoryStats();
            
            // Get new categories this month
            $currentMonth = date('Y-m');
            $newThisMonth = $this->categoryModel
                ->where('DATE_FORMAT(created_at, "%Y-%m")', $currentMonth)
                ->countAllResults();
            
            $stats['new_this_month'] = $newThisMonth;

            return $this->response->setJSON([
                'success' => true,
                'categories' => $categories,
                'statistics' => $stats,
                'pagination' => [
                    'current_page' => (int)$page,
                    'total_pages' => $totalPages,
                    'total_items' => $totalCount,
                    'showing_start' => $showingStart,
                    'showing_end' => $showingEnd,
                    'per_page' => $limit
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get categories error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Gagal memuat data kategori: ' . $e->getMessage()
            ]);
        }
    }

    // API: Get category statistics
    public function getCategoryStats()
    {
        if (!session()->get('is_admin')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        try {
            $stats = $this->categoryModel->getCategoryStats();
            
            // Get new categories this month
            $currentMonth = date('Y-m');
            $newThisMonth = $this->categoryModel
                ->where('DATE_FORMAT(created_at, "%Y-%m")', $currentMonth)
                ->countAllResults();
            
            $stats['new_this_month'] = $newThisMonth;

            return $this->response->setJSON([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get category stats error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Gagal memuat statistik kategori'
            ]);
        }
    }

    // API: Get single category
    public function getCategory($id)
    {
        if (!session()->get('is_admin')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        try {
            $category = $this->categoryModel->find($id);
            
            if (!$category) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Kategori tidak ditemukan'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'category' => $category
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get category error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Gagal memuat data kategori'
            ]);
        }
    }

    // API: Add new category
    public function addCategory()
    {
        if (!session()->get('is_admin')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        try {
            $data = $this->request->getJSON(true);
            
            // Validate data
            if (!$this->categoryModel->validate($data)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'errors' => $this->categoryModel->errors()
                ]);
            }

            // Insert category
            if ($this->categoryModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Kategori berhasil ditambahkan'
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Gagal menambahkan kategori'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Add category error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan kategori'
            ]);
        }
    }

    // API: Update category
    public function updateCategory($id)
    {
        if (!session()->get('is_admin')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        try {
            $data = $this->request->getJSON(true);
            
            // Check if category exists
            if (!$this->categoryModel->find($id)) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Kategori tidak ditemukan'
                ]);
            }

            // Validate data
            $data['id'] = $id; // For unique validation
            if (!$this->categoryModel->validate($data)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'errors' => $this->categoryModel->errors()
                ]);
            }

            // Remove id from data before update
            unset($data['id']);

            // Update category
            if ($this->categoryModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Kategori berhasil diperbarui'
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Gagal memperbarui kategori'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Update category error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui kategori'
            ]);
        }
    }

    // API: Toggle category status
    public function toggleCategoryStatus($id)
    {
        if (!session()->get('is_admin')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        try {
            if ($this->categoryModel->toggleStatus($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Status kategori berhasil diubah'
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Gagal mengubah status kategori'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Toggle category status error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status kategori'
            ]);
        }
    }

    // API: Delete category
    public function deleteCategory($id)
    {
        if (!session()->get('is_admin')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        try {
            // Check if category exists
            $category = $this->categoryModel->find($id);
            if (!$category) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Kategori tidak ditemukan'
                ]);
            }

            // Check if category can be deleted
            if (!$this->categoryModel->canDelete($id)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Kategori tidak dapat dihapus karena masih digunakan'
                ]);
            }

            // Delete category
            if ($this->categoryModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Kategori berhasil dihapus'
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus kategori'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Delete category error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus kategori'
            ]);
        }
    }

    // API: Export categories
    public function exportCategories()
    {
        if (!session()->get('is_admin')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        try {
            $categories = $this->categoryModel->orderBy('name', 'ASC')->findAll();
            
            // Set headers for CSV download
            $this->response->setHeader('Content-Type', 'text/csv');
            $this->response->setHeader('Content-Disposition', 'attachment; filename="categories_' . date('Y-m-d') . '.csv"');
            
            // Create CSV content
            $output = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add headers
            fputcsv($output, ['ID', 'Nama', 'Deskripsi', 'Warna', 'Icon', 'Status', 'Dibuat', 'Diperbarui']);
            
            // Add data
            foreach ($categories as $category) {
                fputcsv($output, [
                    $category['id'],
                    $category['name'],
                    $category['description'] ?? '',
                    $category['color'] ?? '',
                    $category['icon'] ?? '',
                    $category['is_active'] ? 'Aktif' : 'Tidak Aktif',
                    $category['created_at'],
                    $category['updated_at']
                ]);
            }
            
            fclose($output);
            return;
        } catch (\Exception $e) {
            log_message('error', 'Export categories error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Gagal mengekspor data kategori'
            ]);
        }
    }
}
