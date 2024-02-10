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
                    <div class="form-group row">
                        <label for="database" class="form-label col-lg-2 col-md-4 col-6">Database</label>
                        <div class="col-lg-10 col-md-8 col-6">
                            <input type="text" class="form-control" id="database" name="database" placeholder="Project database name" required>
                        </div>
                    </div>
                    <div class="row">
                        <label class="form-label col-lg-2 col-md-4 col-6">Domains</label>
                        <div class="col-lg-10 col-md-8 col-6">
                            <div v-for="(domain, i) in domains" :key="i" :class="'input-group mb-3' + (domains.length - 1 == i? '' : ' form-group')">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" :id="'https-' + i" v-model="domain.https" :name="'domains['+i+'][https]'" value="1">
                                            <label :for="'https-' + i" class="form-check-label">https</label>
                                        </div>
                                    </div>
                                </div>
                                <input type="text" class="form-control" :id="'domains-' + i" :name="'domains['+i+'][name]'" placeholder="Project domain e.g. myproject.docker" v-model="domain.name" required>
                                <div class="input-group-append">
                                    <div v-if="domains.length > 1" class="btn btn-outline-danger" @click="remove(i)">Remove</div>
                                    <div v-if="domains.length - 1 == i" class="btn btn-outline-success" @click="add">Add</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="d-none" type="submit" id="submit">Submit</button>
                </form>
                <template #footer>
                    <div class="btn btn-success" @click="jQuery()('#submit').trigger('click')">Create</div>
                </template>
            </card>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/create-project.js')
@endpush