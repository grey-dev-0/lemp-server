@extends('master')

@push('styles')
    @vite('resources/css/app.scss')
@endpush

@section('content')
    <div class="row">
        <div class="col">
            @if(session()->has('success'))
                <x-alert type="success">
                    The project has been created successfully and, its provision has been initialized.
                </x-alert>
            @endif
            <card title="Projects" color="blue-grey-2" no-padding>
                <template #toolbar>
                    <a href="{{route('projects.add')}}" class="btn btn-sm btn-success"><font-awesome-icon icon="fas fa-plus"></font-awesome-icon> Add Project</a>
                </template>
                <vue-datatable datatable-id="projects" ref="projects" url="{{route('projects.ajax')}}">
                    <dt-column name="path" data="path" class-name="nowrap">Project</dt-column>
                    <dt-column name="type" data="type" :render="renderType">Type</dt-column>
                    <dt-column name="database" data="database">Database</dt-column>
                    <dt-column name="provisioned_at" data="provisioned_at">Provisioned At</dt-column>
                    <dt-column name="created_at" data="created_at">Created At</dt-column>
                    <dt-column name="updated_at" data="updated_at">Updated At</dt-column>
                    <dt-column data="actions" :searchable="false" :orderable="false" :render="renderActions">Actions</dt-column>
                </vue-datatable>
            </card>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let projectTypes = @json(App\Models\Project::$types);
    </script>
    @vite('resources/js/projects.js')
@endpush