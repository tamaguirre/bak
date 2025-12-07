import 'bootstrap';

import { createApp } from 'vue'

import users from './pages/users.vue'
import rooms from './pages/rooms.vue'
import calendar from './pages/calendar.vue'
import shifts from './pages/shifts.vue'
import myReservations from './pages/my-reservations.vue'

import modal from './components/modal.vue'

const app = createApp({})
app.component('users', users)
app.component('rooms', rooms)
app.component('calendar', calendar)
app.component('shifts', shifts)
app.component('my-reservations', myReservations)

app.component('modal', modal)

app.mount('#app')
