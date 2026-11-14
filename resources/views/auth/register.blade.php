@extends('layouts.auth')

@section('title', 'Register')

@section('nav_button')
    <a href="{{ route('login') }}" class="bg-orange-500 text-white px-7 py-2.5 rounded-full shadow-lg shadow-orange-200">Login</a>
@endsection

@section('content')
    <h1 class="text-5xl font-extrabold text-center">Welcome</h1>
    <p class="text-gray-700 text-center mb-6">Create Teacher Account</p>

    <form action="{{ route('register') }}" method="POST" class="space-y-3">
        @csrf

        <div>
            <label class="block text-sm font-bold mb-1">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name"
                   class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email"
                   class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold mb-1">Create Password</label>
            <div class="relative">
                <input type="password" id="password" name="password"
                       class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                <button type="button" onclick="togglePassword('password')" class="absolute right-4 top-3 text-lg">👁</button>
            </div>
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold mb-1">Re-enter Password</label>
            <div class="relative">
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-4 top-3 text-lg">👁</button>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold mb-1">Role</label>
            <select name="role" class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                <option value="guru" selected>Guru</option>
            </select>
            @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="w-full mt-4 bg-white font-bold py-3 rounded-xl shadow-md hover:shadow-lg">Sign Up</button>
    </form>

    <p class="text-center text-orange-500 text-xs font-bold mt-6">Or continue with</p>
    <div class="flex gap-3 mt-4">
        <button type="button" class="flex-1 bg-white/80 py-3 rounded-xl shadow font-bold text-blue-600">G</button>
        <button type="button" class="flex-1 bg-white/80 py-3 rounded-xl shadow font-bold">Apple</button>
    </div>

    <p class="text-center text-sm text-gray-400 mt-6">
        Already have an account? <a href="{{ route('login') }}" class="text-orange-500 font-bold">Login Here</a>
    </p>
@endsection
