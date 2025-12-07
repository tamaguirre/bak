<template>
    <div class="row mb-3">
        <div class="col-12">
            <button
                data-bs-toggle="modal"
                data-bs-target="#dlgCreate"
                class="btn btn-outline-primary float-end">
                Registrar Turno
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Filtrar por Doctor</h5>
                    <select class="form-control" v-model="filter.doctor_id" @change="loadShifts">
                        <option value="">--Sin filtro--</option>
                        <option
                            v-for="doctor in doctors"
                            :key="doctor.id"
                            :value="doctor.id">
                            {{ doctor.name }}
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-10">
            <div class="card">
                <div class="card-body calendar-container">
                    <FullCalendar :options="calendarOptions" />
                </div>
            </div>
        </div>
    </div>

    <modal id="dlgCreate" title="Registrar Turno" ok-text="Guardar" @ok="store">
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label">Fecha</label>
                <input type="date" class="form-control" v-model="create.date">
                <p class="text-danger" v-if="errors?.date">{{ errors.date[0] }}</p>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">Doctor</label>
                <select class="form-control" v-model="create.user_id">
                    <option value="null">--Seleccione--</option>
                    <option
                        v-for="doctor in doctors"
                        :key="doctor.id"
                        :value="doctor.id">
                        {{ doctor.name }}
                    </option>
                </select>
                <p class="text-danger" v-if="errors?.doctor_id">{{ errors.doctor_id[0] }}</p>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">Turno</label>
                <select class="form-control" v-model="create.shift_type_id">
                    <option value="null">--Seleccione--</option>
                    <option
                        v-for="shift_type in shift_types"
                        :key="shift_type.id"
                        :value="shift_type.id">
                        {{ shift_type.name }}
                    </option>
                </select>
                <p class="text-danger" v-if="errors?.shift_type_id">{{ errors.shift_type_id[0] }}</p>
            </div>
        </div>
    </modal>

    <modal
        id="dlgDelete"
       title="¿Estás seguro de eliminar este Turno?"
        ok-text="Si"
        cancelText="No"
        :is-question="true"
        @ok="destroy">
    </modal>
</template>

<script lang="js" setup>
import { onMounted, ref } from 'vue';
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import listPlugin from '@fullcalendar/list'
import interactionPlugin from '@fullcalendar/interaction'
import esLocale from '@fullcalendar/core/locales/es'
import api from '@/axios';
import * as bootstrap from 'bootstrap';
import dayjs  from 'dayjs';

const shift_types = ref([])
const doctors = ref([])
const filter = ref({
    doctor_id: ''
})
const create = ref({
    date: '',
    start_time: '',
    end_time: '',
    user_id: null,
    shift_type_id: null
})

const calendarOptions = ref({
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
    initialView: 'timeGridWeek',
    locale: esLocale,
    headerToolbar: {
        left: 'prev,next',
        center: 'title',
        right: 'timeGridWeek,timeGridDay'
    },
    allDaySlot: false,
    slotDuration: '02:00:00',
    slotLabelInterval: '02:00:00',
    slotLabelFormat: {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    },
    height: 'auto',
    events: [],
    editable: false,
    selectable: false,
    droppable: false,
    selectMirror: true,
    dayMaxEvents: true,
    weekends: true,
    eventContent: (arg) => {
        return { html: arg.event.title }
    }
})

const store = async () => {
    try {
        await api.post('/shifts', create.value)
        loadShifts()
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('dlgCreate'))
        modal.hide()
    }
    catch (err) {
        if (err.response?.status === 422) {
            errors.value = err.response.data.errors
        } else {
            console.error(err)
        }
    }
}

const destroy = async () => {
    try {
        await api.delete('/reservations/' + edit.value)
        loadShifts()
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('dlgDelete'))
        modal.hide()
    }
    catch (err) {
        console.error(err)
    }
}

const loadShiftTypes = async () => {
    const { data } = await api.get('/shift-types')
    shift_types.value = data.data
}

const loadDoctors = async () => {
    const { data } = await api.get('/users?role_id=4')
    doctors.value = data.data
}

const loadShifts = async () => {
    const { data } = await api.get('/shifts?doctor_id=' + (filter.value.doctor_id || ''))

    const groupedShifts = data.data.reduce((acc, shift) => {
        const key = `${shift.date}_${shift.type?.start_time}_${shift.type?.end_time}`

        if (!acc[key]) {
            acc[key] = {
                date: shift.date,
                type: shift.type,
                doctors: []
            }
        }

        acc[key].doctors.push(shift.user?.name)

        return acc
    }, {})

    calendarOptions.value.events = Object.values(groupedShifts).map(group => {
        const doctorsList = group.doctors.join('<br>')

        return {
            title: `${group.type?.name}<br>${doctorsList}`,
            start: `${group.date} ${group.type?.start_time}`,
            end: `${group.date} ${group.type?.end_time}`,
            backgroundColor: group.type?.color,
            borderColor: group.type?.color,
            textColor: '#fff',
            allDay: false,
        }
    })
}

onMounted(() => {
    loadDoctors()
    loadShiftTypes()
    loadShifts()
})
</script>

<style scoped>
.calendar-container {
    padding: 20px;
}
</style>
