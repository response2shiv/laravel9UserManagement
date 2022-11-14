<template>
    <div>
        <div>
            <router-link to="/">Home</router-link>
            <router-link to="/login">Login</router-link>
            <router-link :to="{ name: 'Register' }">Register</router-link>
        </div>

        <router-view></router-view>
    </div>
</template>