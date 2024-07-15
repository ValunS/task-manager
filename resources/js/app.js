import "./bootstrap";

import "bootstrap/dist/css/bootstrap.min.css";

import { createApp } from "vue";
import App from "./App.vue";
import axios from "@/axios";

import VueAxios from "vue-axios";

import { router, store } from "./routes";

// Имортируем Vuex и наше хранилище

const app = createApp(App);

app.use(store);
store.dispatch("auth/checkToken").then(() => {
    app.use(router);
    app.use(VueAxios, axios);
    app.mount("#app");
});
