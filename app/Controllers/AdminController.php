<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/login');
        }
        return view('admin/dashboard');
    }

    public function login()
    {
        return view('admin/login');
    }

    public function loginPost()
    {
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
            return redirect()->to('/');
        }
        return view('admin/login', ['error' => 'Username atau password salah']);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function konten()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/login');
        }
        // Dummy konten, bisa dikembangkan dengan database
        $konten = session()->get('konten') ?? 'Selamat datang di admin!';
        return view('admin/konten', ['konten' => $konten]);
    }

    public function kontenPost()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/login');
        }
        $konten = $this->request->getPost('konten');
        session()->set('konten', $konten);
        return redirect()->to('/admin/konten');
    }
} 