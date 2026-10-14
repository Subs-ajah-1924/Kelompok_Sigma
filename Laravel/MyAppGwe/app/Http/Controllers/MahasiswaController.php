<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa = [
            ["nama" => "andi", "ipk" => 3.5],
            ["nama" => "subahehe", "ipk" => 3.0],
        ];

        return view("mahasiswa.index", ["mahasiswa" => $mahasiswa]);
    }
}
