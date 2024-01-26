@extends('master')

@push('styles')
    @vite('resources/css/app.scss')
@endpush

@section('content')
    <div class="row">
        <div class="col">
            <card title="Projects" color="blue-grey-2">
                <vue-datatable datatable-id="projects" ref="projects" url="{{route('projects.ajax')}}">
                    <dt-column name="path" data="path" class-name="nowrap">Project</dt-column>
                    <dt-column name="type" data="type" :render="renderType">Type</dt-column>
                    <dt-column name="database" data="database">Database</dt-column>
                    <dt-column name="created_at" data="created_at">Created At</dt-column>
                    <dt-column name="updated_at" data="updated_at">Updated At</dt-column>
                </vue-datatable>
            </card>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/projects.js')
@endpush