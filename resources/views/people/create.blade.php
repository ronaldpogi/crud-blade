@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Create Person</h1>
            <p class="text-sm text-gray-500">Add a new person to the directory.</p>
        </div>
        <a href="{{ route('people.index') }}"
           class="text-sm font-medium text-gray-600 hover:text-gray-500">
            Back to list
        </a>
    </div>

    <form method="POST" action="{{ route('people.store') }}"
          class="space-y-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        @csrf

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}"
                       required maxlength="100"
                       class="mt-1 w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}"
                       required maxlength="100"
                       class="mt-1 w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}"
                       maxlength="191"
                       class="mt-1 w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label for="birthdate" class="block text-sm font-medium text-gray-700">Birthdate</label>
                <input id="birthdate" name="birthdate" type="date" value="{{ old('birthdate') }}"
                       class="mt-1 w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('people.index') }}"
               class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                Cancel
            </a>
            <button type="submit"
                    class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Save
            </button>
        </div>
    </form>
@endsection
