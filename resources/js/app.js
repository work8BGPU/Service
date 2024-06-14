import "./bootstrap";
import "@/assets/styles/style.css";

import { createApp } from "vue";
import App from "@/App.vue";

import router from "@/router/router.js";
import store from "@/store/store.js";
import axios from "axios";

const app = createApp(App);

app.use(router);

app.use(store);

app.mount("#app");
