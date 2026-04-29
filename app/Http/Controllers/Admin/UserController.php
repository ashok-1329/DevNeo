<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificationTitle;
use App\Models\Role;
use App\Models\User;
use App\Models\UserCertification;
use App\Models\UserContract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /* ============================================================
       INDEX
    ============================================================ */
    public function index()
    {
        return view('admin.users.index');
    }

    public function getUsers()
    {
        $users = User::with('role')->latest()->get();
        return response()->json($users);
    }

    public function getUserFullData($id)
    {
        $user = User::with(['certifications.title', 'contract'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'data'    => [
                'certs' => $user->certifications->map(function ($c) {
                    return [
                        'id'          => $c->id,
                        'title'       => $c->custom_title ?: optional($c->title)->name ?? '-',
                        'expiry_date' => $c->expiry_date
                            ? Carbon::parse($c->expiry_date)->format('d/m/Y')
                            : '',
                        'file'        => $c->file,
                    ];
                }),
                'contract' => $user->contract,
            ],
        ]);
    }

    /* ============================================================
       CREATE
    ============================================================ */
    public function create()
    {
        // Clean stale drafts older than 24 hours
        User::where('created_by', auth()->id())
            ->where('status', 'draft')
            ->where('updated_at', '<', now()->subHours(24))
            ->whereDoesntHave('dockets') // 👈 IMPORTANT
            ->delete();

        $draft = User::where('created_by', auth()->id())
            ->where('status', 'draft')
            ->with(['certifications.title', 'contract'])
            ->latest()
            ->first();

        $roles  = Role::all();
        $titles = CertificationTitle::all();

        // ── Format draft certs for JS ──────────────────────────
        $existingCerts = null;
        if ($draft && $draft->certifications->count()) {
            $existingCerts = $draft->certifications->map(fn($c) => [
                'id'          => $c->id,
                'title'       => $c->custom_title ?: optional($c->title)->name ?? '—',
                'expiry_date' => $c->expiry_date
                    ? Carbon::parse($c->expiry_date)->format('d/m/Y')
                    : '',
                'file'        => $c->file,
            ])->values();
        }
        // ── Format draft contract for JS ───────────────────────
        $existingContract = null;
        if ($draft && $draft->contract) {
            $c = $draft->contract;
            $existingContract = [
                'employment_type'    => $c->employment_name,
                'hourly_rate'        => $c->salary_rate,
                'payment_frequency'  => $c->payment_made,
                'timesheet_required' => $c->timesheet,
                'staff_type'         => $c->staff,
                'contract_file'      => $c->file_path,
                'notes'              => $c->notes,
            ];
        }

        $existingRole = $draft?->role_id;

        // ── Format draft user dates for JS ─────────────────────
        $existingUserJs = null;
        if ($draft) {
            $existingUserJs = [
                'id'          => $draft->id,
                'first_name'  => $draft->first_name,
                'last_name'   => $draft->last_name,
                'email'       => $draft->email,
                'start_date'  => $draft->start_date
                    ? Carbon::parse($draft->start_date)->format('d/m/Y')
                    : '',
                'finish_date' => $draft->finish_date
                    ? Carbon::parse($draft->finish_date)->format('d/m/Y')
                    : '',
            ];
        }

        return view('admin.users.create', compact(
            'draft',
            'roles',
            'titles',
            'existingUserJs',
            'existingCerts',
            'existingContract',
            'existingRole'
        ));
    }

    /* ============================================================
       SHOW
    ============================================================ */
    public function show($id)
    {
        $user = User::with(['role', 'certifications.title', 'contract'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /* ============================================================
       EDIT
    ============================================================ */
    public function edit($id)
    {
        $user           = User::with(['role', 'certifications.title', 'contract'])->findOrFail($id);
        $roles          = Role::all();
        $titles         = CertificationTitle::all();
        $certifications = UserCertification::where('user_id', $id)->with('title')->get();

        $completedSteps = [1];
        if ($user->certifications->count() > 0) $completedSteps[] = 2;
        if ($user->contract)                     $completedSteps[] = 3;
        if ($user->role_id)                      $completedSteps[] = 4;

        // ── Format certs for JS ───────────────────────────────
        $existingCerts = $certifications->map(fn($c) => [
            'id'          => $c->id,
            'title'       => $c->custom_title ?: optional($c->title)->name ?? '—',
            'expiry_date' => $c->expiry_date
                ? Carbon::parse($c->expiry_date)->format('d/m/Y')
                : '',
            'file'        => $c->file,
        ])->values();

        // ── Format contract for JS (map DB cols → JS keys) ───
        $existingContract = null;
        if ($user->contract) {
            $c = $user->contract;
            $existingContract = [
                'employment_type'    => $c->employment_name,
                'hourly_rate'        => $c->salary_rate,
                'payment_frequency'  => $c->payment_made,
                'timesheet_required' => $c->timesheet,
                'staff_type'         => is_string($c->staff)
                    ? json_decode($c->staff, true)
                    : ($c->staff ?? []),
                'contract_file'      => $c->file_path,
                'notes'              => $c->notes,
            ];
        }

        // ── Format user dates for JS ─────────────────────────
        $existingUserJs = [
            'id'          => $user->id,
            'first_name'  => $user->first_name,
            'last_name'   => $user->last_name,
            'email'       => $user->email,
            'start_date'  => $user->start_date
                ? Carbon::parse($user->start_date)->format('d/m/Y')
                : '',
            'finish_date' => $user->finish_date
                ? Carbon::parse($user->finish_date)->format('d/m/Y')
                : '',
        ];

        return view('admin.users.edit', compact(
            'user',
            'roles',
            'titles',
            'certifications',
            'completedSteps',
            'existingCerts',
            'existingContract',
            'existingUserJs'
        ));
    }

    /* ============================================================
       STEP HANDLER  (create & edit share this)
    ============================================================ */
    public function handleStep(Request $request)
    {
        $step   = $request->step;
        $authId = auth()->id();

        /* ─────────────────────────────────────────────────────
         | CERT (MODAL)
         ─────────────────────────────────────────────────────*/
        if ($step === 'cert') {

            $request->validate([
                'user_id'     => ['required', 'exists:users,id'],
            ]);

            $cert = UserCertification::create([
                'user_id'      => $request->user_id,
                'title_id'     => $request->title_id !== 'other' ? $request->title_id : null,
                'custom_title' => $request->title_id === 'other' ? $request->custom_title : null,
                'expiry_date'  => formatToDbDate($request->expiry_date),
                'file'         => $request->file ?? null,
            ]);

            $title = $cert->title_id
                ? CertificationTitle::find($cert->title_id)
                : null;

            return response()->json([
                'success' => true,
                'cert'    => [
                    'id'          => $cert->id,
                    'title'       => $cert->custom_title ?: optional($title)->name ?? '—',
                    'expiry_date' => $cert->expiry_date
                        ? Carbon::parse($cert->expiry_date)->format('d/m/Y')
                        : '—',
                    'file'        => $cert->file,
                ],
            ]);
        }

        /* ─────────────────────────────────────────────────────
         | STEP 1 — PERSONAL DETAILS
         ─────────────────────────────────────────────────────*/
        if ((int) $step === 1) {

            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name'  => ['required', 'string', 'max:255'],
                'email'      => ['required', 'email', 'max:255'],
            ]);

            $emailExists = User::where('email', $request->email)
                ->when($request->user_id, fn($q) => $q->where('id', '!=', $request->user_id))
                ->exists();

            if ($emailExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'This email is already in use.',
                ], 422);
            }

            $data = [
                'name'        => $request->first_name . ' ' . $request->last_name,
                'first_name'  => $request->first_name,
                'last_name'   => $request->last_name,
                'email'       => $request->email,
                'start_date'  => formatToDbDate($request->start_date),
                'finish_date' => formatToDbDate($request->end_date),
            ];

            if ($request->user_id) {
                $user = User::findOrFail($request->user_id);
                $user->update($data);
            } else {
                $user = User::create(array_merge($data, [
                    'password'   => bcrypt(\Str::random(8)),
                    'status'     => 'draft',
                    'created_by' => $authId,
                ]));
            }

            return response()->json([
                'success' => true,
                'user_id' => $user->id,
            ]);
        }

        /* ─────────────────────────────────────────────────────
         | STEP 2 — CERTS ALREADY SAVED VIA MODAL
         ─────────────────────────────────────────────────────*/
        if ((int) $step === 2) {
            return response()->json(['success' => true]);
        }

        /* ─────────────────────────────────────────────────────
         | STEP 3 — CONTRACT
         ─────────────────────────────────────────────────────*/
        if ((int) $step === 3) {

            $request->validate([
                'user_id' => ['required', 'exists:users,id'],
            ]);

            $filePath = $request->contract_file;
            $fileName = $filePath ? basename($filePath) : null;
            $fileExt  = $filePath ? pathinfo($filePath, PATHINFO_EXTENSION) : null;

            UserContract::updateOrCreate(
                ['user_id' => $request->user_id],
                [
                    'employment_name' => $request->employment_type,
                    'salary_rate'     => $request->hourly_rate,
                    'payment_made'    => $request->payment_frequency,
                    'timesheet'       => $request->timesheet_required,
                    'staff'           => is_array($request->staff_type)
                        ? json_encode($request->staff_type)
                        : $request->staff_type,
                    'file_path'       => $filePath,
                    'file_name'       => $fileName,
                    'file_extension'  => $fileExt,
                    'notes'           => is_array($request->notes)
                        ? json_encode($request->notes)
                        : $request->notes,
                ]
            );

            return response()->json(['success' => true]);
        }

        /* ─────────────────────────────────────────────────────
         | STEP 4 — ROLE + ACTIVATE
         ─────────────────────────────────────────────────────*/
        if ((int) $step === 4) {

            $request->validate([
                'user_id' => ['required', 'exists:users,id'],
                'role_id' => ['required'],
            ]);

            $user = User::findOrFail($request->user_id);
            $user->update([
                'role_id' => $request->role_id,
                'status'  => 'completed',
            ]);

            return response()->json([
                'success'  => true,
                'redirect' => route('users.index'),
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid step.'], 422);
    }

    /* ============================================================
       FILE UPLOAD
    ============================================================ */
    public function uploadFile(Request $request)
    {
        $request->validate(['file' => 'required|file|max:10240']);

        $file = $request->file('file');
        $path = $file->store('uploads/users', 'public');

        return response()->json([
            'success' => true,
            'path'    => $path,
            'url'     => Storage::url($path),
            'name'    => $file->getClientOriginalName(),
            'ext'     => $file->getClientOriginalExtension(),
        ]);
    }

    /* ============================================================
       CERTIFICATION CRUD
    ============================================================ */
    public function getCert($certId)
    {
        $cert = UserCertification::with('title')->findOrFail($certId);

        return response()->json([
            'success' => true,
            'cert'    => [
                'id'           => $cert->id,
                'title_id'     => $cert->title_id ?? 'other',
                'custom_title' => $cert->custom_title,
                'expiry_date'  => $cert->expiry_date
                    ? Carbon::parse($cert->expiry_date)->format('d/m/Y')
                    : '',
                'file'         => $cert->file,
                'file_url'     => $cert->file ? Storage::url($cert->file) : null,
            ],
        ]);
    }

    public function updateCert(Request $request, $certId)
    {
        $cert = UserCertification::findOrFail($certId);

        $cert->update([
            'title_id'     => ($request->title_id && $request->title_id !== 'other')
                ? $request->title_id
                : null,
            'custom_title' => $request->custom_title ?: null,
            'expiry_date'  => formatToDbDate($request->expiry_date),
            'file'         => $request->file ?: $cert->file,
        ]);

        $title = CertificationTitle::find($cert->title_id);

        return response()->json([
            'success' => true,
            'cert'    => [
                'id'          => $cert->id,
                'title'       => $cert->custom_title ?: optional($title)->name ?? '—',
                'expiry_date' => $cert->expiry_date
                    ? Carbon::parse($cert->expiry_date)->format('d/m/Y')
                    : '—',
                'file'        => $cert->file,
            ],
        ]);
    }

    public function deleteCert($certId)
    {
        UserCertification::findOrFail($certId)->delete();
        return response()->json(['success' => true]);
    }

    /* ============================================================
       DELETE USER
    ============================================================ */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
