<div vue-template>
    <a href="{{route('domains.edit', ['domain' => $domain->id])}}" class="btn btn-sm btn-outline-brown-6 mr-2" title="Edit Domain"><font-awesome-icon icon="fas fa-pen-to-square" size="lg"></font-awesome-icon></a>
    <div class="btn btn-sm btn-outline-danger delete-domain" title="Delete domain {{$domain->name}}" @click="remove"><font-awesome-icon icon="far fa-trash-alt" size="lg"></font-awesome-icon></div>
</div>