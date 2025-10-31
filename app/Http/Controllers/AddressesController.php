<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressesController extends Controller
{
    public function index(int $person)
    {
        $personRecord = $this->findPersonOrFail($person);

        $addresses = DB::table('addresses')
            ->where('person_id', $personRecord->id)
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('addresses.index', [
            'person' => $personRecord,
            'addresses' => $addresses,
        ]);
    }

    public function create(int $person)
    {
        $personRecord = $this->findPersonOrFail($person);

        return view('addresses.create', ['person' => $personRecord]);
    }

    public function store(Request $request, int $person)
    {
        $personRecord = $this->findPersonOrFail($person);

        $data = $request->validate([
            'line1' => ['required', 'string', 'max:191'],
            'line2' => ['nullable', 'string', 'max:191'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
        ]);

        $now = now();

        DB::table('addresses')->insert([
            'person_id' => $personRecord->id,
            'line1' => $data['line1'],
            'line2' => $data['line2'] ?? null,
            'city' => $data['city'],
            'province' => $data['province'],
            'postal_code' => $data['postal_code'],
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return redirect()
            ->route('people.addresses.index', $personRecord->id)
            ->with('success', 'Address added successfully.');
    }

    public function edit(int $person, int $address)
    {
        $personRecord = $this->findPersonOrFail($person);
        $addressRecord = $this->findAddressOrFail($personRecord->id, $address);

        return view('addresses.edit', [
            'person' => $personRecord,
            'address' => $addressRecord,
        ]);
    }

    public function update(Request $request, int $person, int $address)
    {
        $personRecord = $this->findPersonOrFail($person);
        $addressRecord = $this->findAddressOrFail($personRecord->id, $address);

        $data = $request->validate([
            'line1' => ['required', 'string', 'max:191'],
            'line2' => ['nullable', 'string', 'max:191'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
        ]);

        DB::table('addresses')
            ->where('id', $addressRecord->id)
            ->update([
                'line1' => $data['line1'],
                'line2' => $data['line2'] ?? null,
                'city' => $data['city'],
                'province' => $data['province'],
                'postal_code' => $data['postal_code'],
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('people.addresses.index', $personRecord->id)
            ->with('success', 'Address updated successfully.');
    }

    public function destroy(int $person, int $address)
    {
        $personRecord = $this->findPersonOrFail($person);
        $addressRecord = $this->findAddressOrFail($personRecord->id, $address);

        DB::table('addresses')->where('id', $addressRecord->id)->delete();

        return redirect()
            ->route('people.addresses.index', $personRecord->id)
            ->with('success', 'Address deleted successfully.');
    }

    protected function findPersonOrFail(int $person): object
    {
        $record = DB::table('people')->where('id', $person)->first();

        if (!$record) {
            abort(404);
        }

        return $record;
    }

    protected function findAddressOrFail(int $person, int $address): object
    {
        $address = DB::table('addresses')
            ->where('id', $address)
            ->where('person_id', $person)
            ->first();

        if (!$address) {
            abort(404);
        }

        return $address;
    }
}
