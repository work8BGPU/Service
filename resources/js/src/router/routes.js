import Main from "@/pages/Main.vue";
import Login from "@/pages/Login.vue";

const routes = [
    {
        path: "/",
        component: Main,
        name: "main",
    },
    {
        path: "/login",
        component: Login,
        name: "login",
    },
];

export default routes;
