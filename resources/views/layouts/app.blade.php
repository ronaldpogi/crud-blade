<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CRUD App') }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900">
    <nav class="border-b border-gray-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
            <a href="{{ route('people.index') }}" class="text-lg font-semibold text-indigo-600">
                {{ config('app.name', 'CRUD App') }}
            </a>
            <div class="space-x-4 text-sm">
                <a href="{{ route('people.index') }}"
                   class="font-medium text-gray-600 hover:text-indigo-600">
                    People
                </a>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-6xl px-4 py-6">
        @include('partials.flash')
        @yield('content')
    </main>
</body>
</html>
