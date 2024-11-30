import { createRouter, createWebHistory } from "vue-router";
import Home from "@/views/pages/Home/Index.vue";
import LoginView from "@/views/pages/Login/Index.vue";
import ErrorView from "@/views/pages/Error/Index.vue";

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

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("access_token");

    if (to.name === "login" && token) {
        next({ name: "home" });
    } else if (
        to.matched.some((record) => record.meta.requiresAuth) &&
        !token
    ) {
        next({ name: "login" });
    } else {
        next();
    }
});

export default router;
