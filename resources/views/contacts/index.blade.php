@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                Contacts for {{ $person->first_name }} {{ $person->last_name }}
            </h1>
            <p class="text-sm text-gray-500">Manage contact details for this person.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('people.show', $person->id) }}"
               class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                Back to profile
            </a>
            <a href="{{ route('people.contacts.create', $person->id) }}"
               class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Add Contact
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr class="text-left text-sm font-semibold text-gray-600">
                <th class="px-4 py-3">Type</th>
                <th class="px-4 py-3">Value</th>
                <th class="px-4 py-3 text-center">Primary</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
            @forelse ($contacts as $contact)
                <tr>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ ucfirst($contact->type) }}</td>
                    <td class="px-4 py-3">{{ $contact->value }}</td>
                    <td class="px-4 py-3 text-center">
                        @if ($contact->is_primary)
                            <span class="inline-flex rounded bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                                Yes
                            </span>
                        @else
                            <span class="text-xs text-gray-500">No</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-3">
                            @unless ($contact->is_primary)
                                <form method="POST" action="{{ route('people.contacts.primary', [$person->id, $contact->id]) }}">
                                    @csrf
                                    <button type="submit"
                                            class="text-sm font-medium text-gray-700 hover:text-gray-500">
                                        Set Primary
                                    </button>
                                </form>
                            @endunless
                            <a href="{{ route('people.contacts.edit', [$person->id, $contact->id]) }}"
                               class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('people.contacts.destroy', [$person->id, $contact->id]) }}"
                                  onsubmit="return confirm('Delete this contact?');">
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
                    <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">
                        No contacts found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $contacts->links() }}
    </div>
@endsection
