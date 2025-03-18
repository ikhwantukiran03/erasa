@extends('layouts.app')

@section('title', 'Register - Enak Rasa Wedding Hall')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="py-4 px-6 bg-primary text-white">
            <h2 class="text-2xl font-display font-bold text-center">Create an Account</h2>
        </div>
        <div class="py-6 px-6">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="name" class="block text-dark font-medium mb-1">Full Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus 
                        class="form-input @error('name') border-red-500 @enderror" 
                        placeholder="John Doe"
                    >
                    @error('name')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-dark font-medium mb-1">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        class="form-input @error('email') border-red-500 @enderror" 
                        placeholder="your@email.com"
                    >
                    @error('email')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="whatsapp" class="block text-dark font-medium mb-1">WhatsApp Number</label>
                    <input 
                        type="text" 
                        id="whatsapp" 
                        name="whatsapp" 
                        value="{{ old('whatsapp') }}" 
                        required 
                        class="form-input @error('whatsapp') border-red-500 @enderror" 
                        placeholder="+1234567890"
                    >
                    @error('whatsapp')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="password" class="block text-dark font-medium mb-1">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        class="form-input @error('password') border-red-500 @enderror" 
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-dark font-medium mb-1">Confirm Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required 
                        class="form-input" 
                        placeholder="••••••••"
                    >
                </div>
                
                <div class="flex flex-col space-y-4">
                    <button type="submit" class="btn-primary w-full py-2 text-center">
                        Register
                    </button>
                    
                    <div class="text-center text-sm text-gray-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-primary hover:underline">Login here</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection