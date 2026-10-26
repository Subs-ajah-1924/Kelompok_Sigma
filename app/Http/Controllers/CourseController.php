<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index(): View
    {
        return $this->placeholder(
            'Mata Kuliah',
            'Halaman daftar mata kuliah akan diimplementasikan setelah model dan migration tersedia.',
        );
    }

    /**
     * Show the form for creating a course.
     */
    public function create(): View
    {
        return $this->placeholder(
            'Tambah Mata Kuliah',
            'Form tambah mata kuliah akan diimplementasikan setelah model mata kuliah tersedia.',
        );
    }

    /**
     * Store a newly created course.
     */
    public function store(): never
    {
        abort(501, 'Fitur tambah mata kuliah belum diimplementasikan.');
    }

    /**
     * Show the form for editing a course.
     */
    public function edit(string $course): View
    {
        return $this->placeholder(
            'Ubah Mata Kuliah',
            "Form ubah mata kuliah #{$course} akan diimplementasikan setelah model mata kuliah tersedia.",
        );
    }

    /**
     * Update the specified course.
     */
    public function update(string $course): never
    {
        abort(501, "Fitur ubah mata kuliah #{$course} belum diimplementasikan.");
    }

    /**
     * Remove the specified course.
     */
    public function destroy(string $course): never
    {
        abort(501, "Fitur hapus mata kuliah #{$course} belum diimplementasikan.");
    }
}
