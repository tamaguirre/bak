<script setup>
import { onMounted, ref } from 'vue';
import api from '@/axios.js';


const reservations = ref([]);
const event_types = ref([])
const create = ref({
    event_type_id: ''
})
const edit = ref({})
const errors = ref({})

const loadReservations = async () => {
    const { data } = await api.get('/reservations')
    reservations.value = data.data
}

const loadEventTypes = async () => {
    const { data } = await api.get('/event-types')
    event_types.value = data.data
}

const show = (reservation) => {
    edit.value = reservation
}

const store = async ({ close }) => {
    try {
        create.value.reservation_id = edit.value.id
        await api.post('/events', create.value)
        await loadReservations()
        close()
    }
    catch (err) {
        if (err.response?.status === 422) {
            errors.value = err.response.data.errors
        } else {
            console.error(err)
        }
    }
}

onMounted(() => {
    loadReservations()
    loadEventTypes()
})
</script>

<template>
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">Mis Reservas</h6>
    </div>

    <div class="row g-3">
        <div class="col-12 col-sm-6 col-lg-4" v-for="reservation in reservations" :key="reservation.id">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div>
                            <h6 class="mb-0">{{ reservation.room?.name }}</h6>
                            <small class="text-muted">{{ reservation.room?.type?.name }}</small>
                        </div>
                    </div>

                    <div class="mb-2">
                        <small class="text-muted d-block">Fecha y Hora</small>
                        <strong>{{ reservation.start_date.slice(0, 16).replace('T', ' ') }}</strong>
                        <span class="text-muted"> - </span>
                        <strong>{{ reservation.end_date.slice(11, 16) }}</strong>
                    </div>

                    <div class="mb-2">
                        <small class="text-muted d-block">Estado</small>
                        <span class="badge" :class="'bg-' + reservation.status?.color">
                            {{ reservation.status?.name }}
                        </span>
                    </div>

                    <div class="mt-auto d-flex gap-2">
                        <button
                            @click="show(reservation)"
                            data-bs-toggle="modal"
                            data-bs-target="#dlgAddEvent"
                            class="btn btn-sm bg-primary text-white flex-fill">
                            Registrar Evento
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <modal id="dlgAddEvent" title="Registrar Evento" ok-text="Guardar" @ok="store">
        <div class="row">
            <div class="col-12">
                <select class="form-control" v-model="create.event_type_id">
                    <option value="">--Seleccione--</option>
                    <option
                        v-for="event_type in event_types"
                        :key="event_type.id"
                        :value="event_type.id">
                        {{ event_type.name }}
                    </option>
                </select>
                <p class="text-danger" v-if="errors.event_type_id">{{ errors.event_type_id[0] }}</p>
            </div>
        </div>
    </modal>
</template>
