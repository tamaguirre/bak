import 'bootstrap';

import { createApp } from 'vue'

import users from './pages/users.vue'
import modal from './components/modal.vue'

const app = createApp({})
app.component('users', users)

app.component('modal', modal)

app.mount('#app')
