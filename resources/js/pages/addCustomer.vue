<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <h2 class="p-3 text-center">Add Customer</h2>
            </div>
        </div>

        <form class="form mt-5" @submit.prevent="saveForm">
            <p class="text-danger" v-for="error in errors" :key="error">
                <span v-for="err in error" :key="err">{{ err }}</span>
            </p>
            <div class="row form-group">
                <div class="col-4">
                    <label for="name">Name:</label>
                </div>
                <div class="col-8">
                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        v-model="form.name"
                    />
                </div>
            </div>
            <div class="row form-group">
                <div class="col-4">
                    <label for="name">Email:</label>
                </div>
                <div class="col-8">
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        v-model="form.email"
                    />
                </div>
            </div>
            <div class="row form-group">
                <div class="col-4">
                    <label for="name">Mobile:</label>
                </div>
                <div class="col-8">
                    <input
                        type="text"
                        class="form-control"
                        id="mobile"
                        v-model="form.mobile"
                    />
                </div>
            </div>
            <div class="row form-group mt-3">
                <div class="col-4"></div>
                <div class="col-8">
                    <button type="submit" class="btn btn-primary">
                        Add Customer
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
export default {
    setup() {
        const router = useRouter();
        const form = reactive({
            name: "",
            email: "",
            mobile: "",
        });
        const saveForm = () => {
            createUser();
            resetForm();
        };
        let errors = ref([]);

        const createUser = () => {
            axios
                .post("/api/customer/save", form)
                .then((response) => {
                    console.log(response);
                    if (response.data.success) {
                        router.push({ name: "ListCustomer" });
                    } else {
                        errors.value = response.data.message;
                    }
                })
                .catch((e) => {
                    errors.value = e.response.data.message;
                });
        };

        const resetForm = () => {
            form.name = "";
            form.email = "";
            form.mobile = "";
        };

        return {
            form,
            saveForm,
            errors,
        };
    },
};
</script>
