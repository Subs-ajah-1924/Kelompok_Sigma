<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\Task;
use App\Services\TaskRecommendationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        $filters = request()->only(['q', 'course_id', 'status', 'difficulty', 'deadline_from', 'deadline_to', 'sort']);

        $query = auth()->user()->tasks()->with('course');

        // Search
        if (! empty($filters['q']) && is_scalar($filters['q'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%'.$filters['q'].'%')
                    ->orWhere('description', 'like', '%'.$filters['q'].'%');
            });
        }

        // Filters
        if (! empty($filters['course_id']) && is_scalar($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }
        if (! empty($filters['status']) && is_scalar($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['difficulty']) && is_scalar($filters['difficulty'])) {
            $query->where('difficulty', $filters['difficulty']);
        }
        if (! empty($filters['deadline_from'])) {
            try {
                $query->where('deadline', '>=', Carbon::parse($filters['deadline_from'])->startOfDay());
            } catch (\Throwable $e) {
                // Abaikan filter tanggal yang tidak valid, jangan sampai 500.
            }
        }
        if (! empty($filters['deadline_to'])) {
            try {
                $query->where('deadline', '<=', Carbon::parse($filters['deadline_to'])->endOfDay());
            } catch (\Throwable $e) {
                // Abaikan filter tanggal yang tidak valid, jangan sampai 500.
            }
        }

        // Sort
        $sort = $filters['sort'] ?? 'deadline';
        match ($sort) {
            'title' => $query->orderBy('title'),
            'created_at' => $query->latest(),
            default => $query->orderBy('deadline'),
        };

        $tasks = $query->paginate(12)->withQueryString();

        $courses = auth()->user()->courses()->get();

        return view('tasks.index', compact('tasks', 'courses', 'filters'));
    }

    public function create(): View
    {
        $courses = auth()->user()->courses()->get();

        return view('tasks.create', compact('courses'));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        if (empty($data['status'])) {
            $data['status'] = 'todo';
        }
        if (($data['status'] ?? null) === 'done') {
            $data['completed_at'] = now();
        }

        $task = auth()->user()->tasks()->create($data);

        return redirect()->route('tasks.show', $task)->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function show(Task $task): View
    {
        $this->authorize('view', $task);

        $task->load('course');

        $recommendation = (new TaskRecommendationService)->recommend($task);

        return view('tasks.show', compact('task', 'recommendation'));
    }

    public function edit(Task $task): View
    {
        $this->authorize('update', $task);

        $courses = auth()->user()->courses()->get();

        return view('tasks.edit', compact('task', 'courses'));
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $data = $request->validated();

        // Handle completed_at
        if (isset($data['status'])) {
            if ($data['status'] === 'done' && $task->status->value !== 'done') {
                $data['completed_at'] = now();
            } elseif ($data['status'] !== 'done') {
                $data['completed_at'] = null;
            }
        }

        $task->update($data);

        return redirect()->route('tasks.show', $task)->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dihapus.');
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $task);

        $newStatus = $request->validated()['status'];

        $data = ['status' => $newStatus];

        if ($newStatus === 'done') {
            $data['completed_at'] = now();
        } else {
            $data['completed_at'] = null;
        }

        $task->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $task->status->value,
                'label' => $task->status->label(),
            ]);
        }

        return redirect()->back()->with('success', 'Status tugas berhasil diperbarui.');
    }
}
