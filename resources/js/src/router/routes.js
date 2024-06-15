import Main from "@/pages/Main.vue";
import Login from "@/pages/Login.vue";
import EmployeeCreate from "@/pages/Employee/Create.vue";
import UserCreate from "@/pages/User/Create.vue";

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
    {
        path: "/users/create",
        component: UserCreate,
        name: "user.create"
    }
];

export default routes;
