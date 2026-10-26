<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CalendarController extends Controller
{
    /**
     * Display the monthly calendar.
     */
    public function index(): View
    {
        return $this->placeholder(
            'Kalender',
            'Kalender bulanan dan integrasi deadline tugas akan diimplementasikan setelah model tugas dan agenda tersedia.',
        );
    }
}
