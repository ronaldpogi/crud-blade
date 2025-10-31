@extends('layouts.app')

@section('content')
    @php($nextDir = $dir === 'asc' ? 'desc' : 'asc')

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">People</h1>
            <p class="text-sm text-gray-500">Manage people, their addresses, and contacts.</p>
        </div>
        <a href="{{ route('people.create') }}"
           class="inline-flex items-center rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            Create Person
        </a>
    </div>

    <form method="GET" action="{{ route('people.index') }}" class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center">
        <div class="flex-1">
            <label for="q" class="sr-only">Search</label>
            <input id="q" name="q" type="search" value="{{ $search }}"
                   placeholder="Search by name or email"
                   class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
        </div>
        <input type="hidden" name="sort" value="{{ $sort }}">
        <input type="hidden" name="dir" value="{{ $dir }}">
        <div class="flex gap-2">
            <button type="submit"
                    class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                Search
            </button>
            <a href="{{ route('people.index') }}"
               class="rounded border border-transparent px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                Reset
            </a>
        </div>
    </form>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr class="text-left text-sm font-semibold text-gray-600">
                <th class="px-4 py-3">
                    <a href="{{ route('people.index', array_filter(['q' => $search, 'sort' => 'last_name', 'dir' => $nextDir])) }}"
                       class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-500">
                        Name
                    </a>
                </th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Birthdate</th>
                <th class="px-4 py-3 text-center">Addresses</th>
                <th class="px-4 py-3">Primary Contact</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
            @forelse ($people as $person)
                <tr>
                    <td class="px-4 py-3 font-medium text-gray-900">
                        {{ $person->first_name }} {{ $person->last_name }}
                    </td>
                    <td class="px-4 py-3">{{ $person->email ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $person->birthdate ?? '—' }}</td>
                    <td class="px-4 py-3 text-center">{{ $person->addresses_count }}</td>
                    <td class="px-4 py-3">{{ $person->primary_contact ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('people.show', $person->id) }}"
                               class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                Show
                            </a>
                            <a href="{{ route('people.edit', $person->id) }}"
                               class="text-sm font-medium text-gray-600 hover:text-gray-500">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('people.destroy', $person->id) }}"
                                  onsubmit="return confirm('Delete this person?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">
                        No people found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $people->links() }}
    </div>
@endsection
