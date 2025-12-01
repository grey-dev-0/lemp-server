@extends('master')

@push('styles')
    @vite('resources/css/app.scss')
@endpush
@section('content')
    <div id="app" class="row">
        <div class="col col-md-6">
            <card title="Stack Status" color="blue-grey-2" no-padding>
                <template #toolbar>
                    <button @@click="fetchStackStatus" class="btn btn-sm btn-primary" :disabled="isLoading">
                        <span v-if="!isLoading">Refresh</span>
                        <span v-else>
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Loading...
                        </span>
                    </button>
                </template>
                <div v-if="error" class="alert alert-danger m-3" role="alert">
                    @{{ error }}
                </div>
                <list v-else :collapse="false">
                    <list-item v-for="service in services" :key="service.name">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <span>@{{ service.name }}</span>
                            <div class="d-flex align-items-center gap-2">
                                <div @@click="restartService(service.name)" :class="service.status === 'exited' ? 'btn-success' : 'btn-info'" class="btn btn-sm d-inline-block mr-2 text-white" :disabled="service.restarting" :title="service.status === 'exited' ? 'Start service' : 'Restart service'">
                                    <span v-if="!service.restarting">
                                        <font-awesome-icon :icon="service.status === 'exited' ? 'fas fa-play' : 'fas fa-rotate-right'"></font-awesome-icon>
                                    </span>
                                    <span v-else>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </span>
                                </div>
                                <span :class="getStatusBadgeClass(service.status)" class="badge text-white">
                                    @{{ service.status }}
                                </span>
                            </div>
                        </div>
                    </list-item>
                </list>
                <div v-if="timestamp" class="text-muted px-3 py-2 text-right">
                    <small>Last updated: @{{ formatTimestamp(timestamp) }}</small>
                </div>
            </card>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/home.js')
@endpush