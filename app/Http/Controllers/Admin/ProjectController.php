<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        // Purge drafts older than 24 hours
        Project::where('created_by', auth()->id())
            ->where('status', 'draft')
            ->where('updated_at', '<', now()->subHours(24))
            ->delete();

        $draft = Project::where('created_by', auth()->id())
            ->where('status', 'draft')
            ->latest()
            ->first();

        // Generate a new project code if no draft exists
        $generatedCode = $draft?->project_code_id ?? $this->generateProjectCode();

        // Data to pass to JS for pre-fill
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
                'step'                      => $draft->step ?? 0,
            ];
        }

        $completedSteps = $this->resolveCompletedSteps($draft);

        [$regions, $clients, $users] = $this->getFormDependencies();

        return view('admin.projects.create', compact(
            'draft',
            'generatedCode',
            'existingProject',
            'completedSteps',
            'regions',
            'clients',
            'users'
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

        [$regions, $clients, $users] = $this->getFormDependencies();

        return view('admin.projects.edit', compact(
            'project',
            'existingProject',
            'completedSteps',
            'regions',
            'clients',
            'users'
        ));
    }

    /* ============================================================
       STEP HANDLER  (create & edit share this endpoint)
    ============================================================ */
    public function handleStep(Request $request)
    {
        $step   = (int) $request->step;
        $authId = auth()->id();

        /* ─────────────────────────────────────────────────────
         | STEP 1 — PROJECT DETAILS
         ─────────────────────────────────────────────────────*/
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
                    'status'     => 'draft',
                    'created_by' => $authId,
                ]));
            }

            return response()->json([
                'success'    => true,
                'project_id' => $project->id,
            ]);
        }

        /* ─────────────────────────────────────────────────────
         | STEP 2 — CLIENT DETAILS
         ─────────────────────────────────────────────────────*/
        if ($step === 2) {
            $request->validate([
                'project_id'            => ['required', 'exists:projects,id'],
                'client_representative' => ['required', 'string', 'max:255'],
                'client_rep_email'      => ['required', 'email', 'max:255'],
                'superintendent'        => ['required', 'string', 'max:255'],
            ]);

            $project = Project::findOrFail($request->project_id);
            $project->update([
                'client_id'                => $request->client_id ?: null,
                'client_representative'    => $request->client_representative,
                'client_rep_email'         => $request->client_rep_email,
                'superintendent'           => $request->superintendent,
                'superintendent_rep'       => $request->superintendent_rep,
                'superintendent_rep_email' => $request->superintendent_rep_email,
                'step'                     => max(2, $project->step ?? 0),
            ]);

            return response()->json(['success' => true]);
        }

        /* ─────────────────────────────────────────────────────
         | STEP 3 — TEAM & DATES
         ─────────────────────────────────────────────────────*/
        if ($step === 3) {
            $request->validate([
                'project_id'       => ['required', 'exists:projects,id'],
                'commencement_date' => ['required'],
                'completion_date'   => ['required'],
            ]);

            $project = Project::findOrFail($request->project_id);
            $project->update([
                'construction_manager' => $request->construction_manager,
                'project_manager'      => $request->project_manager,
                'supervisor'           => $request->supervisor,
                'project_engineer'     => $request->project_engineer,
                'contract_admin'       => $request->contract_admin,
                'commencement_date'    => $this->parseDate($request->commencement_date),
                'completion_date'      => $this->parseDate($request->completion_date),
                'step'                 => max(3, $project->step ?? 0),
            ]);

            return response()->json(['success' => true]);
        }

        /* ─────────────────────────────────────────────────────
         | STEP 4 — CONTRACT & ACTIVATE
         ─────────────────────────────────────────────────────*/
        if ($step === 4) {
            $request->validate([
                'project_id'     => ['required', 'exists:projects,id'],
                'contract_number' => ['required', 'string', 'max:255'],
            ]);

            $project = Project::findOrFail($request->project_id);
            $project->update([
                'contract_number'              => $request->contract_number,
                'contract_type'                => $request->contract_type,
                'payment_term'                 => $request->payment_term,
                'claims_certification_period'  => $request->claims_certification_period,
                'contract_notes'               => $request->contract_notes,
                'step'                         => 4,
                'status'                       => 'active',
            ]);

            return response()->json([
                'success'  => true,
                'redirect' => route('projects.index'),
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid step.'], 422);
    }

    /* ============================================================
       UPDATE STATUS
    ============================================================ */
    public function updateStatus(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'status' => ['required', 'in:active,draft,completed,inactive'],
        ]);

        $project->update(['status' => $request->status]);

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
        if ($project->commencement_date && $project->completion_date) $steps[] = 3;
        // Step 4 complete
        if ($project->contract_number) $steps[] = 4;

        return $steps;
    }

    /** Shared form dependencies */
    private function getFormDependencies(): array
    {
        // Adjust model names to match your actual namespace
        $regions = \DB::table('project_regions')->get();       // or ProjectRegion::all()
        $clients = \DB::table('clients')->get();               // or Client::all()
        $users   = \App\Models\User::orderBy('first_name')->get();

        return [$regions, $clients, $users];
    }
}
