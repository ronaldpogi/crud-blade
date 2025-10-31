@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                {{ $person->first_name }} {{ $person->last_name }}
            </h1>
            <p class="text-sm text-gray-500">Review profile details, addresses, and contacts.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('people.index') }}"
               class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                Back to list
            </a>
            <a href="{{ route('people.edit', $person->id) }}"
               class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Edit Person
            </a>
            <form method="POST" action="{{ route('people.destroy', $person->id) }}"
                  onsubmit="return confirm('Delete this person and all related records?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="rounded border border-red-500 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="mb-8 grid gap-6 lg:grid-cols-3">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm lg:col-span-2">
            <h2 class="text-lg font-semibold text-gray-800">Profile</h2>
            <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-800">{{ $person->email ?? 'Not provided' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Birthdate</dt>
                    <dd class="mt-1 text-sm text-gray-800">{{ $person->birthdate ?? 'Not provided' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Addresses</dt>
                    <dd class="mt-1 text-sm text-gray-800">{{ $addressesCount }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Contacts</dt>
                    <dd class="mt-1 text-sm text-gray-800">{{ $contactsCount }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Primary Contact</dt>
                    <dd class="mt-1 text-sm text-gray-800">
                        {{ optional($primaryContact)->value ?? 'Not set' }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Addresses</h2>
                    <p class="text-xs text-gray-500">Manage mailing addresses for this person.</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('people.addresses.index', $person->id) }}"
                       class="rounded border border-gray-300 px-3 py-1 text-xs font-medium text-gray-700 hover:bg-gray-100">
                        View All
                    </a>
                    <a href="{{ route('people.addresses.create', $person->id) }}"
                       class="rounded bg-indigo-600 px-3 py-1 text-xs font-medium text-white hover:bg-indigo-700">
                        Add Address
                    </a>
                </div>
            </div>

            @forelse ($addresses as $address)
                <div class="border-t border-gray-100 py-4 first:border-t-0 first:pt-0">
                    <p class="text-sm font-medium text-gray-800">{{ $address->line1 }}</p>
                    @if ($address->line2)
                        <p class="text-sm text-gray-600">{{ $address->line2 }}</p>
                    @endif
                    <p class="text-sm text-gray-600">{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                    <div class="mt-3 flex gap-3 text-xs font-medium">
                        <a href="{{ route('people.addresses.edit', [$person->id, $address->id]) }}"
                           class="text-indigo-600 hover:text-indigo-500">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('people.addresses.destroy', [$person->id, $address->id]) }}"
                              onsubmit="return confirm('Delete this address?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-500">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No addresses on file.</p>
            @endforelse
        </section>

        <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Contacts</h2>
                    <p class="text-xs text-gray-500">Phone numbers, emails, and other contact info.</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('people.contacts.index', $person->id) }}"
                       class="rounded border border-gray-300 px-3 py-1 text-xs font-medium text-gray-700 hover:bg-gray-100">
                        View All
                    </a>
                    <a href="{{ route('people.contacts.create', $person->id) }}"
                       class="rounded bg-indigo-600 px-3 py-1 text-xs font-medium text-white hover:bg-indigo-700">
                        Add Contact
                    </a>
                </div>
            </div>

            @forelse ($contacts as $contact)
                <div class="border-t border-gray-100 py-4 first:border-t-0 first:pt-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-800">
                            {{ ucfirst($contact->type) }}
                        </p>
                        @if ($contact->is_primary)
                            <span class="rounded bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                                Primary
                            </span>
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-gray-600">{{ $contact->value }}</p>
                    <div class="mt-3 flex flex-wrap gap-3 text-xs font-medium">
                        <a href="{{ route('people.contacts.edit', [$person->id, $contact->id]) }}"
                           class="text-indigo-600 hover:text-indigo-500">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('people.contacts.destroy', [$person->id, $contact->id]) }}"
                              onsubmit="return confirm('Delete this contact?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-500">
                                Delete
                            </button>
                        </form>
                        @unless ($contact->is_primary)
                            <form method="POST" action="{{ route('people.contacts.primary', [$person->id, $contact->id]) }}">
                                @csrf
                                <button type="submit"
                                        class="rounded border border-gray-300 px-3 py-1 text-xs font-medium text-gray-700 hover:bg-gray-100">
                                    Set Primary
                                </button>
                            </form>
                        @endunless
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No contacts on file.</p>
            @endforelse
        </section>
    </div>
@endsection
