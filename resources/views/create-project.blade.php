@extends('master')

@push('styles')
    @vite('resources/css/app.scss')
@endpush

@section('content')
    <div class="row">
        <div class="col">
            <card title="Create Project" color="green-2">
                <form action="{{route('projects.add')}}" method="post" id="create-project">
                    {{csrf_field()}}

                    <div class="form-group row">
                        <label for="path" class="form-label col-lg-2 col-md-4 col-6">Path</label>
                        <div class="col-lg-10 col-md-8 col-6">
                            <input type="text" class="form-control" id="path" name="path" placeholder="Project path relative to {{env('PROJECTS_ROOT')}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="type" class="form-label col-lg-2 col-md-4 col-6">Type</label>
                        <div class="col-lg-10 col-md-8 col-6">
                            <select name="type" id="type" class="form-control" required>
                                <option value="">-- Type --</option>
                                @foreach(\App\Models\Project::$types as $type => $label)
                                    <option value="{{$type}}">{{$label}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button class="d-none" type="submit" id="submit">Submit</button>
                </form>
                <template #footer>
                    <div class="text-center">
                        <div class="btn btn-lg btn-success" @click="jQuery()('#submit').trigger('click')">Create</div>
                    </div>
                </template>
            </card>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/create-project.js')
@endpush