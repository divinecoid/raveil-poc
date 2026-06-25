@php
    $state   = $getState();
    $key     = $record->getKey();

    $statusConfig = [
        'Unpaid'    => ['label' => 'Unpaid',    'bg' => '#fef2f2', 'text' => '#dc2626', 'border' => '#fca5a5', 'dot' => '#ef4444'],
        'Paid'      => ['label' => 'Paid',      'bg' => '#f0fdf4', 'text' => '#16a34a', 'border' => '#86efac', 'dot' => '#22c55e'],
        'Cancelled' => ['label' => 'Cancelled', 'bg' => '#f9fafb', 'text' => '#6b7280', 'border' => '#d1d5db', 'dot' => '#9ca3af'],
    ];
@endphp

<div
    x-data="{
        open: false,
        currentStatus: @js($state),
        statuses: @js($statusConfig),
        selectStatus(newStatus) {
            if (newStatus === this.currentStatus) { this.open = false; return; }
            this.open = false;
            $wire.triggerUpdateStatus(@js((string) $key), newStatus);
        }
    }"
    x-on:click.stop
    x-on:click.outside="open = false"
    style="position: relative; display: inline-block;"
>
    {{-- Trigger: colored pill badge with chevron --}}
    <button
        type="button"
        x-on:click.stop="open = !open"
        :style="`
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px 4px 8px;
            border-radius: 9999px;
            border: 1.5px solid ${statuses[currentStatus]?.border ?? '#d1d5db'};
            background: ${statuses[currentStatus]?.bg ?? '#f9fafb'};
            color: ${statuses[currentStatus]?.text ?? '#6b7280'};
            font-size: 0.72rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
            line-height: 1.4;
            outline: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        `"
    >
        {{-- Status dot --}}
        <span
            :style="`
                width: 7px;
                height: 7px;
                border-radius: 50%;
                background: ${statuses[currentStatus]?.dot ?? '#9ca3af'};
                flex-shrink: 0;
            `"
        ></span>
        {{-- Status label --}}
        <span x-text="statuses[currentStatus]?.label ?? currentStatus"></span>
        {{-- Chevron --}}
        <svg
            :style="`
                width: 11px; height: 11px;
                transition: transform 0.15s ease;
                transform: ${open ? 'rotate(180deg)' : 'rotate(0deg)'};
                flex-shrink: 0;
                opacity: 0.55;
                margin-left: 2px;
            `"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    {{-- Dropdown panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 translate-y-[-4px]"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="
            position: absolute;
            top: calc(100% + 5px);
            left: 0;
            z-index: 60;
            min-width: 155px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.12), 0 4px 10px -3px rgba(0,0,0,0.08);
            overflow: hidden;
            padding: 4px;
        "
    >
        <template x-for="(config, status) in statuses" :key="status">
            <button
                type="button"
                x-on:click.stop="selectStatus(status)"
                :style="`
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    width: 100%;
                    padding: 8px 10px;
                    border: none;
                    border-radius: 7px;
                    background: ${currentStatus === status ? config.bg : 'transparent'};
                    color: ${config.text};
                    font-size: 0.78rem;
                    font-weight: 600;
                    cursor: pointer;
                    text-align: left;
                    transition: background 0.1s ease;
                `"
                x-on:mouseenter="$el.style.background = config.bg"
                x-on:mouseleave="$el.style.background = currentStatus === status ? config.bg : 'transparent'"
            >
                {{-- Dot --}}
                <span
                    :style="`
                        width: 8px; height: 8px;
                        border-radius: 50%;
                        background: ${config.dot};
                        flex-shrink: 0;
                    `"
                ></span>
                {{-- Label --}}
                <span x-text="config.label"></span>
                {{-- Checkmark for current --}}
                <svg
                    x-show="currentStatus === status"
                    style="width: 13px; height: 13px; margin-left: auto; flex-shrink: 0; opacity: 0.8;"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </button>
        </template>
    </div>
</div>
