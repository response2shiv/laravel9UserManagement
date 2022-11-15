<template>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <router-link class="navbar-brand" to="/"
            ><img height="50" width="50" src="/logo_hc_b.png"
        /></router-link>
        <router-link class="navbar-brand" to="/">Home</router-link>
        <ul class="navbar-nav">
            <li class="nav-item" v-if="$store.getters.getToken == 0">
                <router-link class="text-white" to="/login">Login</router-link>
            </li>
            <li class="navbar-item" v-if="$store.getters.getToken == 0">
                <router-link class="text-white ml-2" :to="{ name: 'Register' }"
                    >Register</router-link
                >
            </li>
            <li class="navbar-item" v-if="$store.getters.getToken != 0">
                <router-link class="text-white ml-2" :to="{ name: 'Dashboard' }"
                    >Dashboard</router-link
                >
            </li>
        </ul>
        <ul class="navbar-nav ml-5">
            <li class="navbar-item" v-if="$store.getters.getToken != 0">
                <button type="button" class="btn btn-dark mt-2" @click="logout">
                    Logout
                </button>
            </li>
        </ul>
    </nav>

    <div class="container" v-if="$store.getters.getToken">
        <!-- <div class="row">
            <div class="col-8 justify-content-center">
                <h2>User Authentic Page</h2>
            </div>
            <div class="col-4">
                <button type="button" class="btn btn-dark mt-2" @click="logout">
                    Logout
                </button>
            </div>
        </div> -->
        <div class="row wrapper">
            <div class="col-4">
                <!-- Sidebar -->
                <nav id="sidebar">
                    <div id="sidebar-wrapper">
                        <ul class="sidebar-nav list-unstyled">
                            <li class="sidebar-brand">
                                <router-link class="navbar-brand" to="/"
                                    ><img src="/logo_hc_b.png"
                                /></router-link>
                            </li>
                            <li>
                                <router-link
                                    class="navbar-brand"
                                    to="/dashboard/add-customer"
                                    >Add Customer</router-link
                                >
                            </li>
                            <li>
                                <router-link
                                    class="navbar-brand"
                                    to="/dashboard/list-customer"
                                    >List Customer</router-link
                                >
                            </li>
                            <li>
                                <router-link
                                    class="navbar-brand"
                                    to="/dashboard/send-notifications"
                                    >Send Notifications</router-link
                                >
                            </li>
                            <li>
                                <router-link
                                    class="navbar-brand"
                                    to="/dashboard/reports"
                                    >Reports</router-link
                                >
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="col-8">
                <!-- Page Content -->
                <div id="content" class="mt-5 border" style="min-height: 500px;">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>

    <div v-if="$store.getters.getToken == 0">
        <router-view></router-view>
    </div>
</template>
<script>
import { useRouter } from "vue-router";
import { useStore } from "vuex";

export default {
    setup() {
        const router = useRouter();
        const store = useStore();

        const logout = () => {
            store.dispatch("removeToken");
            router.push({ name: "Login" });
        };

        return {
            logout,
        };
    },
};
</script>
