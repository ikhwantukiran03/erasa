@extends('layouts.app')

@section('title', 'Create User - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Create New User</h1>
                <p class="text-gray-600 mt-2">Add a new user to the system</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-primary hover:underline">Back to Users</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                @if($errors->any())
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                        <p class="font-bold">Please fix the following errors:</p>
                        <ul class="list-disc ml-4 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-dark font-medium mb-1">Full Name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required 
                                class="form-input @error('name') border-red-500 @enderror" 
                                placeholder="John Doe"
                            >
                        </div>
                        
                        <div>
                            <label for="email" class="block text-dark font-medium mb-1">Email Address</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                class="form-input @error('email') border-red-500 @enderror" 
                                placeholder="user@example.com"
                            >
                        </div>
                        
                        <div>
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
                        </div>
                        
                        <div>
                            <label for="role" class="block text-dark font-medium mb-1">Role</label>
                            <select 
                                id="role" 
                                name="role" 
                                required 
                                class="form-input @error('role') border-red-500 @enderror"
                            >
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="password" class="block text-dark font-medium mb-1">Password</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required 
                                class="form-input @error('password') border-red-500 @enderror" 
                                placeholder="••••••••"
                            >
                        </div>
                        
                        <div>
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
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection