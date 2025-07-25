<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\WaterResourceModel;
use CodeIgniter\Controller;

class AdminController extends Controller
{
    protected $waterResourceModel;

    public function __construct()
    {
        $this->waterResourceModel = new WaterResourceModel();
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
        $statistics = $this->waterResourceModel->getStatistics();

        $data = [
            'title' => 'Dashboard CMS - GIS Admin Portal',
            'current_page' => 'dashboard',
            'total_locations' => $totalLocations,
            'active_locations' => $activeLocations,
            'maintenance_locations' => $maintenanceLocations,
            'statistics' => $statistics,
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
            'current_page' => 'maps'
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

    // Halaman tambah lokasi
    public function mapsAdd()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Tambah Lokasi - GIS Admin Portal',
            'current_page' => 'maps'
        ];

        return view('admin/maps/add', $data);
    }

    // Proses tambah lokasi
    public function mapsAddPost()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/login');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[255]',
            'type' => 'required|in_list[deep-well,reservoir,drainage,irrigation,other]',
            'latitude' => 'required|decimal',
            'longitude' => 'required|decimal',
            'status' => 'required|in_list[active,maintenance,inactive]',
            'description' => 'permit_empty|max_length[1000]',
            'photo' => 'permit_empty|uploaded[photo]|max_size[photo,2048]|is_image[photo]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Data tidak valid. Periksa kembali input Anda.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'type' => $this->request->getPost('type'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
            'status' => $this->request->getPost('status'),
            'description' => $this->request->getPost('description'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle photo upload
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $photoPath = $this->uploadPhoto($photo);
            if ($photoPath) {
                $data['photo'] = $photoPath;
            }
        }

