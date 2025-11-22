<template>
    <div class="row">
        <div class="col-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Reservar Salas</h5>
                    <ul class="list-unstyled">
                        <li
                            v-for="room in rooms"
                            :key="room.id"
                            draggable="true"
                            @dragstart="handleDragStart($event, room)"
                            @dragend="handleDragEnd"
                            class="fc-event draggable-item"
                            :data-room="room"
                        >
                            <span class="badge bg-primary">
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

    <modal id="dlgCreate" title="Reservar Sala" ok-text="Guardar">
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

const rooms = ref([])

function handleEventClick(clickInfo) {
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('dlgDelete'))
    modal.show()
}

function handleEvents(events) {
    console.log('Eventos actuales:', events)
}

const handleDragStart = (event, room) => {
    console.log('Arrastrando:', room)
}

const handleDragEnd = () => {
    console.log('Terminó el arrastre')
}

const handleDrop = (info) => {
    console.log('Soltado en:', info)
    const room = info.draggedEl?.getAttribute('data-room')

    if (room) {
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('dlgCreate'))
        modal.show()
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
    eventClick: handleEventClick,
    eventsSet: handleEvents,
})

const loadRooms = async () => {
    const { data } = await api.get('/rooms')
    rooms.value = data.data
}

onMounted(() => {
    loadRooms()

    const draggableEl = document.querySelector('.list-unstyled')
    if (draggableEl) {
        new Draggable(draggableEl, {
            itemSelector: '.fc-event',
            eventData: function(eventEl) {
                return {
                    title: eventEl.querySelector('.badge').textContent
                }
            }
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
