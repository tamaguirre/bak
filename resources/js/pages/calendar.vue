<template>
    <div class="row">
        <div class="col-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Filtrar por Sala</h5>
                    <select class="form-control" v-model="filter.room_id" @change="loadReservations">
                        <option value="">--Sin filtro--</option>
                        <option
                            v-for="room in rooms"
                            :key="room.id"
                            :value="room.id">
                            {{ room.type?.name }} - {{ room.name }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Reservar Salas</h5>
                    <ul class="list-unstyled">
                        <li
                            v-for="room in rooms"
                            :key="room.id"
                            draggable="true"
                            class="fc-event draggable-item"
                            :data-room="room.id"
                        >
                            <span class="badge" :style="'background:' + room.type?.color + '; color: #fff;'">
                                {{  room.type?.name }} - {{ room.name }}
                            </span>
                        </li>
                    </ul>
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

    <modal id="dlgCreate" title="Reservar Sala" ok-text="Guardar" @ok="store">
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label">Fecha de Inicio</label>
                <input type="datetime-local" class="form-control" v-model="create.start_date">
                <p class="text-danger" v-if="errors?.start_date">{{ errors.start_date[0] }}</p>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">Fecha de Término</label>
                <input type="datetime-local" class="form-control" v-model="create.end_date">
                <p class="text-danger" v-if="errors?.end_date">{{ errors.end_date[0] }}</p>
            </div>
            <div class="col-12 mb-3">
                <label class="form-check-label">
                    <input type="checkbox" v-model="create.emergency">
                    Emergencía
                </label>
            </div>
            <div class="col-12 mb-3" v-if="!create.emergency">
                <label class="form-label">Doctor a cargo</label>
                <select class="form-control" v-model="create.doctor_id">
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
        </div>
    </modal>

    <modal id="dlgDelete" title="¿Estás seguro de eliminar esta Reserva?" ok-text="Si">
    </modal>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import listPlugin from '@fullcalendar/list'
import interactionPlugin, { Draggable } from '@fullcalendar/interaction'
import esLocale from '@fullcalendar/core/locales/es'
import api from '@/axios';
import * as bootstrap from 'bootstrap';
import dayjs  from 'dayjs';

const rooms = ref([])
const doctors = ref([])
const filter = ref({
    room_id: ''
})
const create = ref({
    start_date: '',
    end_date: '',
    room_id: null,
    doctor_id: null,
    emergency: false
})


function handleEventClick(clickInfo) {
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('dlgDelete'))
    modal.show()
}

const handleDrop = (info) => {
    const roomId = info.draggedEl?.getAttribute('data-room')
    const room = rooms.value.find(r => String(r.id) === String(roomId))

    if (room) {
        const date = info.date instanceof Date ? info.date : (info.dateStr ? new Date(info.dateStr) : null);
        const start = dayjs(date);
        create.value.room_id = room.id
        create.value.start_date = start.format('YYYY-MM-DDTHH:mm')
        create.value.end_date = start.add(1, 'hour').format('YYYY-MM-DDTHH:mm')

        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('dlgCreate'))
        modal.show()
    }
}

const handleEventUpdate = async (info) => {
    console.log(info, 'info')
    const event = info.event
    if (event.extendedProps?.temporary) {
        return
    }
    const reservationId = event.extendedProps?.reservation_id ?? event.id
    if (!reservationId) {
        console.warn('Reserva sin id, no se puede actualizar')
        return
    }

    const start = event.start ? dayjs(event.start).format('YYYY-MM-DDTHH:mm') : null
    const end = event.end ? dayjs(event.end).format('YYYY-MM-DDTHH:mm') : dayjs(event.start).add(1, 'hour').format('YYYY-MM-DDTHH:mm')

    try {
        await api.put(`/reservations/${reservationId}`, {
            start_date: start,
            end_date: end
        })
        await loadReservations()
    } catch (error) {
        console.error('Error actualizando reserva:', error)
        if (typeof info.revert === 'function') info.revert()
    }
}

const calendarOptions = ref({
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
    initialView: 'timeGridWeek',
    locale: esLocale,
    headerToolbar: {
        left: 'prev,next',
        center: 'title',
        right: 'timeGridWeek'
    },
    allDaySlot: false,
    events: [],
    editable: true,
    selectable: false,
    droppable: true,
    drop: handleDrop,
    selectMirror: true,
    dayMaxEvents: true,
    weekends: true,
    eventDrop: handleEventUpdate,
    eventResize: handleEventUpdate,
    eventClick: handleEventClick,
    eventDidMount: (info) => {
        info.el.style.fontSize = '11px';
        info.el.style.lineHeight = '1';
        const fullTitle = info.event.title || '';
        info.el.setAttribute('title', fullTitle);
        try {
            info.el.setAttribute('data-bs-toggle', 'tooltip');
            info.el.setAttribute('data-bs-placement', 'top');
            const tip = bootstrap.Tooltip.getOrCreateInstance(info.el);
            info.el.__bs_tooltip = tip;
        } catch (e) {
            console.error('Error initializing tooltip:', e);
        }
    },
    eventWillUnmount: (info) => {
        const tip = info.el.__bs_tooltip;
        if (tip) {
            tip.dispose();
            delete info.el.__bs_tooltip;
        }
    }
})

const store = async () => {
    await api.post('/reservations', create.value)
        .then(() => {
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('dlgCreate'))
            modal.hide()
            loadReservations()
        })
        .catch((error) => {
            if (error.response && error.response.data && error.response.data.errors) {
                errors.value = error.response.data.errors
            }
        })
}


const loadRooms = async () => {
    const { data } = await api.get('/rooms')
    rooms.value = data.data
}

const loadDoctors = async () => {
    const { data } = await api.get('/users?role_id=4')
    doctors.value = data.data
}

const loadReservations = async () => {
    const { data } = await api.get('/reservations?room_id=' + (filter.value.room_id || ''))

    calendarOptions.value.events = data.data.map(reservation => {
        const doctor = reservation.emergency ? 'Emergencia' : reservation.doctor?.name
        const title = reservation.room.type?.name + ' - ' + reservation.room.name +
        ', ' + doctor

        return ({
            title: title,
            start: reservation.start_date,
            end: reservation.end_date,
            allDay: false,
            backgroundColor: reservation.room.type?.color,
            borderColor: reservation.room.type?.color,
            textColor: '#fff'
        });
    })
}

onMounted(() => {
    loadRooms()
    loadDoctors()
    loadReservations()

    const draggableEl = document.querySelector('.list-unstyled')
    if (draggableEl) {
        new Draggable(draggableEl, {
            itemSelector: '.fc-event',
        })
    }
})
</script>

<style scoped>
.calendar-container {
    padding: 20px;
}

.draggable-item {
    cursor: move;
    padding: 5px 0;
}

.draggable-item:hover {
    opacity: 0.8;
}
</style>
