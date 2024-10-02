import { createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home/Index.vue";
import LoginView from "../views/Login/Index.vue";
import ErrorView from "../views/Error/Index.vue";

const routes = [
    {
        path: "/login",
        name: "login",
        component: LoginView,
        meta: {
            requiresAuth: false,
        },
    },
    {
        path: "/home",
        name: "home",
        component: Home,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/:pathMatch(.*)*",
        name: "error",
        component: ErrorView,
    },
];

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes,
});

export default router;
