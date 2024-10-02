<template>
    <div class="py-6">
        <div class="flex bg-white rounded-lg shadow-lg overflow-hidden mx-auto max-w-sm lg:max-w-4xl">
            <div class="hidden lg:block lg:w-1/2 bg-cover" style="
          background-image: url('https://images.ctfassets.net/lzny33ho1g45/O6Ns6DttUzJym7rhGiD36/9affffb4ec5c115b8f742cd34b663cff/best_to_do_apps.jpg');
        "></div>
            <div class="w-full p-8 lg:w-1/2">
                <h2 class="text-2xl font-semibold text-gray-700 text-center">
                    The Weather App
                </h2>

                <div class="mt-4 flex items-center justify-between">
                    <span class="border-b w-1/5 lg:w-1/4"></span>
                    <a href="#" class="text-xs text-center text-gray-500 uppercase">or login with email</a>
                    <span class="border-b w-1/5 lg:w-1/4"></span>
                </div>
                <div class="mt-4">
                    <div class="flex flex-col justify-start text-left">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                        <input
                            class="bg-gray-200 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none"
                            type="email" id="email" name="email" v-model="form.email" placeholder="email@example.com" />
                        <span class="text-red-500" v-if="this.validationErrors.email">{{ this.validationErrors.email
                            }}</span>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex justify-between">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                        <a href="#" class="text-xs text-gray-500">Forget Password?</a>
                    </div>
                    <input
                        class="bg-gray-200 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none"
                        type="password" id="password" name="password" v-model="form.password" />
                    <span class="text-red-500" v-if="this.validationErrors.password">{{ this.validationErrors.password
                        }}</span>
                </div>
                <div class="mt-8">
                    <button class="bg-gray-700 text-white font-bold py-2 px-4 w-full rounded hover:bg-gray-600"
                        @click="login" v-if="!loading">
                        Login
                    </button>
                    <Loader v-if="loading" />
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <span class="border-b w-1/5 md:w-1/4"></span>
                    <router-link to="/register" class="text-xs text-gray-500 uppercase">or sign up</router-link>
                    <span class="border-b w-1/5 md:w-1/4"></span>
                </div>
            </div>
        </div>
        <notification-pop-up v-if="showNotification" />
    </div>
</template>

<script>
import Loader from '@/components/Loader.vue';
import NotificationPopUp from '@/components/notifications/NotificationPopUp.vue';
import ValidationForm from '@/mixins/ValidationForm';

export default {
    name: "LoginView",
    mixins: [ValidationForm],
    components: {
        Loader,
        NotificationPopUp,
    },
    data() {
        return {
            form: {
                email: "",
                password: "",
            },
            validationErrors: {},
            loading: false,
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
        async login(event) {
            this.loading = true;
            if (this.validateForm()) {
                await this.$axios
                    .post("v1/login", this.form)
                    .then(({ data }) => {
                        if (data.access_token) {
                            this.validationErrors = {};
                            console.log(data);
                            this.signIn(data);
                        } else {
                            this.validationErrors = this.convertErrorFromArray(data);
                        }
                    })
                    .catch((error) => {
                        if (error.response && error.response.data.errors) {
                            this.validationErrors = this.convertErrorFromArray(error);
                        }
                    })
            }
            this.loading = false;
            return;
        },

        signIn(data) {
            localStorage.setItem("access_token", data.access_token);
            this.$router.push({ name: "home" });
        },
    },


};
</script>

<style></style>
