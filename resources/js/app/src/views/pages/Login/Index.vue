<template>
    <div class="min-h-screen flex justify-center items-start md:items-center p-8">
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm w-full max-w-sm  p-8 ">
            <div class="flex flex-col space-y-1.5 p-6">
                <h3 class="font-semibold tracking-tight text-2xl">The Weather App</h3>
            </div>
            <div class="mt-4">
                <div class="flex flex-col justify-start text-left">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Email Address</label>
                    <input
                        class="bg-gray-200 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none"
                        type="email" id="email" name="email" v-model="form.email" placeholder="email@example.com" />
                    <span class="text-red-500 transition-all" v-if="this.validationErrors.email">
                        {{ this.validationErrors.email }}
                    </span>
                </div>
            </div>

            <div class="mt-4">
                <div class="flex flex-col justify-start text-left">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                    <input
                        class="bg-gray-200 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none"
                        type="password" id="password" name="password" v-model="form.password" />
                    <span class="text-red-500 transition-all" v-if="this.validationErrors.password">
                        <i class="text-red-500 fa-solid fa-circle-exclamation"></i>
                        {{ this.validationErrors.password }}
                    </span>
                </div>
            </div>
            <div class="mt-8">
                <button class="bg-gray-700 text-white font-bold py-2 px-4 w-full rounded hover:bg-gray-600"
                    @click="login" v-if="!isLoading">
                    Login
                </button>
            </div>
        </div>
        <notification-pop-up v-if="showNotification" />
    </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import NotificationPopUp from '@/views/components/notifications/NotificationPopUp.vue';
import ValidationForm from '@/mixins/ValidationForm';
import { login } from "./useAuthController";

export default {
    name: "LoginView",
    mixins: [ValidationForm],
    components: {
        Loading,
        NotificationPopUp,
    },
    data() {
        return {
            form: {
                email: "",
                password: "",
            },
            validationErrors: {},
            isLoading: false,
            message: '',
            showNotification: false,
        };
    },
    mounted() {
        if (this.$route.query.message) {
            this.showNotification = true;
        }

        setTimeout(() => {
            this.showNotification = false;
        }, 5000);
    },

    methods: {

        async login() {
            try {
                this.isLoading = true;
                if (this.validateForm()) {
                    const response = await login(this.form);

                    if (response.statusCode !== 200) {
                        this.validationErrors = await this.convertErrorFromArray(response.body.errors);
                        return;
                    }
                    this.validationErrors = {};
                    this.$router.push({ name: "home" });
                }
            } catch (error) {
                console.log(error);
            } finally {
                this.isLoading = false;
            }
        },

    },


};
</script>

<style></style>
