<x-app-layout>
    <div class="max-w-4xl mx-auto px-6 py-8">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">My Reports</h1>
            <a href="{{ route('reports.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                Submit a Report
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 px-4 py-3 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($reports->isEmpty())
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-8 text-center shadow-sm">
                <p class="text-zinc-500 dark:text-zinc-400">You have not submitted any reports yet.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($reports as $report)
                    @php
                        $statusColors = [
                            'pending'   => 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200',
                            'reviewing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                            'done'      => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                            'closed'    => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300',
                        ];
                        $badge = $statusColors[$report->status] ?? $statusColors['closed'];
                    @endphp
                    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('reports.show', $report) }}"
                                   class="text-base font-semibold text-zinc-900 dark:text-zinc-100 hover:text-indigo-600 dark:hover:text-indigo-400 truncate block">
                                    {{ $report->title }}
                                </a>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                    Submitted {{ $report->created_at->format('M j, Y') }}
                                </p>
                                @if($report->updated_at->ne($report->created_at))
                                    <p class="text-xs text-zinc-400 dark:text-zinc-500">
                                        Last updated {{ $report->updated_at->format('M j, Y \a\t g:i A') }}
                                    </p>
                                @endif
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wide {{ $badge }} shrink-0">
                                {{ $report->status }}
                            </span>
                        </div>

                        @if($report->admin_comment)
                            <div class="mt-3 pt-3 border-t border-zinc-100 dark:border-zinc-700">
                                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Admin response:</p>
                                <p class="text-sm text-zinc-700 dark:text-zinc-300">{{ $report->admin_comment }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
