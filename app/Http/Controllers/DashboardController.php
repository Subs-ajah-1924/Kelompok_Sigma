<?php

namespace App\Http\Controllers;

use App\Services\TaskRecommendationService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        $totalTasks = $user->tasks()->count();
        $todoCount = $user->tasks()->where('status', 'todo')->count();
        $inProgressCount = $user->tasks()->where('status', 'in_progress')->count();
        $doneCount = $user->tasks()->where('status', 'done')->count();
        $overdueCount = $user->tasks()->where('status', '!=', 'done')->where('deadline', '<', now())->count();

        $upcomingTasks = $user->tasks()
            ->with('course')
            ->where('status', '!=', 'done')
            ->where('deadline', '>=', now())
            ->where('deadline', '<=', now()->copy()->addDays(7)->endOfDay())
            ->orderBy('deadline')
            ->limit(5)
            ->get();

        $recentTasks = $user->tasks()->with('course')->latest()->limit(5)->get();

        $recommendations = $user->tasks()
            ->with('course')
            ->where('status', '!=', 'done')
            ->get()
            ->map(fn ($task) => [
                'task' => $task,
                'recommendation' => (new TaskRecommendationService)->recommend($task),
            ])
            ->sortByDesc(fn ($item) => $item['recommendation']->score)
            ->take(5)
            ->values();

        return view('dashboard', compact(
            'totalTasks', 'todoCount', 'inProgressCount', 'doneCount', 'overdueCount',
            'upcomingTasks', 'recentTasks', 'recommendations'
        ));
    }
}
