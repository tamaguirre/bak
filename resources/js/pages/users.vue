<script setup>
import { onMounted, ref } from 'vue';
import api from '@/axios.js'


const users = ref([]);
const create = ref({})
const edit = ref({})
const errors = ref({})

const loadUsers = async () => {
    const { data } = await api.get('/users')
    users.value = data.data
}

const store = async ({ close }) => {
    try {
        await api.post('/users', create.value)
        loadUsers()
        close()
    } catch (err) {
        if (err.response?.status === 422) {
            errors.value = err.response.data.errors
        } else {
            console.error(err)
        }
    }
}

const update = async ({ close }) => {
    try {
        await api.put('/users/' + edit.value.id, edit.value)
        loadUsers()
        close()
    } catch (err) {
        if (err.response?.status === 422) {
            errors.value = err.response.data.errors
        } else {
            console.error(err)
        }
    }
}

const destroy = async ({ close }) => {
    try {
        await api.delete('/users/' + edit.value.id)
        loadUsers()
        close()
    } catch (err) {
        console.error(err)
    }
}

onMounted(() => {
    loadUsers()
})
</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <div class="card-title">
                        Usuarios
                        <button
                            data-bs-toggle="modal"
                            data-bs-target="#dlgCreate"
                            class="btn btn-sm text-white border-white float-end">
                            Nuevo
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>E-mail</th>
                                <th class="th-end"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users" :key="user.id">
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button
                                            @click="edit = { ...user }"
                                            data-bs-toggle="modal"
                                            data-bs-target="#dlgEdit"
                                            class="btn btn-sm bg-primary text-white">
                                            Editar
                                        </button>
                                        <button
                                            @click="edit = { ...user }"
                                            data-bs-toggle="modal"
                                            data-bs-target="#dlgDelete"
                                            class="btn btn-sm bg-danger text-white">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <modal id="dlgCreate" title="Crear Usuario" ok-text="Guardar" @ok="store">
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" v-model="create.name">
                <p class="text-danger" v-if="errors?.name">{{ errors.name[0] }}</p>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" class="form-control" v-model="create.email">
                <p class="text-danger" v-if="errors.email">{{ errors.email[0] }}</p>
            </div>

            <div class="col-12">
                <label class="form-label">Contraseña</label>
                <input type="password" class="form-control" v-model="create.password">
                <p class="text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
            </div>
        </div>
    </modal>

    <modal id="dlgEdit" title="Editar Usuario" ok-text="Guardar" @ok="update">
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" v-model="edit.name">
                <p class="text-danger" v-if="errors?.name">{{ errors.name[0] }}</p>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" class="form-control" v-model="edit.email">
                <p class="text-danger" v-if="errors.email">{{ errors.email[0] }}</p>
            </div>

            <div class="col-12">
                <label class="form-label">Contraseña</label>
                <input type="password" class="form-control" v-model="edit.password">
                <p class="text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
            </div>
        </div>
    </modal>

    <modal
        id="dlgDelete"
        title="¿ Estás seguro de eliminar este usuario ?"
        ok-text="Si"
        cancelText="No"
        :is-question="true"
        @ok="destroy">
    </modal>
</template>
