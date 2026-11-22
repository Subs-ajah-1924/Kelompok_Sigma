<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        $totalTasks = $user->tasks()->count();
        $doneCount = $user->tasks()->where('status', 'done')->count();
        $todoCount = $user->tasks()->where('status', '!=', 'done')->count();
        $completionRate = $totalTasks > 0 ? round(($doneCount / $totalTasks) * 100, 1) : 0;

        // Produktivitas mingguan (jumlah tugas selesai per minggu, misal 8 minggu terakhir)
        $weeklyProductivity = [];
        for ($i = 7; $i >= 0; $i--) {
            $startOfWeek = now()->subWeeks($i)->startOfWeek();
            $endOfWeek = now()->subWeeks($i)->endOfWeek();

            $count = $user->tasks()
                ->where('status', 'done')
                ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                ->count();

            $weeklyProductivity[] = [
                'week' => $startOfWeek->format('d M').' - '.$endOfWeek->format('d M'),
                'count' => $count,
            ];
        }

        // Distribusi status
        $statusCounts = [
            'todo' => $user->tasks()->where('status', 'todo')->count(),
            'in_progress' => $user->tasks()->where('status', 'in_progress')->count(),
            'done' => $doneCount,
        ];

        return view('statistics.index', compact(
            'totalTasks',
            'doneCount',
            'todoCount',
            'completionRate',
            'weeklyProductivity',
            'statusCounts'
        ));
    }
}
