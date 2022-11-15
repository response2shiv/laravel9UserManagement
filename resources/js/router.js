import { createWebHistory, createRouter } from 'vue-router';
import home from './pages/home.vue';
import login from './pages/login.vue';
import register from './pages/register.vue';
import dashboard from './pages/dashboard.vue';
import store from './store/index.js';
import verifyAccount from "./pages/verifyAccount.vue";
import addCustomer from "./pages/addCustomer.vue";
import listCustomer from "./pages/listCustomer.vue";
import sendNotifications from "./pages/sendNotifications.vue";
import reports from "./pages/reports.vue";

const routes = [
    {
        path: "/",
        name: "Home",
        component: home,
    },
    {
        path: "/login",
        name: "Login",
        component: login,
        meta: {
            requiresAuth: false,
        },
    },
    {
        path: "/register",
        name: "Register",
        component: register,
        meta: {
            requiresAuth: false,
        },
    },
    {
        path: "/dashboard",
        name: "Dashboard",
        component: dashboard,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/verify-account/:token",
        name: "VerifyAccount",
        component: verifyAccount,
        meta: {
            requiresAuth: false,
        },
    },
    {
        path: "/dashboard/add-customer",
        name: "AddCustomer",
        component: addCustomer,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/dashboard/list-customer",
        name: "ListCustomer",
        component: listCustomer,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/dashboard/send-notifications",
        name: "SendNotifications",
        component: sendNotifications,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/dashboard/reports",
        name: "Reports",
        component: reports,
        meta: {
            requiresAuth: true,
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to,from) => {
    if (to.meta.requiresAuth && !store.getters.getToken) {
        return { name: "Login" };
    }
    if (to.meta.requiresAuth == false && store.getters.getToken) {
        return { name: "Dashboard" };
    }
});

export default router;