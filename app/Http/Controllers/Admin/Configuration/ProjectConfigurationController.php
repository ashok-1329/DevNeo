<?php

namespace App\Http\Controllers\Admin\Configuration;

use App\Http\Controllers\Controller;
use App\Models\PlantType;

class ProjectConfigurationController extends Controller
{
    public function index()
    {
        $plantTypes = PlantType::where('status', 1)->orderBy('name')->get();

        return view('admin.configuration.index', compact('plantTypes'));
    }
}
