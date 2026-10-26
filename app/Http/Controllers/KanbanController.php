<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class KanbanController extends Controller
{
    /**
     * Display the Kanban board.
     */
    public function index(): View
    {
        return $this->placeholder(
            'Kanban Board',
            'Papan Kanban To Do, In Progress, dan Done akan diimplementasikan setelah model tugas tersedia.',
        );
    }
}
