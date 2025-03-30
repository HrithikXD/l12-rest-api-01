@extends('layouts.app')

@section('title', 'Register User')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white shadow-2xl rounded-xl overflow-hidden">
            {{-- Header Section --}}
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6">
                <h1 class="text-3xl font-bold text-white">
                    Register
                </h1>
            </div>

            {{-- Form Content --}}
            <form method="POST"
                action="{{ route('register') }}"
                class="p-6">
                @csrf
                @method('POST')
                <div class="mb-6">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500
                    {{ $errors->has('name') ? 'border-red-500' : '' }}">
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500
                    {{ $errors->has('email') ? 'border-red-500' : '' }}">
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="passwprd" class="block text-gray-700 font-bold mb-2">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500
                    {{ $errors->has('password') ? 'border-red-500' : '' }}">
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>


                {{-- Action Buttons --}}
                <div class="mb-6 grid grid-cols-2 gap-4">
                    {{-- Submit Button --}}
                    <button type="submit"
                        class="bg-indigo-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg
                    transition duration-300 ease-in-out transform hover:scale-105">
                        Register
                    </button>

                    {{-- Reset Button --}}
                        <button type="reset"
                            class="bg-indigo-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg
                transition duration-300 ease-in-out transform hover:scale-105">
                            Clear Form
                        </button>
                </div>
                <a class=" text-lg font-semibold text-indigo-500 hover:text-indigo-300 transition duration-200 ease-in-out underline underline-offset-2" href="{{ route('login') }}">
                    {{ __('Already have an account? Login') }}
                </a>
            </form>
        </div>
    </div>
@endsection
