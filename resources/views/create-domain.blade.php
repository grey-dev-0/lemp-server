@extends('master')

@push('styles')
    @vite('resources/css/app.scss')
@endpush

@section('content')
    <div class="row">
        <div class="col">
            <card title="Create Domain" color="green-2">
                <form action="{{route('domains.add')}}" method="post" id="create-domain">
                    {{csrf_field()}}

                    <div class="form-group row">
                        <label for="type" class="form-label col-lg-2 col-md-4 col-6">Project</label>
                        <div class="col-lg-10 col-md-8 col-6">
                            <select name="type" id="type" class="form-control" required>
                                <option value="">-- Project --</option>
                                @foreach($projects as $id => $path)
                                    <option value="{{$id}}">{{$path}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label for="database" class="form-label col-lg-2 col-md-4 col-6">Webserver Configuration <small class="badge badge-secondary">nginx</small></label>
                        <div class="col-lg-10 col-md-8 col-6">
                            <code-editor name="config" value="{!! old('config') !!}"></code-editor>
                        </div>
                    </div>
                    <button class="d-none" type="submit" id="submit">Submit</button>
                </form>
                <template #footer>
                    <div class="btn btn-success" @click="submit">Create</div>
                </template>
            </card>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/create-domain.js')
@endpush