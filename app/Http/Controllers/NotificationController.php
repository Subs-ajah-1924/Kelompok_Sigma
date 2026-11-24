<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $now = now();
        $startOfDay = $now->copy()->startOfDay();
        $endOfDay = $now->copy()->endOfDay();
        $base = auth()->user()->tasks()->with('course')->where('status', '!=', 'done');

        $overdue = (clone $base)->where('deadline', '<', $startOfDay)->orderBy('deadline')->limit(50)->get();
        $today = (clone $base)->whereBetween('deadline', [$startOfDay, $endOfDay])->orderBy('deadline')->limit(50)->get();
        $soon = (clone $base)->where('deadline', '>', $endOfDay)->where('deadline', '<=', $now->copy()->addDays(3)->endOfDay())->orderBy('deadline')->limit(50)->get();
        $upcoming = (clone $base)->where('deadline', '>', $now->copy()->addDays(3)->endOfDay())->where('deadline', '<=', $now->copy()->addDays(7)->endOfDay())->orderBy('deadline')->limit(50)->get();
        $later = (clone $base)->where('deadline', '>', $now->copy()->addDays(7)->endOfDay())->orderBy('deadline')->limit(50)->get();

        return view('notifications.index', compact('overdue', 'today', 'soon', 'upcoming', 'later'));
    }

    public function markGroupDone(string $group): RedirectResponse
    {
        $now = now();
        $query = auth()->user()->tasks()->where('status', '!=', 'done');

        match ($group) {
            'overdue' => $query->where('deadline', '<', $now->copy()->startOfDay()),
            'today' => $query->whereBetween('deadline', [$now->copy()->startOfDay(), $now->copy()->endOfDay()]),
            'soon' => $query->where('deadline', '>', $now->copy()->endOfDay())->where('deadline', '<=', $now->copy()->addDays(3)->endOfDay()),
            'upcoming' => $query->where('deadline', '>', $now->copy()->addDays(3)->endOfDay())->where('deadline', '<=', $now->copy()->addDays(7)->endOfDay()),
            'later' => $query->where('deadline', '>', $now->copy()->addDays(7)->endOfDay()),
            default => abort(404),
        };

        $affected = $query->update(['status' => 'done', 'completed_at' => now()]);

        if ($affected === 0) {
            return back()->with('success', 'Tidak ada tugas yang ditandai selesai.');
        }

        return back()->with('success', 'Tugas berhasil ditandai selesai.');
    }
}
