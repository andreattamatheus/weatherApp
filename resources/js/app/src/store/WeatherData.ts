import { defineStore } from "pinia";

export const useWeatherDataStore = defineStore("weatherData", {
    state: () => ({
        weatherData: [],
    }),
    actions: {
        setWeatherData(forecast: any) {
            this.weatherData = forecast;
        },
    },
});
