<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AdminController extends Controller
{
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
            'username' => 'required',
            'password' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username dan password wajib diisi.');
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'is_admin' => true,
                'admin_id' => $user['id'],
                'admin_username' => $user['username']
            ]);
            return redirect()->to('/admin/dashboard');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Username atau password salah.');
    }

    // Logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // Halaman dashboard admin
    public function dashboard()
    {
        return view('admin/dashboard');
    }

    // Tampilkan konten
    public function konten()
    {
        $konten = session()->get('konten') ?? 'Selamat datang di admin!';
        return view('admin/konten', ['konten' => $konten]);
    }

    // Simpan konten (sementara masih disimpan di session)
    public function kontenPost()
    {
        $konten = $this->request->getPost('konten');
        session()->set('konten', $konten);
        return redirect()->to('/admin/konten');
    }
}
