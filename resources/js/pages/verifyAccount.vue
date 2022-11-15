<template>
    <div>
        <div v-if="form.isTokenVerified">
            <p>Hi {{form.name}}
Your account has been activated successfully.
You can login from <router-link to="/login">here</router-link></p>
        </div>
        <div v-else>
            <p>Please wait...</p>
            <p>We are verifying your account.</p>
        </div>
    </div>
</template>
<script>
import { reactive, ref } from "vue";
import { useRoute } from "vue-router";
export default {
    setup() {
        const route = useRoute();
        let form = reactive({
            token: route.params.token,
            isTokenVerified: 0,
            name: ''
        });
        let error = ref("");
        return {
            form,
            error
        };
    },
    mounted() {
        axios.post("/api/verify-account", this.form).then((response) => {
            console.log(response);
            if (response.data.success) {
                this.form.isTokenVerified = 1;
                this.form.name = response.data.data.name;
                console.log(this.form);
            } else {
                this.error.value = response.data.message;
            }
        });
    }
};
</script>
