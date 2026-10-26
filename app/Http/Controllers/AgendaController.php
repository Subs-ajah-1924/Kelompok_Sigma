<?php

namespace App\Http\Controllers;

class AgendaController extends Controller
{
    /**
     * Store a newly created agenda.
     */
    public function store(): never
    {
        abort(501, 'Fitur tambah agenda belum diimplementasikan.');
    }

    /**
     * Update the specified agenda.
     */
    public function update(string $agenda): never
    {
        abort(501, "Fitur ubah agenda #{$agenda} belum diimplementasikan.");
    }

    /**
     * Remove the specified agenda.
     */
    public function destroy(string $agenda): never
    {
        abort(501, "Fitur hapus agenda #{$agenda} belum diimplementasikan.");
    }
}
