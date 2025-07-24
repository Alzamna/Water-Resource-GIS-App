<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class PetaController extends Controller
{
    public function index()
    {
        // Ambil data POST jika ada
        $latitude = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');

        return view('input_peta', [
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);
    }

    public function halamanBaru()
    {
        return view('halaman_baru');
    }
}
