@extends('layouts.app')

@section('title', 'Forgot Password - Enak Rasa Wedding Hall')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="py-4 px-6 bg-primary text-white">
            <h2 class="text-2xl font-display font-bold text-center">Forgot Password</h2>
        </div>
        <div class="py-6 px-6">
            @if(session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ url('/forgot-password') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-dark font-medium mb-1">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        class="form-input @error('email') border-red-500 @enderror" 
                        placeholder="your@email.com"
                    >
                    @error('email')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn-primary w-full py-2 text-center">
                    Send New Password
                </button>
                <div class="text-center text-sm text-gray-600 mt-4">
                    <a href="{{ route('login') }}" class="text-primary hover:underline">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 