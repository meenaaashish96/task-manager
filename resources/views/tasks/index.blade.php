<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    {{ __('My Tasks') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Plan, track and complete your work in one place.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Top stats row (simple summary) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white shadow-sm rounded-xl px-4 py-3 border border-slate-100">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total</p>
                    <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $tasks->total() }}</p>
                </div>
                <div class="bg-white shadow-sm rounded-xl px-4 py-3 border border-slate-100">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Pending</p>
                    <p class="mt-1 text-lg font-semibold text-amber-600">
                        {{ $tasks->where('status', 'pending')->count() }}
                    </p>
                </div>
                <div class="bg-white shadow-sm rounded-xl px-4 py-3 border border-slate-100">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Completed</p>
                    <p class="mt-1 text-lg font-semibold text-emerald-600">
                        {{ $tasks->where('status', 'completed')->count() }}
                    </p>
                </div>
            </div>

            {{-- Create + filter row --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100">
                <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <h3 class="text-base font-semibold text-slate-900">Add New Task</h3>
                    <!-- Simple filter/search bar -->
                    <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap gap-3 items-end">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Status</label>
                            <select name="status" class="border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">All</option>
                                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                                <option value="in-progress" @selected(request('status') === 'in-progress')>In Progress</option>
                                <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Due Date</label>
                            <input type="date" name="due_date" value="{{ request('due_date') }}"
                                   class="border-gray-300 rounded-md shadow-sm text-sm">
                        </div>
                        <button type="submit"
                                class="inline-flex items-center px-3 py-2 bg-slate-900 text-white text-xs font-medium rounded-md hover:bg-black transition">
                            Filter
                        </button>
                    </form>
                </div>

                <div class="p-6 pt-4">
                    @if(session('success'))
                        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                            <span class="font-semibold">Success:</span> {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            <p class="font-semibold mb-1">There were some problems with your input:</p>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-slate-600">Task Title</label>
                                <input type="text" name="title" placeholder="e.g. Prepare monthly report"
                                       class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-full" required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-slate-600">Status</label>
                                <select name="status"
                                        class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-full">
                                    <option value="pending">Pending</option>
                                    <option value="in-progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-slate-600">Due Date</label>
                                <input type="date" name="due_date"
                                       class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-full" required>
                            </div>
                            <div class="space-y-1 md:col-span-1">
                                <label class="text-xs font-medium text-slate-600">Description</label>
                                <textarea name="description" rows="1" placeholder="Add a short note"
                                          class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-full"></textarea>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-indigo-700 transition">
                                Create Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tasks table --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100">
                <div class="p-6">
                    @if($tasks->count() === 0)
                        <p class="text-gray-500 text-sm">No tasks yet. Create your first task using the form above.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="bg-slate-50 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">
                                        <th class="px-3 py-2">Title</th>
                                        <th class="px-3 py-2">Status</th>
                                        <th class="px-3 py-2">Due Date</th>
                                        <th class="px-3 py-2 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($tasks as $task)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="px-3 py-2 align-top">
                                            <div class="font-semibold text-slate-900">{{ $task->title }}</div>
                                            @if($task->description)
                                                <div class="text-xs text-slate-500 mt-0.5">{{ $task->description }}</div>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 align-middle">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                                {{ $task->status == 'completed' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100' :
                                                   ($task->status == 'in-progress' ? 'bg-amber-50 text-amber-700 ring-1 ring-amber-100' : 'bg-rose-50 text-rose-700 ring-1 ring-rose-100') }}">
                                                {{ ucfirst($task->status) }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 align-middle text-slate-700">
                                            {{ $task->due_date }}
                                        </td>
                                        <td class="px-3 py-2 align-middle">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('tasks.edit', $task) }}"
                                                   class="inline-flex items-center px-2.5 py-1.5 rounded-md border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-100 transition">
                                                    Edit
                                                </a>
                                                <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this task?');">
                                                    @csrf @method('DELETE')
                                                    <button
                                                        class="inline-flex items-center px-2.5 py-1.5 rounded-md border border-red-200 text-xs font-medium text-red-600 hover:bg-red-50 transition">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $tasks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>