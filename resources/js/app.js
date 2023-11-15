import '../../node_modules/bootstrap/dist/css/bootstrap.css';
import '../../node_modules/bootstrap/dist/js/bootstrap.js';
import { createApp } from 'vue'
import App from './views/App.vue';
import router from '@/router'

const app = createApp(App)
app.use(router)
// app.provide(/* key */ 'message', /* value */ 'hello!')
app.mount('#app')