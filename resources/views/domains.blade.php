@extends('master')

@push('styles')
    @vite('resources/css/app.scss')
@endpush

@section('content')
    <div class="row">
        <div class="col">
            @if(session()->has('success'))
                <x-alert https="success">
                    The domain has been created successfully and, its provision has been initialized.
                </x-alert>
            @endif
            <card title="Domains" color="blue-grey-2" no-padding>
                <template #toolbar>
                    <a href="{{route('domains.add')}}" class="btn btn-sm btn-success">Add Domain</a>
                </template>
                <vue-datatable datatable-id="domains" ref="domains" url="{{route('domains.ajax')}}">
                    <dt-column name="name" data="name" class-name="nowrap">Domain</dt-column>
                    <dt-column name="https" data="https" :render="renderHttps">Https</dt-column>
                    <dt-column name="provisioned_at" data="provisioned_at">Provisioned At</dt-column>
                    <dt-column name="created_at" data="created_at">Created At</dt-column>
                    <dt-column name="updated_at" data="updated_at">Updated At</dt-column>
                    <dt-column data="actions" :searchable="false" :orderable="false">Actions</dt-column>
                </vue-datatable>
            </card>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/domains.js')
@endpush