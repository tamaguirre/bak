import 'bootstrap';

import { createApp } from 'vue'

import users from './pages/users.vue'
import rooms from './pages/rooms.vue'

import modal from './components/modal.vue'

const app = createApp({})
app.component('users', users)
app.component('rooms', rooms)

app.component('modal', modal)

app.mount('#app')
