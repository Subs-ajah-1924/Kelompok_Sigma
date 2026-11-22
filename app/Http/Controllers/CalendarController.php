<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(): View
    {
        $monthParam = request('month');
        try {
            $month = Carbon::createFromFormat('Y-m', $monthParam ?? now()->format('Y-m'))->startOfMonth();
        } catch (\Exception $e) {
            $month = now()->startOfMonth();
        }

        $gridStart = $month->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $gridEnd = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $tasks = auth()->user()->tasks()
            ->with('course')
            ->whereBetween('deadline', [$gridStart->copy()->startOfDay(), $gridEnd->copy()->endOfDay()])
            ->get()
            ->groupBy(fn ($task) => $task->deadline->format('Y-m-d'));

        $agendas = auth()->user()->agendas()
            ->whereBetween('date', [$gridStart->toDateString(), $gridEnd->toDateString()])
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn ($agenda) => $agenda->date->format('Y-m-d'));

        // Build grid days
        $days = [];
        $current = $gridStart->copy();
        while ($current->lte($gridEnd)) {
            $days[] = $current->copy();
            $current->addDay();
        }

        return view('calendar.index', compact('month', 'gridStart', 'gridEnd', 'tasks', 'agendas', 'days'));
    }
}
