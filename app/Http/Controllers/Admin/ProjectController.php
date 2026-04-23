<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * List all active projects — used by DataTable or index views.
     */
    public function index()
    {
        return view('admin.projects.index');
    }

    /**
     * JSON endpoint: returns id, project_number, project_code_id for dropdown use.
     * Used by payment-rule-form.js to auto-fill project code.
     */
    public function getData()
    {
        $projects = Project::active()
            ->select('id', 'project_number', 'project_code_id', 'project_name')
            ->orderBy('project_number')
            ->get();

        return response()->json($projects);
    }

    /**
     * Show one project's details (used by other modules).
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);
        return response()->json($project);
    }
}
