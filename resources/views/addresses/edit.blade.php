@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Edit Address</h1>
            <p class="text-sm text-gray-500">
                Update address for {{ $person->first_name }} {{ $person->last_name }}.
            </p>
        </div>
        <a href="{{ route('people.addresses.index', $person->id) }}"
           class="text-sm font-medium text-gray-600 hover:text-gray-500">
            Back to addresses
        </a>
    </div>

    <form method="POST" action="{{ route('people.addresses.update', [$person->id, $address->id]) }}"
          class="space-y-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')

        <div class="grid gap-6">
            <div>
                <label for="line1" class="block text-sm font-medium text-gray-700">Address Line 1</label>
                <input id="line1" name="line1" type="text"
                       value="{{ old('line1', $address->line1) }}"
                       required maxlength="191"
                       class="mt-1 w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label for="line2" class="block text-sm font-medium text-gray-700">Address Line 2</label>
                <input id="line2" name="line2" type="text"
                       value="{{ old('line2', $address->line2) }}"
                       maxlength="191"
                       class="mt-1 w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                    <input id="city" name="city" type="text"
                           value="{{ old('city', $address->city) }}"
                           required maxlength="100"
                           class="mt-1 w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="province" class="block text-sm font-medium text-gray-700">Province</label>
                    <input id="province" name="province" type="text"
                           value="{{ old('province', $address->province) }}"
                           required maxlength="100"
                           class="mt-1 w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>
            <div>
                <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                <input id="postal_code" name="postal_code" type="text"
                       value="{{ old('postal_code', $address->postal_code) }}"
                       required maxlength="20"
                       class="mt-1 w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('people.addresses.index', $person->id) }}"
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
