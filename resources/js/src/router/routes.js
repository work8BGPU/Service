import Main from "@/pages/Main.vue";
import Login from "@/pages/Login.vue";
import EmployeeCreate from "@/pages/Employee/Create.vue";

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
    {
        path: "/employees/create",
        component: EmployeeCreate,
        name: "employee.create",
    },
];

export default routes;
