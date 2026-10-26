<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

abstract class Controller
{
    /**
     * Render a temporary page for a controller that is not implemented yet.
     */
    protected function placeholder(string $title, string $message): View
    {
        return view('placeholder', [
            'title' => $title,
            'message' => $message,
        ]);
    }
}
