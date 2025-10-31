<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactsController extends Controller
{
    public function index(int $person)
    {
        $personRecord = $this->findPersonOrFail($person);

        $contacts = DB::table('contacts')
            ->where('person_id', $personRecord->id)
            ->orderByDesc('is_primary')
            ->orderBy('type')
            ->paginate(10)
            ->withQueryString();

        return view('contacts.index', [
            'person' => $personRecord,
            'contacts' => $contacts,
        ]);
    }

    public function create(int $person)
    {
        $personRecord = $this->findPersonOrFail($person);

        return view('contacts.create', ['person' => $personRecord]);
    }

    public function store(Request $request, int $person)
    {
        $personRecord = $this->findPersonOrFail($person);

        $data = $request->validate([
            'type' => ['required', 'string', 'max:50'],
            'value' => ['required', 'string', 'max:191'],
            'is_primary' => ['sometimes', 'boolean'],
        ]);

        $isPrimary = !empty($data['is_primary']);
        $now = now();

        DB::transaction(function () use ($personRecord, $data, $isPrimary, $now) {
            if ($isPrimary) {
                DB::table('contacts')
                    ->where('person_id', $personRecord->id)
                    ->update([
                        'is_primary' => false,
                        'updated_at' => $now,
                    ]);
            }

            DB::table('contacts')->insert([
                'person_id' => $personRecord->id,
                'type' => $data['type'],
                'value' => $data['value'],
                'is_primary' => $isPrimary,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        return redirect()
            ->route('people.contacts.index', $personRecord->id)
            ->with('success', 'Contact added successfully.');
    }

    public function edit(int $person, int $contact)
    {
        $personRecord = $this->findPersonOrFail($person);
        $contactRecord = $this->findContactOrFail($personRecord->id, $contact);

        return view('contacts.edit', [
            'person' => $personRecord,
            'contact' => $contactRecord,
        ]);
    }

    public function update(Request $request, int $person, int $contact)
    {
        $personRecord = $this->findPersonOrFail($person);
        $contactRecord = $this->findContactOrFail($personRecord->id, $contact);

        $data = $request->validate([
            'type' => ['required', 'string', 'max:50'],
            'value' => ['required', 'string', 'max:191'],
            'is_primary' => ['sometimes', 'boolean'],
        ]);

        $isPrimary = !empty($data['is_primary']);
        $now = now();

        DB::transaction(function () use ($personRecord, $contactRecord, $data, $isPrimary, $now) {
            if ($isPrimary) {
                DB::table('contacts')
                    ->where('person_id', $personRecord->id)
                    ->where('id', '!=', $contactRecord->id)
                    ->update([
                        'is_primary' => false,
                        'updated_at' => $now,
                    ]);
            }

            DB::table('contacts')
                ->where('id', $contactRecord->id)
                ->update([
                    'type' => $data['type'],
                    'value' => $data['value'],
                    'is_primary' => $isPrimary,
                    'updated_at' => $now,
                ]);
        });

        return redirect()
            ->route('people.contacts.index', $personRecord->id)
            ->with('success', 'Contact updated successfully.');
    }

    public function destroy(int $person, int $contact)
    {
        $personRecord = $this->findPersonOrFail($person);
        $contactRecord = $this->findContactOrFail($personRecord->id, $contact);

        DB::table('contacts')->where('id', $contactRecord->id)->delete();

        return redirect()
            ->route('people.contacts.index', $personRecord->id)
            ->with('success', 'Contact deleted successfully.');
    }

    public function setPrimary(int $person, int $contact)
    {
        $personRecord = $this->findPersonOrFail($person);
        $contactRecord = $this->findContactOrFail($personRecord->id, $contact);
        $now = now();

        DB::transaction(function () use ($personRecord, $contactRecord, $now) {
            DB::table('contacts')
                ->where('person_id', $personRecord->id)
                ->where('id', '!=', $contactRecord->id)
                ->update([
                    'is_primary' => false,
                    'updated_at' => $now,
                ]);

            DB::table('contacts')
                ->where('id', $contactRecord->id)
                ->update([
                    'is_primary' => true,
                    'updated_at' => $now,
                ]);
        });

        return redirect()
            ->route('people.contacts.index', $personRecord->id)
            ->with('success', 'Primary contact updated.');
    }

    protected function findPersonOrFail(int $person): object
    {
        $record = DB::table('people')->where('id', $person)->first();

        if (!$record) {
            abort(404);
        }

        return $record;
    }

    protected function findContactOrFail(int $person, int $contact): object
    {
        $contact = DB::table('contacts')
            ->where('id', $contact)
            ->where('person_id', $person)
            ->first();

        if (!$contact) {
            abort(404);
        }

        return $contact;
    }
}
