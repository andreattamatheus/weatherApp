// axios.ts

import axios from "axios";
import type { App } from "vue";

interface AxiosOptions {
    baseUrl?: string;
}

const userToken = localStorage.getItem("access_token");

export default {
    install: (app: App, options: AxiosOptions) => {
        app.config.globalProperties.$axios = axios.create({
            baseURL: options.baseUrl,
            withCredentials: true,
            headers: {
                "Content-Type": "application/json",
                Authorization: userToken ? `Bearer ${userToken}` : "",
            },
        });
    },
};
