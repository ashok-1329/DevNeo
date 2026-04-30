<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────────
    // VALIDATION RULES
    // ──────────────────────────────────────────────────────────────────────────

    private function rules(bool $isUpdate = false): array
    {
        return [
            'client_name'           => 'required|string|max:255',
            'client_abn'            => 'required|digits:11',
            'client_phone'          => 'required|numeric',
            'client_representative' => 'required|string|max:255',
            'client_rep_email'      => 'required|email|max:255',
            'client_account_email'  => 'nullable|email|max:255',
            'client_terms'          => 'nullable|string|max:255',
            'client_address'        => 'required|string',
            'internal_note'         => 'nullable|string',
            'status'                => 'nullable|integer|in:0,1,2',

            'client_logo' => $isUpdate
                ? 'nullable|image|mimes:jpg,jpeg,png|max:10240'
                : 'required|image|mimes:jpg,jpeg,png|max:10240',
        ];
    }

    private function messages(): array
    {
        return [
            'client_abn.digits'            => 'ABN must be exactly 11 digits.',
            'client_phone.numeric'         => 'Phone must contain digits only.',
            'client_rep_email.email'       => 'Enter a valid representative email address.',
            'client_account_email.email'   => 'Enter a valid account email address.',
            'client_logo.required'         => 'A client logo is required.',
            'client_logo.image'            => 'Logo must be an image file.',
            'client_logo.mimes'            => 'Logo must be JPG or PNG.',
            'client_logo.max'              => 'Logo file size must not exceed 2MB.',
        ];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // INDEX
    // ──────────────────────────────────────────────────────────────────────────

    public function index()
    {
        return view('admin.clients.index');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // DATA (DataTables JSON)
    // ──────────────────────────────────────────────────────────────────────────

    public function getData()
    {
        $clients = Client::select([
            'id',
            'client_name',
            'client_abn',
            'client_phone',
            'client_representative',
            'client_rep_email',
            'client_account_email',
            'client_terms',
            'status',
        ])->orderBy('client_name')->get();

        return response()->json($clients);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // CREATE
    // ──────────────────────────────────────────────────────────────────────────

    public function create()
    {
        return view('admin.clients.create');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // STORE
    // ──────────────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        $logo = $request->file('client_logo')->store('clients', 'public');

        Client::create([
            'client_name'           => $validated['client_name'],
            'client_abn'            => $validated['client_abn'],
            'client_phone'          => $validated['client_phone'],
            'client_representative' => $validated['client_representative'],
            'client_rep_email'      => $validated['client_rep_email'],
            'client_account_email'  => $validated['client_account_email'] ?? null,
            'client_terms'          => $validated['client_terms'] ?? null,
            'client_address'        => $validated['client_address'],
            'internal_note'         => $validated['internal_note'] ?? null,
            'client_logo'           => $logo,
            'status'                => 1,
            'created_by'            => auth()->id(),
            'updated_by'            => auth()->id(),
        ]);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client created successfully.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // SHOW
    // ──────────────────────────────────────────────────────────────────────────

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return view('admin.clients.show', compact('client'));
    }

    public function getClient($id)
    {
        $client = Client::findOrFail($id);

        return response()->json($client);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // EDIT
    // ──────────────────────────────────────────────────────────────────────────

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('admin.clients.edit', compact('client'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // UPDATE
    // ──────────────────────────────────────────────────────────────────────────

    public function update(Request $request, $id)
    {
        $client    = Client::findOrFail($id);
        $validated = $request->validate($this->rules(true), $this->messages());

        $logo = $client->client_logo;

        if ($request->hasFile('client_logo')) {
            // Delete old logo from storage
            if ($logo && Storage::disk('public')->exists($logo)) {
                Storage::disk('public')->delete($logo);
            }
            $logo = $request->file('client_logo')->store('clients', 'public');
        }

        $client->update([
            'client_name'           => $validated['client_name'],
            'client_abn'            => $validated['client_abn'],
            'client_phone'          => $validated['client_phone'],
            'client_representative' => $validated['client_representative'],
            'client_rep_email'      => $validated['client_rep_email'],
            'client_account_email'  => $validated['client_account_email'] ?? null,
            'client_terms'          => $validated['client_terms'] ?? null,
            'client_address'        => $validated['client_address'],
            'internal_note'         => $validated['internal_note'] ?? null,
            'client_logo'           => $logo,
            'status'                => $validated['status'] ?? $client->status,
            'updated_by'            => auth()->id(),
        ]);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client updated successfully.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // DESTROY
    // ──────────────────────────────────────────────────────────────────────────

    public function destroy($id)
    {
        $client = Client::findOrFail($id);

        if ($client->client_logo && Storage::disk('public')->exists($client->client_logo)) {
            Storage::disk('public')->delete($client->client_logo);
        }

        $client->delete();

        return response()->json(['message' => 'Client deleted successfully.']);
    }
}
