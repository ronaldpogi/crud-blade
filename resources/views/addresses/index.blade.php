@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                Addresses for {{ $person->first_name }} {{ $person->last_name }}
            </h1>
            <p class="text-sm text-gray-500">Manage mailing addresses for this person.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('people.show', $person->id) }}"
               class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                Back to profile
            </a>
            <a href="{{ route('people.addresses.create', $person->id) }}"
               class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Add Address
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr class="text-left text-sm font-semibold text-gray-600">
                <th class="px-4 py-3">Address</th>
                <th class="px-4 py-3">City</th>
                <th class="px-4 py-3">Province</th>
                <th class="px-4 py-3">Postal Code</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
            @forelse ($addresses as $address)
                <tr>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900">{{ $address->line1 }}</div>
                        @if ($address->line2)
                            <div class="text-gray-600">{{ $address->line2 }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $address->city }}</td>
                    <td class="px-4 py-3">{{ $address->province }}</td>
                    <td class="px-4 py-3">{{ $address->postal_code }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('people.addresses.edit', [$person->id, $address->id]) }}"
                               class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('people.addresses.destroy', [$person->id, $address->id]) }}"
                                  onsubmit="return confirm('Delete this address?');">
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
                    <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                        No addresses found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $addresses->links() }}
    </div>
@endsection
