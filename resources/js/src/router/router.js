import { createRouter, createWebHistory } from "vue-router";
import routes from "./routes.js";

import store from "@/store/store.js";

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: "active",
    routes,
});

const token = store.state.token;
const user = store.state.user;

router.beforeEach((to, from, next) => {
    if (!token) {
        if (to.name === "login") return next();
        else return next({ name: "login" });
    }

    if (to.name === "login" && token) {
        return next({ name: "main" });
    }

    next();
});

export default router;
