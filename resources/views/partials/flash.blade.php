@if (session('success'))
    <div class="mb-4 rounded border border-green-400 bg-green-50 px-4 py-3 text-green-700">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 rounded border border-red-400 bg-red-50 px-4 py-3 text-red-700">
        <p class="font-semibold">Please fix the following issues:</p>
        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
