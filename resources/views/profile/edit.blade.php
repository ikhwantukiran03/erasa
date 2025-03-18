@extends('layouts.app')

@section('title', 'Edit Profile - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Edit Profile</h1>
                <p class="text-gray-600 mt-2">Update your account information</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-primary hover:underline">Back to Dashboard</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Personal Information</h2>
            </div>
            <div class="p-6">
                @if(session('status'))
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-dark font-medium mb-1">Full Name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', Auth::user()->name) }}" 
                                required 
                                class="form-input @error('name') border-red-500 @enderror" 
                            >
                            @error('name')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-dark font-medium mb-1">Email Address</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', Auth::user()->email) }}" 
                                required 
                                class="form-input @error('email') border-red-500 @enderror" 
                            >
                            @error('email')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="whatsapp" class="block text-dark font-medium mb-1">WhatsApp Number</label>
                            <input 
                                type="text" 
                                id="whatsapp" 
                                name="whatsapp" 
                                value="{{ old('whatsapp', Auth::user()->whatsapp) }}" 
                                required 
                                class="form-input @error('whatsapp') border-red-500 @enderror" 
                            >
                            @error('whatsapp')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Change Password</h3>
                        <p class="text-gray-600 text-sm mb-4">Leave these fields empty if you don't want to change your password.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="current_password" class="block text-dark font-medium mb-1">Current Password</label>
                                <input 
                                    type="password" 
                                    id="current_password" 
                                    name="current_password" 
                                    class="form-input @error('current_password') border-red-500 @enderror" 
                                >
                                @error('current_password')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="hidden md:block"></div>
                            
                            <div>
                                <label for="password" class="block text-dark font-medium mb-1">New Password</label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-input @error('password') border-red-500 @enderror" 
                                >
                                @error('password')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-dark font-medium mb-1">Confirm New Password</label>
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    class="form-input"
                                >
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="btn-primary py-2 px-6">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection