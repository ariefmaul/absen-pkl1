@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto">
        <h2 class="text-2xl font-semibold text-center text-blue-800 mb-6">Login</h2>

        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 p-3 rounded">
                    <ul class="list-disc pl-5 mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 p-2" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 p-2" />
            </div>

            <div class="flex items-center justify-between">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="remember"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>

                <a href="#" class="text-sm text-blue-600 hover:underline">Lupa password?</a>
            </div>

            <div>
                <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-md hover:bg-blue-500">Masuk</button>
            </div>

        </form>
    </div>
@endsection
