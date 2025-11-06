<script setup>
import { onMounted, ref } from 'vue';
import api from '@/axios.js'

const rooms = ref([]);
const create = ref({})
const edit = ref({})
const errors = ref({})

const loadRooms = async () => {
    const { data } = await api.get('/rooms')
    rooms.value = data.data
}

const store = async ({ close }) => {
    try {
        await api.post('/rooms', create.value)
        loadRooms()
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
        await api.put('/rooms/' + edit.value.id, edit.value)
        loadRooms()
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
        await api.delete('/rooms/' + edit.value.id)
        loadRooms()
        close()
    } catch (err) {
        console.error(err)
    }
}

onMounted(() => {
    loadRooms()
})
</script>

<template>
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">Pabellones</h6>
        <button
            data-bs-toggle="modal"
            data-bs-target="#dlgCreate"
            class="btn btn-outline-primary float-end">
            Nuevo
        </button>
    </div>

    <div class="row g-3">
        <div class="col-12 col-sm-6 col-lg-4" v-for="room in rooms" :key="room.id">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="min-w-0">
                            <div class="fw-semibold text-truncate">{{ room.name }}</div>
                        </div>
                    </div>

                    <div class="mt-auto d-flex gap-2">
                        <button
                            @click="edit = { ...room }"
                            data-bs-toggle="modal"
                            data-bs-target="#dlgEdit"
                            class="btn btn-sm bg-primary text-white flex-fill">
                            Editar
                        </button>

                        <button
                            @click="edit = { ...room }"
                            data-bs-toggle="modal"
                            data-bs-target="#dlgDelete"
                            class="btn btn-sm btn-outline-danger flex-fill">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <modal id="dlgCreate" title="Crear Pabellón" ok-text="Guardar" @ok="store">
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" v-model="create.name">
                <p class="text-danger" v-if="errors?.name">{{ errors.name[0] }}</p>
            </div>
        </div>
    </modal>

    <modal id="dlgEdit" title="Editar Pabellón" ok-text="Guardar" @ok="update">
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" v-model="edit.name">
                <p class="text-danger" v-if="errors?.name">{{ errors.name[0] }}</p>
            </div>
        </div>
    </modal>

    <modal
        id="dlgDelete"
        title="¿ Estás seguro de eliminar este pabellón ?"
        ok-text="Si"
        cancelText="No"
        :is-question="true"
        @ok="destroy">
    </modal>
</template>
