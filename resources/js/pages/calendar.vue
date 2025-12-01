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
            <div class="col-6 mb-3">
                <label class="form-label">Fecha de Inicio</label>
                <input type="datetime-local" class="form-control" v-model="create.start_date">
                <p class="text-danger" v-if="errors?.start_date">{{ errors.start_date[0] }}</p>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">Fecha de Término</label>
                <input type="datetime-local" class="form-control" v-model="create.end_date">
                <p class="text-danger" v-if="errors?.end_date">{{ errors.end_date[0] }}</p>
            </div>
        </div>
        <div class="row">
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
            <div class="col-12 mb-3">
                <label class="form-label">Nombre del Paciente</label>
                <input type="text" class="form-control" v-model="create.patient_name">
                <p class="text-danger" v-if="errors?.patient_name">{{ errors.patient_name[0] }}</p>
            </div>
            <div class="col-12 mb-3">
                <label class="form-check-label">
                    <input type="checkbox" v-model="create.restroom">
                    Solicita Aseo (adicional 30 minutos)
                </label>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">Notas</label>
                <textarea type="text" class="form-control" v-model="create.notes"></textarea>
                <p class="text-danger" v-if="errors?.notes">{{ errors.notes[0] }}</p>
            </div>
        </div>
    </modal>

    <modal
        id="dlgDelete"
       title="¿Estás seguro de eliminar esta Reserva?"
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
const edit = ref(null)


function handleEventClick(clickInfo) {
    edit.value = clickInfo.event.extendedProps.reservation_id
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
    const event = info.event
    edit.value = event.extendedProps.reservation_id
    const start = event.start ? dayjs(event.start).format('YYYY-MM-DDTHH:mm') : null
    const end = event.end ? dayjs(event.end).format('YYYY-MM-DDTHH:mm') : dayjs(event.start).add(1, 'hour').format('YYYY-MM-DDTHH:mm')

    try {
        await api.put('reservations/' + edit.value, {
            start_date: start,
            end_date: end
        })
        loadReservations()
    } catch (error) {
        console.error('Error actualizando reserva:', error)
    }
}

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
// javascript
    eventDidMount: (info) => {
        info.el.style.fontSize = '11px';
        info.el.style.lineHeight = '1.2';

        const titleHtml = info.event.extendedProps?.titleHtml;
        const titleTarget = info.el.querySelector('.fc-event-title') || info.el.querySelector('.fc-event-main') || info.el;

        if (titleHtml) {
            titleTarget.innerHTML = titleHtml;
            titleTarget.style.whiteSpace = 'normal';
        }

        const tooltipHtml = info.event.extendedProps?.tooltipHtml;

        try {
            const existing = bootstrap.Tooltip.getInstance(info.el);
            if (existing) existing.dispose();

            info.el.setAttribute('data-bs-toggle', 'tooltip');
            info.el.setAttribute('data-bs-placement', 'top');
            info.el.setAttribute('data-bs-html', 'true');

            const tip = new bootstrap.Tooltip(info.el, {
                html: true,
                title: tooltipHtml || info.event.title
            });
            info.el.__bs_tooltip = tip;
        } catch (e) {
            console.error('Error initializing tooltip:', e);
        }
    }
})

const store = async () => {
    try {
        await api.post('/reservations', create.value)
        loadReservations()
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
        loadReservations()
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('dlgDelete'))
        modal.hide()
    }
    catch (err) {
        console.error(err)
    }
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
        const restroom = reservation.restroom ? '<br><strong>Aseo solicitado</strong>' : ''
        const titleText = `${reservation.room.type?.name} - ${reservation.room.name}\nDoctor: ${doctor}\nPaciente: ${reservation.patient_name}${restroom}`
        const titleHtml = titleText.replace(/\n/g, '<br>')
        const tooltipHtml = `<strong>${reservation.room.type?.name} - ${reservation.room.name}</strong><br>Doctor: ${doctor}<br>Paciente: ${reservation.patient_name}${reservation.restroom ? '<br><strong>Aseo solicitado</strong>' : ''}`

        return ({
            title: titleText,
            start: reservation.start_date,
            end: reservation.end_date,
            allDay: false,
            backgroundColor: reservation.room.type?.color,
            borderColor: reservation.room.type?.color,
            textColor: '#fff',
            extendedProps: {
                reservation_id: reservation.id,
                titleHtml,
                tooltipHtml
            }
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
