<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ThemeController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'theme_color' => 'required|in:indigo,rose,emerald,amber,sky',
        ]);

        $request->user()->update([
            'theme_color' => $request->theme_color,
        ]);

        return back()->with('success', 'Tema berhasil diperbarui.');
    }
}
