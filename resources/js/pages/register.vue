<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <div class="justify-content-center">
                    <img src="/banner.jpg" class="banner mt-4" />
                </div>
            </div>
            <div class="col-sm-8">
                <div class="row justify-content-center">
                    <div class="col-sm-6 mt-4">
                        <h2>Register</h2>

                        <p v-if="process.verificationEmail">
                            Hi {{form.name}},
Activation link has been sent to {{form.email}}.
Please check your email and activate your account.
                        </p>
                        <p
                            class="text-danger"
                            v-for="error in errors"
                            :key="error"
                        >
                            <span v-for="err in error" :key="err">{{
                                err
                            }}</span>
                        </p>

                        <form @submit.prevent="register">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    v-model="form.name"
                                />
                            </div>
                            <div class="form-group">
                                <label for="email">Email Id:</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    v-model="form.email"
                                />
                            </div>
                            <div class="form-group">
                                <label for="phone">Mobile:</label>
                                <div class="row">
                                    <div class="col-8">
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="phone"
                                            v-model="form.phone"
                                        />
                                    </div>
                                    <div class="col-4">
                                        <span
                                            ><button
                                                type="button"
                                                class="btn btn-primary"
                                                @click.prevent="sendOtp"
                                                :disabled="form.isOtpSend == 1"
                                            >
                                                Get OTP
                                            </button></span
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <p>email recieved otp is : {{ form.otp }}</p>
                                <div class="row">
                                    <div class="col-4">
                                        <label for="userOtp">Enter OTP:</label>
                                    </div>
                                    <div class="col-4">
                                        <input
                                            type="text"
                                            maxlength="4"
                                            class="form-control"
                                            @keyup="verifyOtpValue"
                                            id="userOtp"
                                            v-model="form.userInputOtp"
                                        /><input
                                            type="hidden"
                                            id="isOtpVerified"
                                            v-model="form.isOtpVerified"
                                        />
                                    </div>
                                    <div class="col-4">
                                        <span v-if="form.isOtpVerified">OTP validated</span>
                                        <span v-if="form.userInputOtp.length == 4 && form.userInputOtp != form.otp">invalid OTP</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    v-model="form.password"
                                />
                            </div>
                            <div class="form-group">
                                <label for="password">State:</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="state"
                                    v-model="form.state"
                                />
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-4">
                                        <label for="password">Gender:</label>
                                    </div>
                                    <div class="col-4">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <input
                                                    type="radio"
                                                    class="form-control"
                                                    id="male"
                                                    value="male"
                                                    v-model="form.gender"
                                                />
                                            </div>
                                            <div class="col">
                                                <label for="male" class="m-0"
                                                    >Male</label
                                                >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <input
                                                    type="radio"
                                                    class="form-control"
                                                    id="female"
                                                    value="female"
                                                    v-model="form.gender"
                                                />
                                            </div>
                                            <div class="col">
                                                <label for="female" class="m-0"
                                                    >Female</label
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";
export default {
    setup() {
        const router = useRouter();
        const store = useStore();

        let process = reactive({
            verificationEmail: false,
        });
        let form = reactive({
            name: "",
            email: "",
            phone: "",
            isOtpVerified: 0,
            password: "",
            state: "",
            gender: "",
            otp: "",
            userInputOtp: "",
            isOtpSend: 0,
        });
        let errors = ref([]);
        const register = async () => {
            if (form.isOtpVerified == 0) {
                errors.value = ["Please verify your phone number first."];
                return false;
            }
            await axios
                .post("api/register", form)
                .then((response) => {
                    console.log(response);
                    if (response.data.success) {
                        // store.dispatch('setToken', response.data.data.token);
                        // router.push({ name: "Dashboard" });
                        process.verificationEmail = true;
                    }
                })
                .catch((e) => {
                    errors.value = e.response.data.message;
                });
        };
        const sendOtp = async () => {
            if (!form.isOtpVerified && form.email) {
                await axios
                    .post("api/send-otp-mail", form)
                    .then((response) => {
                        console.log(response);
                        if (response.data.success) {
                            form.isOtpSend = 1;
                            form.otp = response.data.data.otp;
                        } else {
                            errors.value = [response.data.message];
                        }
                    })
                    .catch((e) => {
                        console.log("in exception catch");
                        if (typeof e.response.data.message === "object")
                            errors.value = e.response.data.message;
                        errors.value.push(e.response.data.message);
                    });
            }
        };
        const verifyOtpValue = () => {
            if ( form.userInputOtp.length == 4 ) {
                if(form.userInputOtp == form.otp){
                    console.log("verified.");
                    form.isOtpVerified = 1;
                } else {
                    console.log("not verified.");
                    form.isOtpVerified = 0;
                }
            }
        };

        return {
            form,
            register,
            errors,
            sendOtp,
            verifyOtpValue,
            process,
        };
    },
};
</script>
