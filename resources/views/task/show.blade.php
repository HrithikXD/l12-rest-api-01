@extends('layouts.app')

@section('title', $task['name'])

@section('content')
<div class="container mx-auto px-4 py-8">

    <div class="max-w-2xl mx-auto bg-white shadow-2xl rounded-xl overflow-hidden">
        {{-- Header Section --}}
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6">
            <h1 class="text-3xl font-bold text-white">{{ $task['name'] }}</h1>
        </div>

        {{-- Task Details Content --}}
        <div class="p-6">
            {{-- Description --}}
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-3">Description</h2>
                <p class="text-gray-600">{{ $task['description'] }}</p>
            </div>

            {{-- Long Description (if exists) --}}
            @if ($task['long_description'])
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">Additional Details</h2>
                    <p class="text-gray-600">{{ $task['long_description'] }}</p>
                </div>
            @endif

            {{-- Metadata Section --}}
            <div class="grid grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded-lg">
                <div>
                    <p class="text-sm text-gray-500">Created At</p>
                    <p class="font-medium text-gray-700">{{ $task['created_at'] }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Last Updated</p>
                    <p class="font-medium text-gray-700">{{ $task['updated_at'] }}</p>
                </div>
            </div>

            {{-- Status Badge --}}
            <div class="mb-6">
                <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold
                    {{ $task['completed'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $task['completed'] ? 'Completed' : 'In Progress' }}
                </span>
            </div>

            {{-- Action Buttons --}}
            <div class="grid grid-cols-3 gap-4">
                {{-- Edit Button --}}
                <a href="{{route('task.edit')}}" class="inline-flex items-center justify-center bg-indigo-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 text-center h-10">
                    Edit
                </a>

                {{-- Complete/Uncomplete Button --}}
                <form action="{{route('task.complete')}}" method="POST" class="w-full">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="w-full inline-flex items-center justify-center h-10
                        {{ $task['completed'] ? 'bg-indigo-500  hover:bg-yellow-700' : 'bg-indigo-500 hover:bg-green-700' }}
                        text-white font-bold py-3 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 text-center">

                        {{ $task['completed'] ? 'Incomplete' : 'Complete' }}
                    </button>
                </form>

                {{-- Delete Button --}}
                <form action="{{route('task.delete')}}" method="POST" class="w-full" onsubmit="return confirm('Are you sure you want to delete this task?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex items-center justify-center bg-indigo-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 text-center h-10">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
