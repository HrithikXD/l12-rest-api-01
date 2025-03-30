@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-center">
            <div class="w-full md:w-2/3 lg:w-2/3">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-4 bg-gray-50 border-b border-gray-200 font-medium text-lg">
                        {{ __('Dashboard') }}
                    </div>

                    <div class="p-6 bg-white">

                        <h4 class="text-xl font-semibold">Welcome, {{ Session::get('user')['name'] ?? 'User' }}!</h4>
                        <p class="mt-2">You are logged in!</p>

                        <div class="mt-6 grid md:grid-cols-2 gap-6">
                            <div class="bg-white rounded-lg border shadow-sm">
                                <div class="p-5">
                                    <h5 class="text-lg font-medium mb-2">My Profile</h5>
                                    <p class="text-gray-600 mb-4">View and update your profile information.</p>
                                    <a href="{{ route('profile') }}"
                                        class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                        Go to Profile
                                    </a>
                                </div>
                            </div>
                            @if (Session::get('user')['is_admin'])
                                <div class="bg-white rounded-lg border shadow-sm">
                                    <div class="p-5">
                                        <h5 class="text-lg font-medium mb-2">Users</h5>
                                        <p class="text-gray-600 mb-4">View all users in the system.</p>
                                        <a href="{{ route('users.index') }}"
                                            class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                            View Users
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <div class="bg-white rounded-lg border shadow-sm">
                                <div class="p-5">
                                    <h5 class="text-lg font-medium mb-2">My Tasks</h5>
                                    <p class="text-gray-600 mb-4">View and create tasks.</p>
                                    <a href="{{ route('tasks.index') }}"
                                        class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                        Go to Tasks
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
