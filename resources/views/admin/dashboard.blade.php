@extends('layouts.admin')

@section('content')

<div class="card-container">
    <div class="row g-4">

        <div class="col-md-3">
            <a href="{{ route('admin.dashboard') }}">
                <div class="card-box">
                    <i class="fa fa-chart-line fa-2x mb-2"></i>
                    <div>DASHBOARD</div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('users.index') }}">
                <div class="card-box">
                    <i class="fa fa-users-gear fa-2x mb-2"></i>
                    <div>ADMIN</div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('projects.index') }}">
                <div class="card-box">
                    <i class="fa fa-diagram-project fa-2x mb-2"></i>
                    <div>MANAGE PROJECTS</div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('cashflow.index') }}">
                <div class="card-box">
                    <i class="fa fa-cash-register fa-2x mb-2"></i>
                    <div>CASHFLOW</div>
                </div>
            </a>
        </div>

    </div>
</div>

<div class="card-container mt-4">
    <div class="row g-4">

        <div class="col-md-3">
            <a href="{{ route('users.index') }}">
                <div class="card-box">
                    <i class="fa fa-users fa-2x mb-2"></i>
                    <div>MANAGE USER LIST</div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('reports.index') }}">
                <div class="card-box">
                    <i class="fa fa-file-lines fa-2x mb-2"></i>
                    <div>REPORTS</div>
                </div>
            </a>
        </div>

    </div>
</div>

@endsection
