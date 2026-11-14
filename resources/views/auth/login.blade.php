@extends('layouts.auth')

@section('title', 'Login')

@section('nav_button')
    <a href="{{ route('register') }}" class="bg-orange-500 text-white px-7 py-2.5 rounded-full shadow-lg shadow-orange-200">Register</a>
@endsection

@section('content')
    <h1 class="text-5xl font-extrabold">Hello!</h1>
    <p class="text-gray-700 mb-6">Welcome Back, Teacher</p>

    @if (session('success'))
        <div class="mb-4 text-sm bg-green-100 text-green-700 px-4 py-2 rounded-lg">{{ session('success') }}</div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <label class="block text-sm font-bold mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email"
               class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
        @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror

        <div class="flex justify-between mt-4 mb-1 text-sm">
            <label class="font-bold">Password</label>
            <a href="#" class="font-bold">Forgot?</a>
        </div>
        <div class="relative">
            <input type="password" id="password" name="password"
                   class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
            <button type="button" onclick="togglePassword('password')" class="absolute right-4 top-3 text-lg">👁</button>
        </div>
        @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror

        <button type="submit" class="w-full mt-8 bg-white font-bold py-3 rounded-xl shadow-md hover:shadow-lg">Sign In</button>
    </form>

    <p class="text-center text-orange-500 text-xs font-bold mt-8">Or continue with</p>
    <div class="flex gap-3 mt-4">
        <button type="button" class="flex-1 bg-white/80 py-3 rounded-xl shadow font-bold text-blue-600">G</button>
        <button type="button" class="flex-1 bg-white/80 py-3 rounded-xl shadow font-bold">Apple</button>
    </div>

    <p class="text-center text-sm text-gray-400 mt-6">
        Don't have an account? <a href="{{ route('register') }}" class="text-orange-500 font-bold">Register Now</a>
    </p>
@endsection
