import axios from "axios";

interface AxiosOptions {
    baseUrl?: string;
}

const options: AxiosOptions = {
    baseUrl: process.env.VUE_APP_URL,
};

export const httpClient = axios.create({
    baseURL: options.baseUrl,
    withCredentials: true,
    headers: {
        "Content-Type": "application/json",
    },
});

httpClient.interceptors.request.use(async (config) => {
    const accessToken = localStorage.getItem("access_token");

    if (accessToken) {
        config.headers.Authorization = `Bearer ${accessToken}`;
    }
    return config;
});
