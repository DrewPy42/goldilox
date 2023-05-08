import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from "./router";
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"
/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import {} from './assets/fontAwesome.js';

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.component('font-awesome-icon', FontAwesomeIcon)
app.mount('#app')
