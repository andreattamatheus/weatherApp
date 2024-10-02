import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import axios from "./plugins/axios";
import VueCookies from "vue-cookies";

import "@/assets/css/tailwind.css";
import "vue-loading-overlay/dist/css/index.css";

const app = createApp(App);

app.use(router)
    .use(axios, {
        baseUrl: process.env.VUE_APP_URL,
    })
    .use(VueCookies)
    .mount("#app");
