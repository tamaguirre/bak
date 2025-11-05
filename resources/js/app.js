import 'bootstrap';

import { createApp } from 'vue'


const mount = (selector, comp, props = {}) => {
    const el = document.querySelector(selector)
    if (!el) return
    const app = createApp(comp, props)

    app.mount(el)
    return app
}

mount('#app', Hello)
