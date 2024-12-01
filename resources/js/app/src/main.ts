import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import { createPinia } from "pinia";

import "@/styles/css/tailwind.css";
import "vue-loading-overlay/dist/css/index.css";

const pinia = createPinia();
const app = createApp(App);

app.use(router).use(pinia).mount("#app");
