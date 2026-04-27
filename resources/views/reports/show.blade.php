<x-app-layout>
    <div class="max-w-2xl mx-auto px-6 py-8">

        <div class="mb-6">
            <a href="{{ route('reports.index') }}"
               class="text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 text-sm">
                &larr; My Reports
            </a>
        </div>

        @php
            $statusColors = [
                'pending'   => 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200',
                'reviewing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                'done'      => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                'closed'    => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300',
            ];
            $badge = $statusColors[$report->status] ?? $statusColors['closed'];
        @endphp

        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-zinc-100 dark:border-zinc-700">
                <div class="flex items-start justify-between gap-4">
                    <h1 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">{{ $report->title }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wide {{ $badge }} shrink-0">
                        {{ $report->status }}
                    </span>
                </div>
                <div class="mt-1 flex flex-wrap gap-x-4 gap-y-0.5">
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                        Submitted {{ $report->created_at->format('M j, Y \a\t g:i A') }}
                    </p>
                    @if($report->updated_at->ne($report->created_at))
                        <p class="text-xs text-zinc-400 dark:text-zinc-500">
                            Last updated {{ $report->updated_at->format('M j, Y \a\t g:i A') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="p-6">
                <h2 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-2">Your report</h2>
                <p class="text-sm text-zinc-800 dark:text-zinc-200 whitespace-pre-wrap">{{ $report->description }}</p>
            </div>

            @if($report->admin_comment)
                <div class="p-6 bg-indigo-50 dark:bg-indigo-950 border-t border-indigo-100 dark:border-indigo-900">
                    <h2 class="text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-2">Admin response</h2>
                    <p class="text-sm text-zinc-800 dark:text-zinc-200 whitespace-pre-wrap">{{ $report->admin_comment }}</p>
                </div>
            @else
                <div class="p-6 bg-zinc-50 dark:bg-zinc-900 border-t border-zinc-100 dark:border-zinc-700">
                    <p class="text-sm text-zinc-400 dark:text-zinc-500 italic">No admin response yet. We will review your report shortly.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
