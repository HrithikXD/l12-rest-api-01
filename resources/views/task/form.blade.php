@extends('layouts.app')

@section('title', isset($task) ? 'Edit Task' : 'Add Task')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white shadow-2xl rounded-xl overflow-hidden">
            {{-- Header Section --}}
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6">
                <h1 class="text-3xl font-bold text-white">
                    {{ isset($task) ? 'Edit Task' : 'Create New Task' }}
                </h1>
            </div>

            {{-- Form Content --}}
            <form method="POST"
                action="{{ isset($task) ? route('task.update', $task['id']) : route('task.store') }}"
                class="p-6">
                @csrf
                @isset($task)
                    @method('PUT')
                @endisset

                {{-- Title Input --}}
                <div class="mb-6">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Title</label>
                    <input type="text" name="name" id="name" value="{{ $task['name'] ?? old('name') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500
                    {{ $errors->has('name') ? 'border-red-500' : '' }}">
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description Input --}}
                <div class="mb-6">
                    <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                    <textarea name="description" id="description" rows="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500
                    {{ $errors->has('description') ? 'border-red-500' : '' }}">{{ $task['description'] ?? old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Long Description Input --}}
                <div class="mb-6">
                    <label for="long_description" class="block text-gray-700 font-bold mb-2">Long Description</label>
                    <textarea name="long_description" id="long_description" rows="5"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500
                    {{ $errors->has('long_description') ? 'border-red-500' : '' }}">{{ $task['long_description'] ?? old('long_description') }}</textarea>
                    @error('long_description')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="grid grid-cols-2 gap-4">
                    {{-- Submit Button --}}
                    <button type="submit"
                        class="bg-indigo-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg
                    transition duration-300 ease-in-out transform hover:scale-105">
                        {{ isset($task) ? 'Update Task' : 'Create Task' }}
                    </button>

                    {{-- Reset Button --}}
                    @if (!isset($task))
                        <button type="reset"
                            class="bg-indigo-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg
                transition duration-300 ease-in-out transform hover:scale-105">
                            Clear Form
                        </button>
                    @endif

                </div>
            </form>
        </div>
    </div>
@endsection
