<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index(): View
    {
        return $this->placeholder(
            'Daftar Tugas',
            'Halaman daftar tugas, pencarian, dan filter akan diimplementasikan setelah model dan migration tersedia.',
        );
    }

    /**
     * Show the form for creating a task.
     */
    public function create(): View
    {
        return $this->placeholder(
            'Tambah Tugas',
            'Form tambah tugas akan diimplementasikan setelah model mata kuliah dan tugas tersedia.',
        );
    }

    /**
     * Store a newly created task.
     */
    public function store(): never
    {
        abort(501, 'Fitur tambah tugas belum diimplementasikan.');
    }

    /**
     * Display the specified task.
     */
    public function show(string $task): View
    {
        return $this->placeholder(
            'Detail Tugas',
            "Halaman detail untuk tugas #{$task} akan diimplementasikan setelah model tugas tersedia.",
        );
    }

    /**
     * Show the form for editing a task.
     */
    public function edit(string $task): View
    {
        return $this->placeholder(
            'Ubah Tugas',
            "Form ubah tugas #{$task} akan diimplementasikan setelah model tugas tersedia.",
        );
    }

    /**
     * Update the specified task.
     */
    public function update(string $task): never
    {
        abort(501, "Fitur ubah tugas #{$task} belum diimplementasikan.");
    }

    /**
     * Remove the specified task.
     */
    public function destroy(string $task): never
    {
        abort(501, "Fitur hapus tugas #{$task} belum diimplementasikan.");
    }

    /**
     * Update the status of the specified task from the Kanban board.
     */
    public function updateStatus(string $task): never
    {
        abort(501, "Fitur ubah status tugas #{$task} belum diimplementasikan.");
    }
}
