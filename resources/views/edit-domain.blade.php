@extends('master')

@push('styles')
    @vite('resources/css/app.scss')
@endpush

@section('content')
    <div class="row">
        <div class="col">
            @if(session()->has('success'))
                <x-alert type="success">
                    The domain has been updated successfully and, its provision has been initialized.
                </x-alert>
            @elseif($errors->any())
                <x-alert type="danger">
                    Please correct the following fields to proceed:
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif
            <card title="Edit Domain" color="brown-4">
                <form action="{{route('domains.edit', $domain->id)}}" method="post" id="edit-domain">
                    {{csrf_field()}}

                    <div class="form-group row">
                        <label for="type" class="form-label col-lg-2 col-md-4 col-6">Project</label>
                        <div class="col-lg-10 col-md-8 col-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="btn btn-outline-secondary" @click="generateStub">Generate Stub</div>
                                </div>
                                <select name="project" id="project" class="form-control" required>
                                    <option value="">-- Project --</option>
                                    @foreach($projects as $id => $path)
                                        <option value="{{$id}}" @selected($domain->project_id == $id)>{{$path}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="domain" class="form-label col-lg-2 col-md-4 col-6">Primary Domain</label>
                        <div class="col-lg-10 col-md-8 col-6">
                            <input type="text" name="domain" id="domain" class="form-control" required placeholder="Primary Domain e.g. abc.docker" value="{{ $domain->name }}">
                        </div>
                    </div>
                    <div class="row">
                        <label class="form-label col-lg-2 col-md-4 col-6">Webserver Configuration <small class="badge badge-secondary">nginx</small></label>
                        <div class="col-lg-10 col-md-8 col-6">
                            <code-editor ref="configEditor" name="config" value="{!! old('config', $config) !!}"></code-editor>
                        </div>
                    </div>
                    <button class="d-none" type="submit" id="submit">Submit</button>
                </form>
                <template #footer>
                    <div class="btn btn-primary" @click="submit">Update</div>
                </template>
            </card>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/edit-domain.js')
@endpush
