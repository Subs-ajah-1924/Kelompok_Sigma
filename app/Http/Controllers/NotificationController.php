<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display the deadline notifications.
     */
    public function index(): View
    {
        return $this->placeholder(
            'Notifikasi Deadline',
            'Daftar tugas yang mendekati deadline dan yang sudah terlambat akan diimplementasikan setelah model tugas tersedia.',
        );
    }
}
