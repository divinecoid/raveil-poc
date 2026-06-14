<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Resources\Tasks\TaskResource;
use Filament\Resources\Pages\Page;
use App\Models\Task;

class KanbanBoard extends Page
{
    protected static string $resource = TaskResource::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-view-columns';

    protected string $view = 'filament.resources.tasks.pages.kanban-board';

    public function getStatuses(): array
    {
        return [
            'todo' => 'Todo',
            'in_progress' => 'In Progress',
            'review' => 'Review',
            'done' => 'Done',
        ];
    }

    protected function getViewData(): array
    {
        $tasks = Task::with('project')->orderBy('order')->get()->groupBy('status');
        
        return [
            'tasksByStatus' => $tasks,
            'statuses' => $this->getStatuses(),
        ];
    }

    public function updateTaskStatus($taskId, $status)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->update(['status' => $status]);
        }
    }
}

