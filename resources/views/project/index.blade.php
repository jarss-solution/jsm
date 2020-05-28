@extends('layouts.app')
@section('content')
<div class="page-content-wrapper ">
    <div class="content sm-gutter">
        <div class="container-fluid padding-25 sm-padding-10" style="padding-top: 3px !important;">
            <h5 style="color: #85858e;">View current, archived and project leads. The projects are color coded to differentiate their category for easy identification.</h5>
            <div class="card card-borderless task-card">
                <ul class="nav nav-tabs nav-tabs-simple d-none d-md-flex d-lg-flex d-xl-flex" role="tablist" data-init-reponsive-tabs="dropdownfx">
                    <li class="nav-item">
                        <a class="active show" data-toggle="tab" role="tab" data-target="#tab_current" href="#" aria-selected="true">Current Projects</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" data-toggle="tab" role="tab" data-target="#tab_archived" class="" aria-selected="false">Archived Projects</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" data-toggle="tab" role="tab" data-target="#tab_lead" class="" aria-selected="false">Project Leads</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" data-toggle="tab" role="tab" data-target="#tab_amc" class="" aria-selected="false">AMC</a>
                    </li>
                </ul>
                <div class="nav-tab-dropdown cs-wrapper full-width d-lg-none d-xl-none d-md-none">
                    <div class="cs-select cs-skin-slide full-width" tabindex="0"><span class="cs-placeholder">Current Projects</span>
                        <div class="cs-options">
                            <ul>
                                <li data-option="" data-value="#tab_current"><span>Current Projects</span></li>
                                <li data-option="" data-value="#tab_archived"><span>Archived Projects</span></li>
                                <li data-option="" data-value="#tab_lead"><span>Project Leads</span></li>
                                <li data-option="" data-value="#tab_amc"><span>AMC</span></li>
                            </ul>
                        </div><select class="cs-select cs-skin-slide full-width" data-init-plugin="cs-select">
                            <option value="#tab_current" selected="">Current Projects</option>
                            <option value="#tab_archived">Archived Projects</option>
                            <option value="#tab_lead">Project Leads</option>
                            <option value="#tab_amc">AMC</option>
                        </select>
                        <div class="cs-backdrop"></div>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active show" id="tab_current">
                        <div class="row column-seperation">
                            <div class="col-lg-12">
                                <div class="padding-10">
                                    <h6 style="margin-top: 0;">Filter</h6>
                                    <div class="row">
                                        <div class="col-md-8">
                                            {{ html()->form('GET', route('project.search'))->open() }}
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        {{ html()->text('search', request()->get('search'))->class('form-control')->placeholder('Search projects') }}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        {{ html()->select('assigned_user', $users, request()->get('assigned_user'))->class('form-control')->placeholder('Select employee') }}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        {{ html()->select('type', [
                                                        'design' => 'Design',
                                                        'tech' => 'Tech',
                                                        'product' => 'Product',
                                                        'potentian_lead' => 'Potential lead',
                                                        'web_hosting' => 'Web hosting',
                                                        'amc' => 'AMC',
                                                        ], request()->get('type'))->class('form-control')->placeholder('Select project type') }}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <button class="btn btn-success btn-black" type="submit"><i class="fa fa-search"></i> Search</button>
                                                </div>
                                            </div>
                                            {{ html()->form()->close() }}
                                        </div>
                                        <div class="col-md-4">
                                            @if(Auth::user()->role == 1)
                                            <button type="button" class="btn btn-black btn-primary add-project float-right">
                                                <i class="fa fa-plus"></i> Add New Project
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-hover table-projects table-datatable">
                                        <thead>
                                            <tr>
                                                <td width="3%" class="text-center"><i class="fa fa-star-o"></i></td>
                                                <td width="5%">SN</td>
                                                <td width="20%">Project Name</td>
                                                <td width="10%">Client</td>
                                                <td width="15%">Assigned Users</td>
                                                <td width="15%">Progressbar</td>
                                                <td width="10%">Category</td>
                                                <td width="10%">Est hrs - Spent hrs</td>
                                                <td width="180px">Edit</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($projects as $key => $project)
                                            <tr data-href="{{ route('project.show', $project->slug) }}" class="project-type-{{ $project->type }}">
                                                <td class="text-center">
                                                    <i class="fa project-star {{ $project->star->count() ? 'fa-star' : 'fa-star-o' }}" data-project-id="{{ $project->id }}"></i>
                                                </td>
                                                <td class='clickable-row'>{{ ++$key + (((request()->projects ?: 1) * 11) - 11) }}</td>
                                                <td class='clickable-row'>{{ $project->title }}</td>
                                                <td class='clickable-row'>{{ $project->client_name }}</td>
                                                <td class='clickable-row'>
                                                    @foreach($project->assignedUsers as $assignedUser)
                                                    <span class="thumbnail-wrapper circular inline m-r-5" style="width: 25px; height: 25px;">
                                                        <img src="{{ $assignedUser->profilePicture() }}" alt="{{ $assignedUser->name }}" title="{{ $assignedUser->name }}">
                                                    </span>
                                                    @endforeach
                                                </td>
                                                <td class='clickable-row'>
                                                    @php
                                                    $project_process = unserialize($project->process) ?: [];
                                                    $last_process = end($project_process) ?: 'New';
                                                    @endphp
                                                    {{ ucfirst(str_replace('_', ' ', $last_process)) }}
                                                </td>
                                                <td class='clickable-row'>{{ ucfirst($project->type) }}</td>
                                                <td class='clickable-row'>
                                                    {{ $project->estimated_hours ?: 0 }}hrs -
                                                    {{ round($project->timelogs->sum('time_log')) }}hrs
                                                </td>
                                                <td>
                                                    <a href="{{ route('project.edit', $project->slug) }}" class="btn btn-xs btn-black text-white float-left m-r-10">Edit</a>
                                                    {{ html()->form('DELETE', route('project.destroy', $project->slug))->class('float-left')->open() }}
                                                    <button type="submit" class="btn btn-xs btn-black text-white" onclick="return confirm('Are you sure? This will delete all the project history, including worked hours.')">Delete</button>
                                                    {{ html()->form()->close() }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{-- <div class="pull-right p-t-20 kms-navigation">
                                        {!! $projects->appends(['projects' => $projects->currentPage()])->links() !!}
                                    </div> --}}
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_archived">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="padding-10">
                                    @if(Auth::user()->role == 1)
                                    <button type="button" class="btn btn-black btn-primary add-project float-right m-b-10">
                                        <i class="fa fa-plus"></i> Add New Project
                                    </button>
                                    @endif
                                    <table class="table table-bordered table-hover table-projects table-datatable">
                                        <thead>
                                            <tr>
                                                <td width="3%" class="text-center"><i class="fa fa-star-o"></i></td>
                                                <td width="5%">SN</td>
                                                <td width="20%">Project Name</td>
                                                <td width="10%">Client</td>
                                                <td width="15%">Assigned Users</td>
                                                <td width="15%">Progressbar</td>
                                                <td width="10%">Category</td>
                                                <td width="10%">Est hrs - Spent hrs</td>
                                                <td width="180px">Edit</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($archived_projects as $key => $archived_project)
                                            <tr data-href="{{ route('project.show', $archived_project->slug) }}" class="project-type-{{ $archived_project->type }}">
                                                <td class="text-center">
                                                    <i class="fa project-star {{ $archived_project->star->count() ? 'fa-star' : 'fa-star-o' }}" data-project-id="{{ $archived_project->id }}"></i>
                                                </td>
                                                <td class='clickable-row'>{{ ++$key + (((request()->archived ?: 1) * 11) - 11) }}</td>
                                                <td class='clickable-row'>{{ $archived_project->title }}</td>
                                                <td class='clickable-row'>{{ $archived_project->client_name }}</td>
                                                <td class='clickable-row'>
                                                    @foreach($archived_project->assignedUsers as $assignedUser)
                                                    <span class="thumbnail-wrapper circular inline m-r-5" style="width: 25px; height: 25px;">
                                                        <img src="{{ $assignedUser->profilePicture() }}" alt="{{ $assignedUser->name }}" title="{{ $assignedUser->name }}">
                                                    </span>
                                                    @endforeach
                                                </td>
                                                <td class='clickable-row'>
                                                    @php
                                                    $project_process = unserialize($archived_project->process) ?: [];
                                                    $last_process = end($project_process) ?: 'New';
                                                    @endphp
                                                    {{ ucfirst(str_replace('_', ' ', $last_process)) }}
                                                </td>
                                                <td class='clickable-row'>{{ ucfirst($archived_project->type) }}</td>
                                                <td class='clickable-row'>
                                                    {{ $archived_project->estimated_hours ?: 0 }}hrs -
                                                    {{ round($archived_project->timelogs->sum('time_log')) }}hrs
                                                </td>
                                                <td>
                                                    <a href="{{ route('project.edit', $archived_project->slug) }}" class="btn btn-xs btn-black text-white float-left m-r-10">Edit</a>
                                                    {{ html()->form('DELETE', route('project.destroy', $archived_project->slug))->class('float-left')->open() }}
                                                    <button type="submit" class="btn btn-xs btn-black text-white" onclick="return confirm('Are you sure? This will delete all the project history, including worked hours.')">Delete</button>
                                                    {{ html()->form()->close() }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_lead">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="padding-10">
                                    @if(Auth::user()->role == 1)
                                    <button type="button" class="btn btn-black btn-primary add-project float-right m-b-10">
                                        <i class="fa fa-plus"></i> Add New Project
                                    </button>
                                    @endif
                                <table class="table table-bordered table-hover table-projects table-datatable">
                                    <thead>
                                        <tr>
                                            <td width="3%" class="text-center"><i class="fa fa-star-o"></i></td>
                                            <td width="5%">SN</td>
                                            <td width="20%">Project Name</td>
                                            <td width="10%">Client</td>
                                            <td width="15%">Assigned Users</td>
                                            <td width="15%">Progressbar</td>
                                            <td width="10%">Category</td>
                                            <td width="10%">Est hrs - Spent hrs</td>
                                            <td width="180px">Edit</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($project_leads as $key => $project_lead)
                                        <tr data-href="{{ route('project.show', $project_lead->slug) }}" class="project-type-{{ $project_lead->type }}">
                                            <td class="text-center">
                                                <i class="fa project-star {{ $project_lead->star->count() ? 'fa-star' : 'fa-star-o' }}" data-project-id="{{ $project_lead->id }}"></i>
                                            </td>
                                            <td class='clickable-row'>{{ ++$key }}</td>
                                            <td class='clickable-row'>{{ $project_lead->title }}</td>
                                            <td class='clickable-row'>{{ $project_lead->client_name }}</td>
                                            <td class='clickable-row'>
                                                @foreach($project_lead->assignedUsers as $assignedUser)
                                                <span class="thumbnail-wrapper circular inline m-r-5" style="width: 25px; height: 25px;">
                                                    <img src="{{ $assignedUser->profilePicture() }}" alt="{{ $assignedUser->name }}" title="{{ $assignedUser->name }}">
                                                </span>
                                                @endforeach
                                            </td>
                                            <td class='clickable-row'>
                                                @php
                                                $project_process = unserialize($project_lead->process) ?: [];
                                                $last_process = end($project_process) ?: 'New';
                                                @endphp
                                                {{ ucfirst(str_replace('_', ' ', $last_process)) }}
                                            </td>
                                            <td class='clickable-row'>{{ ucfirst($project_lead->type) }}</td>
                                            <td class='clickable-row'>
                                                {{ $project_lead->estimated_hours ?: 0 }}hrs -
                                                {{ round($project_lead->timelogs->sum('time_log')) }}hrs
                                            </td>
                                            <td>
                                                <a href="{{ route('project.edit', $project_lead->slug) }}" class="btn btn-xs btn-black text-white float-left m-r-10">Edit</a>
                                                {{ html()->form('DELETE', route('project.destroy', $project_lead->slug))->class('float-left')->open() }}
                                                <button type="submit" class="btn btn-xs btn-black text-white" onclick="return confirm('Are you sure? This will delete all the project history, including worked hours.')">Delete</button>
                                                {{ html()->form()->close() }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_amc">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="padding-10">
                                    @if(Auth::user()->role == 1)
                                    <button type="button" class="btn btn-black btn-primary add-project float-right m-b-10">
                                        <i class="fa fa-plus"></i> Add New Project
                                    </button>
                                    @endif
                                <table class="table table-bordered table-hover table-projects table-datatable">
                                    <thead>
                                        <tr>
                                            <td width="3%" class="text-center"><i class="fa fa-star-o"></i></td>
                                            <td width="5%">SN</td>
                                            <td width="20%">Project Name</td>
                                            <td width="10%">Client</td>
                                            <td width="15%">Assigned Users</td>
                                            <td width="15%">Progressbar</td>
                                            <td width="10%">Category</td>
                                            <td width="10%">Est hrs - Spent hrs</td>
                                            <td width="180px">Edit</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($project_amc as $key => $projectamc)
                                        <tr data-href="{{ route('project.show', $projectamc->slug) }}" class="project-type-{{ $projectamc->type }}">
                                            <td class="text-center">
                                                <i class="fa project-star {{ $projectamc->star->count() ? 'fa-star' : 'fa-star-o' }}" data-project-id="{{ $projectamc->id }}"></i>
                                            </td>
                                            <td class='clickable-row'>{{ ++$key }}</td>
                                            <td class='clickable-row'>{{ $projectamc->title }}</td>
                                            <td class='clickable-row'>{{ $projectamc->client_name }}</td>
                                            <td class='clickable-row'>
                                                @foreach($projectamc->assignedUsers as $assignedUser)
                                                <span class="thumbnail-wrapper circular inline m-r-5" style="width: 25px; height: 25px;">
                                                    <img src="{{ $assignedUser->profilePicture() }}" alt="{{ $assignedUser->name }}" title="{{ $assignedUser->name }}">
                                                </span>
                                                @endforeach
                                            </td>
                                            <td class='clickable-row'>
                                                @php
                                                $project_process = unserialize($projectamc->process) ?: [];
                                                $last_process = end($project_process) ?: 'New';
                                                @endphp
                                                {{ ucfirst(str_replace('_', ' ', $last_process)) }}
                                            </td>
                                            <td class='clickable-row'>{{ ucfirst($projectamc->type) }}</td>
                                            <td class='clickable-row'>
                                                {{ $projectamc->estimated_hours ?: 0 }}hrs -
                                                {{ round($projectamc->timelogs->sum('time_log')) }}hrs
                                            </td>
                                            <td>
                                                <a href="{{ route('project.edit', $projectamc->slug) }}" class="btn btn-xs btn-black text-white float-left m-r-10">Edit</a>
                                                {{ html()->form('DELETE', route('project.destroy', $projectamc->slug))->class('float-left')->open() }}
                                                <button type="submit" class="btn btn-xs btn-black text-white" onclick="return confirm('Are you sure? This will delete all the project history, including worked hours.')">Delete</button>
                                                {{ html()->form()->close() }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="quickview-wrapper calendar-event" id="calendar-event" style="width: 800px;right: -800px;">
            <div class="view-port clearfix" id="eventFormController">
                <div class="view bg-white">
                    <div class="scrollable">
                        <div class="p-l-30 p-r-30 p-t-20">
                            <a class="pg-close text-master link pull-right" data-toggle="quickview" data-toggle-element="#calendar-event" href="#"></a>
                            <h4 id="event-date">Add Project</h4>
                            <form action="{{ route('project.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Project Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="title" placeholder="Project name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Client Name</label>
                                            <input type="text" class="form-control" name="client_name" placeholder="Client name">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Deadline</label>
                                            <input type="date" class="form-control" name="deadline" placeholder="Budget">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Currency</label>
                                                    <select name="budget_currency" class="form-control" required>
                                                        <option value="NRS">NRS</option>
                                                        <option value="USD">USD</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>Budget</label>
                                                    <input type="number" class="form-control" name="budget" placeholder="Budget">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Estimated Hours</label>
                                    {{ html()->input('number', 'estimated_hours')->class('form-control')->placeholder('Estimated Hours') }}
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Assign <span class="text-danger">*</span></label>
                                            {{ html()->select('assigned_users[]', $users)->multiple()->class('form-control form-control-type multiselect')->required() }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Category <span class="text-danger">*</span></label>
                                            {{ html()->select('type', [
                                            'tech' => 'Tech',
                                            'design' => 'Design',
                                            'product' => 'Product',
                                            'potential_lead' => 'Potential Lead',
                                            'web_hosting' => 'Web Hosting',
                                            'amc' => 'AMC'
                                            ])->class('form-control form-control-type')->placeholder('Select Type')->required() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Deliverables</label>
                                    <div>
                                        <input name="deliverables" type="text" class="tagsinput custom-tag-input" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" placeholder="Project description" id="txtEventDesc" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Process prepopulate checklist</label>
                                    <div class="process-prepopulate-checklist-tech">
                                        @foreach($process_tech as $key => $processTech)
                                        <div class="checkbox check-primary">
                                            <input name="process[]" type="checkbox" value="{{ $key }}" id="checkbox_tech_{{ $key }}" class="process-checkbox">
                                            <label for="checkbox_tech_{{ $key }}">{{ $processTech }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="process-prepopulate-checklist-design">
                                        @foreach($process_design as $key => $processDesign)
                                        <div class="checkbox check-primary">
                                            <input name="process[]" type="checkbox" value="{{ $key }}" id="checkbox_design_{{ $key }}" class="process-checkbox">
                                            <label for="checkbox_design_{{ $key }}">{{ $processDesign }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                    {{-- <div class="process-prepopulate-checklist-amc">
                                        @foreach($process_amc as $key => $processAmc)
                                        <div class="checkbox check-primary">
                                            <input name="process[]" type="checkbox" value="{{ $key }}" id="checkbox_amc_{{ $key }}" class="process-checkbox">
                                            <label for="checkbox_amc_{{ $key }}">{{ $processAmc }}</label>
                                        </div>
                                        @endforeach
                                    </div> --}}
                                </div>
                                <button id="eventSave" class="btn btn-primary btn-black">Save Project</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Calendar Events Form -->
    </div>
</div>
@endsection
@push('styles')
<link rel="stylesheet" href="{{ asset('dist/assets/custom/multiselect/css/jquery.multiselect.css')}}">
<link href="{{ asset('dist/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<style>
    .process-prepopulate-checklist-tech, .process-prepopulate-checklist-design, .process-prepopulate-checklist-amc {
		display: none;
	}
	.table-datatable {
		width: 100% !important;
	}
	table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after {
		content: '';
	}
	table.table-bordered.dataTable {
    	border-collapse: collapse !important;
	}
    .nav-tabs {
        background-color: #323237;
        padding: 3px;
    }
    .nav-tabs > li > a {
        color: #FFFFFF;
    }
    .nav-tabs > li > a.active:hover, .nav-tabs > li > a.active:focus {
        color: #FFFFFF;
    }
    .nav-tabs > li > a:hover, .nav-tabs > li > a:focus {
        color: #FFFFFF;
    }
    .table.dataTable.no-footer {
        border: 1px solid #dee2e6;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('dist/assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/custom/multiselect/js/jquery.multiselect.js')}}"></script>
<script src="{{ asset('dist/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/datatables-responsive/js/datatables.responsive.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/datatables-responsive/js/lodash.min.js')}}" type="text/javascript"></script>
<script>
var settings = { "sDom": "<t><'row'<p i>>", "destroy": true, "scrollCollapse": true, "oLanguage": { "sLengthMenu": "_MENU_ ", "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries" }, "iDisplayLength": 15 };
$('.table-datatable').dataTable(settings);

</script>
<script>
$('.project-star').on('click', function() {
    var $this = $(this);
    var project_id = $(this).data('project-id');
    $.ajax({
        type: 'get',
        url: '/project/' + project_id + '/star',
        success: function(res) {
            if (res.status == 'created') {
                $this.addClass('fa-star');
                $this.removeClass('fa-star-o');
            } else {
                $this.removeClass('fa-star');
                $this.addClass('fa-star-o');
            }
        }
    });
});

$(function() {
    $(".multiselect").multiselect({
        columns: 1,
        placeholder: 'Select employee',
        search: true
    });
});

jQuery(document).ready(function($) {
    
    $("body").on('click', '.clickable-row', function() {
        window.location = $(this).parent('tr').data("href");
    });
});

$('.custom-tag-input').tagsinput({});

$('.add-project').on('click', function() {
    $('#calendar-event').addClass('open');
});

$('.form-control-type').on('change', function() {
    $('.process-checkbox').prop("checked", false);
    if ($(this).val() == 'tech') {
        $('.process-prepopulate-checklist-design').hide();
        $('.process-prepopulate-checklist-tech').show();
    } else if ($(this).val() == 'design') {
        $('.process-prepopulate-checklist-design').show();
        $('.process-prepopulate-checklist-tech').hide();
    } else {
        $('.process-prepopulate-checklist-design').hide();
        $('.process-prepopulate-checklist-tech').hide();
    }
});

</script>
@endpush
