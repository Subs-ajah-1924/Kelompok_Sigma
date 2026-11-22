<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $now = now();

        $tasks = auth()->user()->tasks()
            ->with('course')
            ->where('status', '!=', 'done')
            ->where('deadline', '<=', $now->copy()->addDays(7)->endOfDay())
            ->orderBy('deadline')
            ->get();

        $overdue = $tasks->filter(fn ($t) => $t->deadline->lt($now->copy()->startOfDay()));
        $today = $tasks->filter(fn ($t) => $t->deadline->between($now->copy()->startOfDay(), $now->copy()->endOfDay()));
        $soon = $tasks->filter(fn ($t) => $t->deadline->gt($now->copy()->endOfDay()) && $t->deadline->lte($now->copy()->addDays(3)->endOfDay()));
        $upcoming = $tasks->filter(fn ($t) => $t->deadline->gt($now->copy()->addDays(3)->endOfDay()) && $t->deadline->lte($now->copy()->addDays(7)->endOfDay()));

        return view('notifications.index', compact('overdue', 'today', 'soon', 'upcoming'));
    }

    public function markGroupDone(string $group): \Illuminate\Http\RedirectResponse
    {
        $now = now();
        $query = auth()->user()->tasks()->where('status', '!=', 'done');

        match ($group) {
            'overdue' => $query->where('deadline', '<', $now->copy()->startOfDay()),
            'today' => $query->whereBetween('deadline', [$now->copy()->startOfDay(), $now->copy()->endOfDay()]),
            'soon' => $query->where('deadline', '>', $now->copy()->endOfDay())->where('deadline', '<=', $now->copy()->addDays(3)->endOfDay()),
            'upcoming' => $query->where('deadline', '>', $now->copy()->addDays(3)->endOfDay())->where('deadline', '<=', $now->copy()->addDays(7)->endOfDay()),
            default => null,
        };

        $query->update(['status' => 'done', 'completed_at' => now()]);

        return back()->with('success', 'Tugas berhasil ditandai selesai.');
    }
}
