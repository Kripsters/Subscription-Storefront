<x-app-layout>
    <div class="max-w-2xl mx-auto px-6 py-8">

        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('reports.index') }}"
               class="text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 text-sm">
                &larr; My Reports
            </a>
        </div>

        <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 mb-6">Submit a Report</h1>

        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
            <form method="POST" action="{{ route('reports.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="title"
                        name="title"
                        type="text"
                        value="{{ old('title') }}"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('title') border-red-500 @enderror"
                        placeholder="Brief summary of your issue"
                    >
                    @error('title')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="6"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('description') border-red-500 @enderror"
                        placeholder="Please describe your issue in detail..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('reports.index') }}"
                       class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-700 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-600 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
