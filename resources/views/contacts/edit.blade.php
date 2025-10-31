@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Edit Contact</h1>
            <p class="text-sm text-gray-500">
                Update contact for {{ $person->first_name }} {{ $person->last_name }}.
            </p>
        </div>
        <a href="{{ route('people.contacts.index', $person->id) }}"
           class="text-sm font-medium text-gray-600 hover:text-gray-500">
            Back to contacts
        </a>
    </div>

    <form method="POST" action="{{ route('people.contacts.update', [$person->id, $contact->id]) }}"
          class="space-y-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')

        <div class="grid gap-6">
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                <input id="type" name="type" type="text"
                       value="{{ old('type', $contact->type) }}"
                       required maxlength="50"
                       class="mt-1 w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label for="value" class="block text-sm font-medium text-gray-700">Value</label>
                <input id="value" name="value" type="text"
                       value="{{ old('value', $contact->value) }}"
                       required maxlength="191"
                       class="mt-1 w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
            <div class="flex items-center gap-2">
                <input id="is_primary" name="is_primary" type="checkbox" value="1"
                       @checked(old('is_primary', $contact->is_primary))>
                <label for="is_primary" class="text-sm text-gray-700">Set as primary contact</label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('people.contacts.index', $person->id) }}"
               class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                Cancel
            </a>
            <button type="submit"
                    class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Update
            </button>
        </div>
    </form>
@endsection
