@extends('layouts.app')

@section('title', 'Task Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        {{-- Header Section --}}
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Task Management
                </h1>
                <a href="{{route('task.create')}}" class="inline-flex items-center px-4 py-2 bg-white text-indigo-700 font-medium rounded-md shadow hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    New Task
                </a>
            </div>
        </div>

        {{-- Stats Summary --}}
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Tasks</p>
                    <p class="mt-1 text-xl font-semibold text-indigo-600">{{ count($tasks) }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Completed</p>
                    <p class="mt-1 text-xl font-semibold text-green-600">
                        {{ count(array_filter($tasks, function($t) { return $t['completed']; })) }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending</p>
                    <p class="mt-1 text-xl font-semibold text-amber-600">
                        {{ count(array_filter($tasks, function($t) { return !$t['completed']; })) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Tasks List --}}
        <div>
            <ul role="list" class="divide-y divide-gray-200">
                @forelse ($tasks as $task)
                    <li class="relative hover:bg-gray-50">
                        <a href="{{ route('tasks.show', $task['id']) }}" class="block px-6 py-5 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                            <div class="flex items-center justify-between">
                                <div class="min-w-0 flex-1">
                                    <p class="text-base font-medium {{ $task['completed'] ? 'text-gray-400 line-through' : 'text-gray-900' }}">
                                        {{ $task['name'] }}
                                    </p>
                                    @if(isset($task['due_date']))
                                    <p class="mt-1 text-sm text-gray-500">
                                        Due: {{ \Carbon\Carbon::parse($task['due_date'])->format('M d, Y') }}
                                    </p>
                                    @endif
                                </div>
                                <div class="ml-5 flex-shrink-0 flex">
                                    @if($task['completed'])
                                        <span class="flex-shrink-0 inline-block px-2 py-0.5 text-green-800 text-xs font-medium bg-green-100 rounded-full">Completed</span>
                                    @else
                                        <span class="flex-shrink-0 inline-block px-2 py-0.5 text-amber-800 text-xs font-medium bg-amber-100 rounded-full">Pending</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-1">
                                <p class="text-sm text-gray-500 line-clamp-2">
                                    {{ isset($task['description']) ? $task['description'] : 'No description provided' }}
                                </p>
                            </div>
                        </a>
                    </li>
                @empty
                    <div class="text-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No tasks found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Get started by creating a new task.
                        </p>
                        <div class="mt-6">
                            <a href="{{route('task.create')}}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                New Task
                            </a>
                        </div>
                    </div>
                @endforelse
            </ul>
        </div>

        {{-- Pagination --}}
        {{-- @if ($tasks->count())
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                {{ $tasks->links() }}
            </div>
        @endif --}}
    </div>
</div>
@endsection
