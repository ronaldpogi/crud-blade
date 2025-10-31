<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeopleController extends Controller
{
    public function index(Request $request)
    {
        $search    = $request->input('q');
        $sortField = $request->input('sort', 'last_name');
        $sortDir   = strtolower($request->input('dir', 'asc'));

        if ($sortField !== 'last_name') {
            $sortField = 'last_name';
        }
        if (!in_array($sortDir, ['asc', 'desc'], true)) {
            $sortDir = 'asc';
        }

        $query = DB::table('people as p')
            ->leftJoin('addresses as a', 'a.person_id', '=', 'p.id')
            ->select('p.*')
            ->selectRaw('COUNT(a.id) as addresses_count')
            ->selectSub(function ($sub) {
                $sub->from('contacts')
                    ->select('value')
                    ->whereColumn('contacts.person_id', 'p.id')
                    ->where('contacts.is_primary', 1)
                    ->orderByDesc('contacts.created_at')
                    ->limit(1);
            }, 'primary_contact');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $like = '%' . $search . '%';
                $q->where('p.first_name', 'like', $like)
                ->orWhere('p.last_name',  'like', $like)
                ->orWhere('p.email',      'like', $like);
            });
        }

        $people = $query
            ->groupBy('p.id')
            ->orderBy('p.' . $sortField, $sortDir)
            ->paginate(15)
            ->withQueryString();

        return view('people.index', [
            'people' => $people,
            'search' => $search,
            'sort'   => $sortField,
            'dir'    => $sortDir,
        ]);
    }

    public function create()
    {
        return view('people.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:191', 'unique:people,email'],
            'birthdate' => ['nullable', 'date'],
        ]);

        $now = now();

        $id = DB::table('people')->insertGetId([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'] ?? null,
            'birthdate' => $data['birthdate'] ?? null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return redirect()
            ->route('people.show', $id)
            ->with('success', 'Person created successfully.');
    }

    public function show(int $person)
    {
        $personRecord = DB::table('people as p')
            ->where('p.id', $person)
            ->select('p.*')
            ->selectSub(function ($sub) {
                $sub->from('addresses')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('addresses.person_id', 'p.id');
            }, 'addresses_count')
            ->selectSub(function ($sub) {
                $sub->from('contacts')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('contacts.person_id', 'p.id');
            }, 'contacts_count')
            ->selectSub(function ($sub) {
                $sub->from('contacts')
                    ->select('value')
                    ->whereColumn('contacts.person_id', 'p.id')
                    ->where('contacts.is_primary', 1) // true -> 1 for MySQL
                    ->orderByDesc('contacts.created_at')
                    ->limit(1);
            }, 'primary_contact_value')
            ->first();

        if (!$personRecord) {
            abort(404);
        }

        $addresses = DB::table('addresses')
            ->where('person_id', $personRecord->id)
            ->orderByDesc('created_at')
            ->get();

        $contacts = DB::table('contacts')
            ->where('person_id', $personRecord->id)
            ->orderByDesc('is_primary')
            ->orderBy('type')
            ->orderByDesc('created_at')
            ->get();

        $primaryContact = $personRecord->primary_contact_value
            ?? optional($contacts->first())->value;

        return view('people.show', [
            'person'          => $personRecord,
            'addressesCount'  => (int) $personRecord->addresses_count,
            'contactsCount'   => (int) $personRecord->contacts_count,
            'primaryContact'  => $primaryContact,
            'addresses'       => $addresses,
            'contacts'        => $contacts,
        ]);
    }

    public function edit(int $person)
    {
        $personRecord = $this->findPersonOrFail($person);

        return view('people.edit', ['person' => $personRecord]);
    }

    public function update(Request $request, int $person)
    {
        $personRecord = $this->findPersonOrFail($person);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:191', 'unique:people,email,' . $personRecord->id],
            'birthdate' => ['nullable', 'date'],
        ]);

        DB::table('people')
            ->where('id', $personRecord->id)
            ->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'] ?? null,
                'birthdate' => $data['birthdate'] ?? null,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('people.show', $personRecord->id)
            ->with('success', 'Person updated successfully.');
    }

    public function destroy(int $person)
    {
        $personRecord = $this->findPersonOrFail($person);

        DB::table('people')->where('id', $personRecord->id)->delete();

        return redirect()
            ->route('people.index')
            ->with('success', 'Person deleted successfully.');
    }

    protected function findPersonOrFail(int $person): object
    {
        $record = DB::table('people')->where('id', $person)->first();

        if (!$record) {
            abort(404);
        }

        return $record;
    }
}
