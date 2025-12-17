<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                    <h3 class="text-lg font-bold">Add New Task</h3>
                    <!-- Simple filter/search bar -->
                    <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap gap-2 items-end">
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
                                class="px-3 py-2 bg-gray-800 text-white text-xs rounded hover:bg-black">
                            Filter
                        </button>
                    </form>
                </div>
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <strong class="font-bold">Whoops!</strong>
                        <span class="block sm:inline">There were some problems with your input.</span>
                        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" name="title" placeholder="Task Title"
                               class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        <select name="status"
                                class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="pending">Pending</option>
                            <option value="in-progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                        <input type="date" name="due_date"
                               class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        <textarea name="description" placeholder="Description"
                                  class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    <button type="submit"
                            class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Create Task
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-xs text-gray-400 mb-2">Total tasks found: {{ $tasks->total() }}</p>
                @if($tasks->count() === 0)
                    <p class="text-gray-500">No tasks yet. Create your first task using the form above.</p>
                @else
                    <table class="min-w-full w-full">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="p-2">Title</th>
                                <th class="p-2">Status</th>
                                <th class="p-2">Due Date</th>
                                <th class="p-2 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                            <tr class="border-b">
                                <td class="p-2">
                                    <div class="font-bold">{{ $task->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $task->description }}</div>
                                </td>
                                <td class="p-2">
                                    <span class="px-2 py-1 text-xs rounded 
                                        {{ $task->status == 'completed' ? 'bg-green-200 text-green-800' : 
                                           ($task->status == 'in-progress' ? 'bg-yellow-200 text-yellow-800' : 'bg-red-200 text-red-800') }}">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </td>
                                <td class="p-2">{{ $task->due_date }}</td>
                                <td class="p-2">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('tasks.edit', $task) }}"
                                           class="text-indigo-600 hover:underline text-sm">Edit</a>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                              onsubmit="return confirm('Are you sure?');">
                                            @csrf @method('DELETE')
                                            <button class="text-red-500 hover:underline text-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $tasks->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>