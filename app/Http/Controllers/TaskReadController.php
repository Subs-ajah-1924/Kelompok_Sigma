<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;

class TaskReadController extends Controller
{
    public function __invoke(Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $task->update(['is_read' => true]);

        return redirect()->route('tasks.show', $task);
    }
}
