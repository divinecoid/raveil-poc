<x-filament-panels::page>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    
    <style>
        .kanban-board {
            display: flex;
            gap: 1.5rem;
            overflow-x: auto;
            padding-bottom: 1.5rem;
            align-items: flex-start;
        }
        .kanban-col {
            flex-shrink: 0;
            width: 320px;
            background-color: #f3f4f6;
            border-radius: 1rem;
            padding: 1.25rem;
            border: 1px solid #e5e7eb;
        }
        .kanban-col-title {
            font-weight: 700;
            font-size: 1.125rem;
            margin-bottom: 1.25rem;
            color: #1f2937;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .kanban-card {
            background-color: #ffffff;
            padding: 1.25rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e5e7eb;
            cursor: grab;
            color: #111827;
            transition: all 0.2s ease-in-out;
        }
        .kanban-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-color: #d1d5db;
        }
        .kanban-card:active {
            cursor: grabbing;
        }
        .kanban-card h4 {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.375rem;
            line-height: 1.4;
        }
        .kanban-card p {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }
        
        /* Dark mode overrides using Filament's .dark class */
        .dark .kanban-col {
            background-color: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.08);
        }
        .dark .kanban-col-title {
            color: #f9fafb;
        }
        .dark .kanban-card {
            background-color: #18181b; /* Zinc 900 */
            border-color: rgba(255, 255, 255, 0.1);
            color: #f9fafb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        }
        .dark .kanban-card:hover {
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        }
        .dark .kanban-card p {
            color: #a1a1aa; /* Zinc 400 */
        }
        .sortable-ghost {
            opacity: 0.4;
            background-color: #e5e7eb;
        }
        .dark .sortable-ghost {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>

    <div class="kanban-board" x-data="{
        initSortable(el, status) {
            if (typeof Sortable === 'undefined') {
                setTimeout(() => this.initSortable(el, status), 100);
                return;
            }
            new Sortable(el, {
                group: 'tasks',
                animation: 200,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                onEnd: (evt) => {
                    if (evt.from === evt.to && evt.oldIndex === evt.newIndex) return;
                    let taskId = evt.item.dataset.taskId;
                    let newStatus = evt.to.dataset.status;
                    $wire.updateTaskStatus(taskId, newStatus);
                }
            });
        }
    }">
        @foreach ($statuses as $status => $statusLabel)
            <div class="kanban-col">
                <div class="kanban-col-title">
                    <span>{{ $statusLabel }}</span>
                    <span style="font-size: 0.75rem; background: rgba(156,163,175,0.2); padding: 0.125rem 0.5rem; border-radius: 999px;">
                        {{ count($tasksByStatus[$status] ?? []) }}
                    </span>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 0.875rem; min-height: 200px;"
                     x-init="initSortable($el, '{{ $status }}')"
                     data-status="{{ $status }}">
                    @foreach ($tasksByStatus[$status] ?? [] as $task)
                        <div data-task-id="{{ $task->id }}" class="kanban-card">
                            <h4>{{ $task->title }}</h4>
                            <p>{{ $task->project ? $task->project->name : 'No Project' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
