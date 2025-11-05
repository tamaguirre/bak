import 'bootstrap';

import { createApp } from 'vue'

import users from './pages/users.vue'

const app = createApp({})
app.component('users', users)

app.mount('#app')
