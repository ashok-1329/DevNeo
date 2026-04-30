<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectRegion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /* ============================================================
       INDEX
    ============================================================ */
    public function index()
    {
        return view('admin.projects.index');
    }

    /* ============================================================
       DATATABLE DATA
    ============================================================ */
    public function getData()
    {
        $records = Project::orderByDesc('id')->get()->map(function ($p, $index) {
            return [
                'id'                   => $p->id,
                'sno'                  => $index + 1,
                'project_name'         => $p->project_name,
                'project_number'       => $p->project_number,
                'project_code'         => $p->project_code_id,
                'project_region'       => $p->project_region === 'other'
                    ? $p->project_other_region
                    : $p->project_region,
                'project_client'       => $p->client_representative ?? '-',
                'construction_manager' => $p->construction_manager ?? '-',
                'project_manager'      => $p->project_manager ?? '-',
                'supervisor'           => $p->supervisor ?? '-',
                'project_engineer'     => $p->project_engineer ?? '-',
                'contract_admin'       => $p->contract_admin ?? '-',
                'commencement_date'    => $p->commencement_date
                    ? Carbon::parse($p->commencement_date)->format('d M Y')
                    : '-',
                'status'               => $p->status,
                'step'                 => $p->step ?? 0,
            ];
        });

        return response()->json($records);
    }

    /* ============================================================
       SHOW
    ============================================================ */
    public function show($id)
    {
        $project = Project::findOrFail($id);
        return view('admin.projects.show', compact('project'));
    }

    /* ============================================================
       CREATE  (with draft recovery)
    ============================================================ */
    public function create()
    {
        Project::where('status', 1)
            ->where('updated_at', '<', now()->subHours(24))
            ->delete();

        $draft = Project::where('status', 1)->where('user_id', Auth::id())->where('step', '!=', 13)->latest()->first();

        // echo json_encode($draft);
        // die();
        $generatedCode = $draft?->project_code_id ?? $this->generateProjectCode();

        $existingProject = null;
        if ($draft) {
            $existingProject = [
                'id'                        => $draft->id,
                'project_code_id'           => $draft->project_code_id,
                'project_name'              => $draft->project_name,
                'project_region'            => $draft->project_region,
                'project_other_region'      => $draft->project_other_region,
                'project_number'            => $draft->project_number,
                'project_description'       => $draft->project_description,
                'project_address'           => $draft->project_address,
                'project_notes'             => $draft->project_notes,
                // step 2
                'client_id'                 => $draft->client_id,
                'client_representative'     => $draft->client_representative,
                'client_rep_email'          => $draft->client_rep_email,
                'superintendent'            => $draft->superintendent,
                'superintendent_rep'        => $draft->superintendent_rep,
                'superintendent_rep_email'  => $draft->superintendent_rep_email,
                'client_phone_number'       => $draft->client_phone_number,
                'client_address'            => $draft->client_address,
                'invoices_sent_to'          => $draft->invoices_sent_to,
                // step 3
                'construction_manager'      => $draft->construction_manager,
                'project_manager'           => $draft->project_manager,
                'supervisor'                => $draft->supervisor,
                'project_engineer'          => $draft->project_engineer,
                'contract_admin'            => $draft->contract_admin,
                'commencement_date'         => $draft->commencement_date
                    ? Carbon::parse($draft->commencement_date)->format('d/m/Y')
                    : '',
                'completion_date'           => $draft->completion_date
                    ? Carbon::parse($draft->completion_date)->format('d/m/Y')
                    : '',
                // step 4
                'contract_number'           => $draft->contract_number,
                'contract_type'             => $draft->contract_type,
                'payment_term'              => $draft->payment_term,
                'claims_certification_period' => $draft->claims_certification_period,
                'contract_notes'            => $draft->contract_notes,

                // step 5
                'contract_value'             => $draft->contract_value,
                'contract_value_gst'         => $draft->contract_value_gst,
                'provisional_sum_total'      => $draft->provisional_sum_total,
                'provisional_sum_total_gst'  => $draft->provisional_sum_total_gst,
                'assign_profit_margin'       => $draft->assign_profit_margin,
                'assign_profit_margin_value' => $draft->assign_profit_margin_value,
                'insurance_percentage'       => $draft->insurance_percentage,
                'insurance_percentage_value' => $draft->insurance_percentage_value,
                'profit_value'               => $draft->profit_value,
            ];
        }

        $completedSteps = $this->resolveCompletedSteps($draft);

        [$regions, $clients, $users, $contractTypes, $paymentTerms] = $this->getFormDependencies();

        $projectManagers      = $users->where('role_id', 2);
        $constructionManagers = $users->where('role_id', 8);
        $supervisors          = $users->where('role_id', 6);
        $projectEngineers     = $users->where('role_id', 9);
        $contractAdmins       = $users->where('role_id', 12);

        return view('admin.projects.create', compact(
            'draft',
            'generatedCode',
            'existingProject',
            'completedSteps',
            'regions',
            'clients',
            'users',
            'projectManagers',
            'constructionManagers',
            'supervisors',
            'projectEngineers',
            'contractAdmins',
            'contractTypes',
            'paymentTerms',
        ));
    }

    /* ============================================================
       EDIT
    ============================================================ */
    public function edit($id)
    {
        $project = Project::findOrFail($id);

        $existingProject = [
            'id'                        => $project->id,
            'project_code_id'           => $project->project_code_id,
            'project_name'              => $project->project_name,
            'project_region'            => $project->project_region,
            'project_other_region'      => $project->project_other_region,
            'project_number'            => $project->project_number,
            'project_description'       => $project->project_description,
            'project_address'           => $project->project_address,
            'project_notes'             => $project->project_notes,
            'client_id'                 => $project->client_id,
            'client_representative'     => $project->client_representative,
            'client_rep_email'          => $project->client_rep_email,
            'superintendent'            => $project->superintendent,
            'superintendent_rep'        => $project->superintendent_rep,
            'superintendent_rep_email'  => $project->superintendent_rep_email,
            'client_phone_number'       => $project->client_phone_number,
            'client_address'            => $project->client_address,
            'invoices_sent_to'          => $project->invoices_sent_to,
            'construction_manager'      => $project->construction_manager,
            'project_manager'           => $project->project_manager,
            'supervisor'                => $project->supervisor,
            'project_engineer'          => $project->project_engineer,
            'contract_admin'            => $project->contract_admin,
            'commencement_date'         => $project->commencement_date
                ? Carbon::parse($project->commencement_date)->format('d/m/Y')
                : '',
            'completion_date'           => $project->completion_date
                ? Carbon::parse($project->completion_date)->format('d/m/Y')
                : '',
            'contract_number'           => $project->contract_number,
            'contract_type'             => $project->contract_type,
            'payment_term'              => $project->payment_term,
            'claims_certification_period' => $project->claims_certification_period,
            'contract_notes'            => $project->contract_notes,
            'step'                      => $project->step ?? 4,
        ];

        $completedSteps = $this->resolveCompletedSteps($project);

        [$regions, $clients, $users, $contractTypes] = $this->getFormDependencies();

        return view('admin.projects.edit', compact(
            'project',
            'existingProject',
            'completedSteps',
            'regions',
            'clients',
            'users',
            'contractTypes',
        ));
    }

    /* ============================================================
       STEP HANDLER  (create & edit share this endpoint)
    ============================================================ */
    public function handleStep(Request $request)
    {
        $step   = (int) $request->step;
        $authId = Auth::id();

        if ($step === 1) {
            $request->validate([
                'project_name'   => ['required', 'string', 'max:255'],
                'project_region' => ['required'],
                'project_number' => ['required', 'string', 'max:100'],
            ]);

            $data = [
                'project_code_id'      => $request->project_code_id,
                'project_name'         => $request->project_name,
                'project_region'       => $request->project_region,
                'project_other_region' => $request->project_region === 'other'
                    ? $request->project_other_region
                    : null,
                'project_number'       => $request->project_number,
                'project_description'  => $request->project_description,
                'project_address'      => $request->project_address,
                'project_notes'        => $request->project_notes,
                'step'                 => max(1, (int) ($request->current_step ?? 0)),
            ];

            if ($request->project_id) {
                $project = Project::findOrFail($request->project_id);
                $project->update($data);
            } else {
                $project = Project::create(array_merge($data, [
                    'status'     => 1,
                    'user_id' => $authId,
                ]));
            }

            return response()->json([
                'success'    => true,
                'project_id' => $project->id,
            ]);
        }

        if ($step === 2) {

            $request->validate([
                'project_id'            => ['required', 'exists:projects,id'],
                'client_id'             => ['nullable', 'exists:clients,id'],

                'client_representative' => ['required', 'string', 'max:255'],
                'client_rep_email'      => ['required', 'email', 'max:255'],

                'superintendent'        => ['required', 'string', 'max:255'],
                'superintendent_rep'    => ['nullable', 'string', 'max:255'],
                'superintendent_rep_email' => ['nullable', 'email', 'max:255'],

                // ✅ NEW FIELDS (ALL REQUIRED)
                'client_phone_number'   => ['required', 'string', 'max:20'],
                'client_address'        => ['required', 'string', 'max:500'],
                'invoices_sent_to'      => ['required', 'email', 'max:255'],
            ]);

            $project = Project::findOrFail($request->project_id);

            $project->update([
                'client_id'                => $request->client_id ?: null,
                'client_representative'    => $request->client_representative,
                'client_rep_email'         => $request->client_rep_email,

                'superintendent'           => $request->superintendent,
                'superintendent_rep'       => $request->superintendent_rep,
                'superintendent_rep_email' => $request->superintendent_rep_email,

                // ✅ NEW FIELDS
                'client_phone_number'      => $request->client_phone_number,
                'client_address'           => $request->client_address,
                'invoices_sent_to'         => $request->invoices_sent_to,

                'step'                     => max(2, $project->step ?? 0),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Step 2 saved successfully'
            ]);
        }

        if ($step === 3) {
            $request->validate([
                'construction_manager' => ['required', 'exists:users,id'],
                'project_manager'      => ['required', 'exists:users,id'],
                'supervisor'           => ['required', 'exists:users,id'],
                'project_engineer'     => ['required', 'exists:users,id'],
                'contract_admin'       => ['required', 'exists:users,id'],
                'project_id'           => ['required', 'exists:projects,id'],

            ]);

            $project = Project::findOrFail($request->project_id);
            $project->update([
                'construction_manager' => $request->construction_manager,
                'project_manager'      => $request->project_manager,
                'supervisor'           => $request->supervisor,
                'project_engineer'     => $request->project_engineer,
                'contract_admin'       => (int) $request->contract_admin,
                'step'                 => max(3, $project->step ?? 0),
            ]);

            return response()->json(['success' => true]);
        }

        if ($step === 4) {
            $request->validate([
                'project_id'     => ['required', 'exists:projects,id'],
                'contract_number' => ['required', 'string', 'max:255'],
                'commencement_date'    => ['required'],
                'completion_date'      => ['required'],
            ]);

            $project = Project::findOrFail($request->project_id);
            $project->update([
                'contract_number'              => $request->contract_number,
                'contract_type'                => $request->contract_type,
                'payment_term'                 => $request->payment_term,
                'claims_certification_period'  => $request->claims_certification_period,
                'contract_notes'               => $request->contract_notes,
                'commencement_date'            => $this->parseDate($request->commencement_date),
                'completion_date'              => $this->parseDate($request->completion_date),
                'step'                         => 4,
            ]);

            return response()->json(['success' => true]);
        }

        if ($step === 5) {

            $request->validate([
                'project_id'                => ['required', 'exists:projects,id'],

                'contract_value'            => ['required', 'numeric'],
                'contract_value_gst'        => ['required', 'numeric'],
                'provisional_sum_total'     => ['required', 'numeric'],
                'provisional_sum_total_gst' => ['required', 'numeric'],
                'assign_profit_margin'      => ['required', 'numeric'],
                'assign_profit_margin_value' => ['required', 'numeric'],
                'insurance_percentage'      => ['required', 'numeric'],
                'insurance_percentage_value' => ['required', 'numeric'],
                'lump_sum'                  => ['nullable', 'boolean'],
                'schedule_of_rate'          => ['nullable', 'boolean'],
                'profit_value'              => ['nullable', 'numeric'],
            ]);

            $project = Project::findOrFail($request->project_id);

            $project->update([
                'lump_sum'                  => $request->lump_sum ?? 0,
                'schedule_of_rate'          => $request->schedule_of_rate ?? 0,
                'contract_value'            => $request->contract_value,
                'contract_value_gst'        => $request->contract_value_gst,
                'provisional_sum_total'     => $request->provisional_sum_total,
                'provisional_sum_total_gst' => $request->provisional_sum_total_gst,
                'assign_profit_margin'      => $request->assign_profit_margin,
                'assign_profit_margin_value' => $request->assign_profit_margin_value,
                'insurance_percentage'      => $request->insurance_percentage,
                'insurance_percentage_value' => $request->insurance_percentage_value,
                'profit_value'              => $request->profit_value,
                'step'                      => 5,
            ]);

            return response()->json(['success' => true]);
        }

        if ($step === 6) {

            return response()->json(['success' => true]);
        }
        if ($step === 7) {

            return response()->json(['success' => true]);
        }
        if ($step === 8) {

            return response()->json(['success' => true]);
        }
        if ($step === 9) {

            return response()->json(['success' => true]);
        }
        if ($step === 10) {

            return response()->json(['success' => true]);
        }
        if ($step === 11) {

            return response()->json(['success' => true]);
        }
        if ($step === 12) {

            return response()->json(['success' => true]);
        }
        if ($step === 13) {

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid step.'], 422);
    }

    /* ============================================================
       UPDATE STATUS
    ============================================================ */
    public function updateStatus(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $project->update(['status' => (int) $request->status]);

        return response()->json([
            'status'  => $request->status,
            'message' => 'Status updated successfully.',
        ]);
    }

    /* ============================================================
       DELETE
    ============================================================ */
    public function destroy($id)
    {
        Project::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    /* ============================================================
       PRIVATE HELPERS
    ============================================================ */

    /** Generate next available project code e.g. NEOP001 */
    private function generateProjectCode(): string
    {
        $last = Project::orderByDesc('id')->value('project_code_id');
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            return 'NEOP' . str_pad((int) $m[1] + 1, 3, '0', STR_PAD_LEFT);
        }
        return 'NEOP001';
    }

    /** Parse dd/mm/yyyy → Y-m-d for DB storage */
    private function parseDate(?string $date): ?string
    {
        if (!$date) return null;
        try {
            return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /** Determine which steps are completed for the given project/draft */
    private function resolveCompletedSteps(?Project $project): array
    {
        if (!$project) return [];

        $steps = [];
        // Step 1 complete if core fields filled
        if ($project->project_name && $project->project_number) $steps[] = 1;
        // Step 2 complete
        if ($project->client_representative && $project->superintendent) $steps[] = 2;
        // Step 3 complete
        if ($project->project_manager && $project->project_engineer && $project->contract_admin && $project->construction_manager && $project->supervisor) $steps[] = 3;
        // Step 4 complete
        if ($project->contract_number) $steps[] = 4;
        // Step 5 complete
        if ($project->contract_value && $project->contract_value_gst && $project->provisional_sum_total) $steps[] = 5;
        if ($project->contract_value && $project->contract_value_gst && $project->provisional_sum_total) $steps[] = 6;
        if ($project->contract_value && $project->contract_value_gst && $project->provisional_sum_total) $steps[] = 7;
        if ($project->contract_value && $project->contract_value_gst && $project->provisional_sum_total) $steps[] = 8;
        if ($project->contract_value && $project->contract_value_gst && $project->provisional_sum_total) $steps[] = 9;
        if ($project->contract_value && $project->contract_value_gst && $project->provisional_sum_total) $steps[] = 10;
        if ($project->contract_value && $project->contract_value_gst && $project->provisional_sum_total) $steps[] = 11;
        if ($project->contract_value && $project->contract_value_gst && $project->provisional_sum_total) $steps[] = 12;
        if ($project->contract_value && $project->contract_value_gst && $project->provisional_sum_total) $steps[] = 13;

        return $steps;
    }

    /** Shared form dependencies */
    private function getFormDependencies(): array
    {
        $regions = \DB::table('project_regions')->get();
        $contractTypes = \DB::table('settings')->get();
        $clients = \DB::table('clients')->get();
        $paymentTerms = \DB::table('payment_terms')->get();
        $users   = \App\Models\User::orderBy('first_name')->get();

        return [$regions, $clients, $users, $contractTypes, $paymentTerms];
    }

    public function generateProjectNumber(Request $request)
    {
        if (empty($request->region)) {
            return ['success' => false, 'value' => ''];
        }

        $region = ProjectRegion::find($request->region);
        if (!$region) {
            return ['success' => false, 'value' => ''];
        }

        $prefix = strtoupper(Str::substr($region->name, 0, 1));

        if (!empty($request->project_id)) {
            $project = Project::find($request->project_id);

            if ($project && $project->project_region == $request->region) {
                return ['success' => true, 'value' => $project->project_number];
            }
        }

        $lastProject = Project::where('project_number', 'LIKE', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $lastNumber = 0;
        if ($lastProject && preg_match('/\d+$/', $lastProject->project_number, $matches)) {
            $lastNumber = (int) $matches[0];
        }

        $newNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);

        return [
            'success' => true,
            'value'   => $prefix . $newNumber
        ];
    }
}
