<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <h2 class="p-3 text-center">Send notifications</h2>
            </div>
        </div>

        <form class="form mt-5" @submit.prevent="saveForm">
            <p class="text-danger" v-for="error in errors" :key="error">
                <span v-for="err in error" :key="err">{{ err }}</span>
            </p>
            <div class="row form-group">
                <div class="col">
                    <label for="name">Message:</label>
                </div>
            </div>
            <div class="row form-group">
                <div class="col">
                    <textarea
                        type="text"
                        class="form-control"
                        id="name"
                        v-model="form.message"
                    ></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-3">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <input
                                    type="radio"
                                    class="form-control"
                                    id="now"
                                    value="now"
                                    v-model="form.type"
                                />
                            </div>
                            <div class="col">
                                <label for="male" class="m-0">Send Now</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <input
                                    type="radio"
                                    class="form-control"
                                    id="schedule"
                                    value="schedule"
                                    v-model="form.type"
                                />
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div><label for="female" class="m-0">Schedule</label></div>
                                </div>
                                <div class="row">
                                    <div><input type="datetime-local" class="form-control" placeholder="Enter Date Time" v-model="form.schedule"/></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row form-group mt-3">
                <div class="col">
                    <button type="submit" class="btn btn-primary">
                        Submit
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
            message: "",
            type: "",
            schedule: null,
        });
        const saveForm = () => {
            createNotification();
            resetForm();
        };
        let errors = ref([]);

        const createNotification = () => {
            axios
                .post("/api/notification/save", form)
                .then((response) => {
                    console.log(response);
                    if (response.data.success) {
                        router.push({ name: "Reports" });
                    } else {
                        errors.value = response.data.message;
                    }
                })
                .catch((e) => {
                    errors.value = e.response.data.message;
                });
        };

        const resetForm = () => {
            form.message = "";
            form.type = "";
            form.schedule = null;
        };

        return {
            form,
            saveForm,
            errors,
        };
    },
};
</script>
