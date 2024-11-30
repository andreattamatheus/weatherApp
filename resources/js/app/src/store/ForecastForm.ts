import { defineStore } from "pinia";

export const useForecastFormStore = defineStore("forecastForm", {
    state: () => ({
        state: "",
        city: "",
    }),
    actions: {
        setState(newState: string) {
            this.state = newState;
        },
        setCity(newCity: string) {
            this.city = newCity;
        },
    },
});
