<x-filament-widgets::widget>
<style>
    .or-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .or-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
    }

    .or-title {
        font-size: 14px;
        font-weight: 500;
        color: #111827;
    }

    .or-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .or-badge-pending  { background: #fef3c7; color: #b45309; }
    .or-badge-reviewing { background: #dbeafe; color: #1d4ed8; }
    .or-badge-done     { background: #d1fae5; color: #065f46; }

    .or-empty {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 32px 20px;
        border: 2px dashed #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        color: #9ca3af;
    }

    /* Dark mode */
    .dark .or-row {
        background: #141414;
        border-color: #2a2a2a;
    }
    .dark .or-title { color: #f5f5f5; }
    .dark .or-badge-pending  { background: rgba(245,158,11,0.12); color: #fbbf24; }
    .dark .or-badge-reviewing { background: rgba(59,130,246,0.12); color: #60a5fa; }
    .dark .or-badge-done     { background: rgba(16,185,129,0.12); color: #34d399; }
    .dark .or-empty { border-color: #2a2a2a; color: #525252; }
</style>

    <x-filament::section>
        <x-slot name="heading">Open Reports</x-slot>

        <div class="or-list">
            @forelse($reports as $report)
                <div class="or-row">
                    <span class="or-title">{{ $report->title }}</span>
                    <span class="or-badge or-badge-{{ $report->status }}">
                        {{ ucfirst($report->status) }}
                    </span>
                </div>
            @empty
                <div class="or-empty">No open reports</div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
