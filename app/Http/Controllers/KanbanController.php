<?php

namespace App\Http\Controllers;

use App\Services\TaskRecommendationService;
use Illuminate\View\View;

class KanbanController extends Controller
{
    public function index(): View
    {
        $tasks = auth()->user()->tasks()->with('course')->get();

        $columns = [
            'todo' => $tasks->where('status.value', 'todo')->values(),
            'in_progress' => $tasks->where('status.value', 'in_progress')->values(),
            'done' => $tasks->where('status.value', 'done')->values(),
        ];

        $recommendationService = new TaskRecommendationService;

        return view('kanban.index', compact('columns', 'recommendationService'));
    }
}
