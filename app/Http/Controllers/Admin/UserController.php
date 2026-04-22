<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Models\Role;
use App\Models\User;
use App\Models\CertificationTitle;
use Illuminate\Http\Request;
use Pest\TestCaseMethodFilters\PrTestCaseFilter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedMail;
use Illuminate\Support\Facades\Password;



class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // public function index()
    // {
    //     $users = $this->userService->getAllUsers();
    //     return view('admin.users.index', compact('users'));
    // }

    public function index()
    {
        $users = \App\Models\User::with('role')->latest()->get();

        return view('admin.users.index', compact('users'));
    }

    public function getUsers()
    {

        $users = User::with('role')->get();

        return response()->json($users);
    }

    public function handleStep(Request $request)
{
    $step = $request->step;

    // STEP 1 → CREATE USER
    if ($step == 1) {

        // =========================
        // VALIDATION
        // =========================
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
        ];

        $request->validate($rules);

        // =========================
        // COMMON EMAIL CHECK (SMART)
        // =========================
        $emailExists = User::where('email', $request->email)
            ->when($request->user_id, function ($query) use ($request) {
                $query->where('id', '!=', $request->user_id);
            })
            ->exists();

        if ($emailExists) {
            return response()->json([
                'success' => false,
                'message' => 'Email already exists'
            ]);
        }

        // =========================
        // UPDATE EXISTING USER
        // =========================
        if ($request->user_id) {

            $user = User::find($request->user_id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }

            $user->update([
                'name'       => $request->first_name . ' ' . $request->last_name,
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'start_date' => formatToDbDate($request->start_date),
                'expiry_date' => formatToDbDate($request->expiry_date)

            ]);

            return response()->json([
                'success' => true,
                'user_id' => $user->id
            ]);
        }

        // =========================
        // CREATE NEW USER
        // =========================

        $password = Str::random(8);

        $user = User::create([
            'name'       => $request->first_name . ' ' . $request->last_name,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'start_date' => formatToDbDate($request->start_date),
            'expiry_date' => formatToDbDate($request->expiry_date),
            'password'   => bcrypt($password),
            'status'     => 'draft',
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'user_id' => $user->id
        ]);
    }

    // STEP 2 → CERTIFICATIONS
    if ($step == 2) {

        $certifications = json_decode($request->certifications, true);
        foreach ($certifications as $cert) {
            UserCertification::create([
                'user_id' => $request->user_id,
                'title' => $cert['title'],
                'expiry_date' => formatToDbDate($cert['expiry_date']),
                'file' => $cert['file']
            ]);
        }

        return response()->json(['success' => true]);
    }

    // STEP 3 → CONTRACT
    if ($step == 3) {

        User::where('id', $request->user_id)->update([
            'contract_file' => $request->contract_file
        ]);

        return response()->json(['success' => true]);
    }

    // STEP 4 → COMPLETE USER
    if ($step == 4) {

        $user = User::findOrFail($request->user_id);
        $user->update([
            'role_id' => $request->role_id,
            'status'  => 'completed'
        ]);

         // SEND RESET PASSWORD LINK
        Password::sendResetLink([
            'email' => $user->email
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('users.index')
        ]);
    }
}

    public function create()
    {
        // $roles = Role::all();
        // return view('admin.users.create', compact('roles'));

          $draft = User::where('created_by', auth()->id())
        ->where('status', 'draft')
        ->latest()
        ->first();

        $roles = Role::all();

        $titles = CertificationTitle::all();

        return view('admin.users.create', compact('draft','roles','titles'));
    }

    public function store(Request $request)
    {
        $this->userService->createUser($request->all());
        return redirect()->route('users.index');
    }

    public function edit($id)
    {
        $user = $this->userService->getUser($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user','roles'));
    }

    public function update(Request $request, $id)
    {
        $this->userService->updateUser($id, $request->all());
        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        $this->userService->deleteUser($id);
        return back();
    }
}