        if ($this->waterResourceModel->insert($data)) {
            return redirect()->to('/admin/maps')->with('success', 'Lokasi berhasil ditambahkan!');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan lokasi. Silakan coba lagi.');
        }
    }

    // Halaman edit lokasi
    public function mapsEdit($id)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/login');
        }

        $location = $this->waterResourceModel->find($id);
        if (!$location) {
            return redirect()->to('/admin/maps')->with('error', 'Lokasi tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Lokasi - GIS Admin Portal',
            'current_page' => 'maps',
            'location' => $location
        ];

        return view('admin/maps/edit', $data);
    }

    // Proses edit lokasi
    public function mapsEditPost($id)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/login');
        }

        $location = $this->waterResourceModel->find($id);
        if (!$location) {
            return redirect()->to('/admin/maps')->with('error', 'Lokasi tidak ditemukan.');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[255]',
            'type' => 'required|in_list[deep-well,reservoir,drainage,irrigation,other]',
            'latitude' => 'required|decimal',
            'longitude' => 'required|decimal',
            'status' => 'required|in_list[active,maintenance,inactive]',
            'description' => 'permit_empty|max_length[1000]',
            'photo' => 'permit_empty|uploaded[photo]|max_size[photo,2048]|is_image[photo]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Data tidak valid. Periksa kembali input Anda.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'type' => $this->request->getPost('type'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
            'status' => $this->request->getPost('status'),
            'description' => $this->request->getPost('description'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle photo removal
        if ($this->request->getPost('remove_photo')) {
            if ($location['photo'] && file_exists(WRITEPATH . 'uploads/' . $location['photo'])) {
                unlink(WRITEPATH . 'uploads/' . $location['photo']);
            }
            $data['photo'] = null;
        }

        // Handle new photo upload
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            // Delete old photo if exists
            if ($location['photo'] && file_exists(WRITEPATH . 'uploads/' . $location['photo'])) {
                unlink(WRITEPATH . 'uploads/' . $location['photo']);
            }
            
            $photoPath = $this->uploadPhoto($photo);
            if ($photoPath) {
                $data['photo'] = $photoPath;
            }
        }

        if ($this->waterResourceModel->update($id, $data)) {
            return redirect()->to('/admin/maps')->with('success', 'Lokasi berhasil diperbarui!');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui lokasi. Silakan coba lagi.');
        }
    }

    // Halaman daftar lokasi
    public function mapsList()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Daftar Lokasi - GIS Admin Portal',
            'current_page' => 'maps'
        ];

        return view('admin/maps/list', $data);
    }

    // Export data
    public function mapsExport()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/login');
        }

        $format = $this->request->getGet('format');
        $search = $this->request->getGet('search');
        $type = $this->request->getGet('type');
        $status = $this->request->getGet('status');

        // Get filtered data
        $builder = $this->waterResourceModel->builder();
        
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('name', $search)
                    ->orLike('description', $search)
                    ->groupEnd();
        }
        
        if (!empty($type)) {
            $builder->where('type', $type);
        }
        
        if (!empty($status)) {
            $builder->where('status', $status);
        }
        
        $locations = $builder->get()->getResultArray();

        if ($format === 'excel') {
            return $this->exportToExcel($locations);
        } elseif ($format === 'pdf') {
            return $this->exportToPDF($locations);
        }

        return redirect()->to('/admin/maps/list');
    }

    // Export to Excel (simple CSV format)
    private function exportToExcel($locations)
    {
        $filename = 'lokasi_sumber_daya_air_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header CSV
        fputcsv($output, [
            'ID',
            'Nama Lokasi',
            'Jenis Infrastruktur',
            'Latitude',
            'Longitude',
            'Status',
            'Deskripsi',
            'Tanggal Dibuat',
            'Tanggal Diperbarui'
        ]);
        
        // Data rows
        foreach ($locations as $location) {
            $typeLabels = [
                'deep-well' => 'Sumur Pompa Dalam',
                'reservoir' => 'Sumur Reservoir',
                'drainage' => 'Saluran Pembuang',
                'irrigation' => 'Jaringan Irigasi',
                'other' => 'Lainnya'
            ];
            
            $statusLabels = [
                'active' => 'Aktif',
                'maintenance' => 'Maintenance',
                'inactive' => 'Tidak Aktif'
            ];
            
            fputcsv($output, [
                $location['id'],
                $location['name'],
                $typeLabels[$location['type']] ?? $location['type'],
                $location['latitude'],
                $location['longitude'],
                $statusLabels[$location['status']] ?? $location['status'],
                $location['description'] ?? '',
                date('d/m/Y H:i', strtotime($location['created_at'])),
                date('d/m/Y H:i', strtotime($location['updated_at']))
            ]);
        }
        
        fclose($output);
        exit;
    }

    // Export to PDF (simple HTML to PDF)
    private function exportToPDF($locations)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Daftar Lokasi Sumber Daya Air</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; }
                .header { text-align: center; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .status-active { color: #166534; }
                .status-maintenance { color: #92400e; }
                .status-inactive { color: #991b1b; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>Daftar Lokasi Sumber Daya Air</h2>
                <p>Diekspor pada: ' . date('d/m/Y H:i:s') . '</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lokasi</th>
                        <th>Jenis</th>
                        <th>Koordinat</th>
                        <th>Status</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>';
        
        $typeLabels = [
            'deep-well' => 'Sumur Pompa Dalam',
            'reservoir' => 'Sumur Reservoir',
            'drainage' => 'Saluran Pembuang',
            'irrigation' => 'Jaringan Irigasi',
            'other' => 'Lainnya'
        ];
        
        $statusLabels = [
            'active' => 'Aktif',
            'maintenance' => 'Maintenance',
            'inactive' => 'Tidak Aktif'
        ];
        
        $no = 1;
        foreach ($locations as $location) {
            $statusClass = 'status-' . $location['status'];
            $html .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>' . htmlspecialchars($location['name']) . '</td>
                        <td>' . ($typeLabels[$location['type']] ?? $location['type']) . '</td>
                        <td>' . $location['latitude'] . ', ' . $location['longitude'] . '</td>
                        <td class="' . $statusClass . '">' . ($statusLabels[$location['status']] ?? $location['status']) . '</td>
                        <td>' . htmlspecialchars($location['description'] ?? '') . '</td>
                    </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
        </body>
        </html>';
        
        // Simple HTML output (for basic PDF generation, you might want to use a library like TCPDF or mPDF)
        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="lokasi_sumber_daya_air_' . date('Y-m-d_H-i-s') . '.html"');
        
        echo $html;
        exit;
    }
}
