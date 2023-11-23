import "../../node_modules/bootstrap/dist/css/bootstrap.css";
import "../../node_modules/bootstrap/dist/js/bootstrap.js";
import "@fortawesome/fontawesome-free/css/all.css";
import 'vue3-form-wizard/dist/style.css'
import { createApp } from "vue";
import App from "./views/App.vue";
import { createPinia } from 'pinia'
import router from "@/router";

const app = createApp(App);
const pinia = createPinia()
app.use(router);
app.use(pinia)
// app.provide(/* key */ 'message', /* value */ 'hello!')
app.mount("#app");
