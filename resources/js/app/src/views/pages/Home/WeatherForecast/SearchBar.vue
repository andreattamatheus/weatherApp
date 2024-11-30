<template>
    <div class="flex w-full flex-col gap-4 lg:w-1/2">
        <div class="mr-auto">
            <h2 class="font-display my-6 text-2xl font-semibold tracking-tighter">
                Discover the weather:
            </h2>
        </div>
        <div class="flex w-full flex-col justify-start text-left">
            <InputTextField fieldLabel="City" fieldName="city" v-model="city" id="city"
                placeholder="Enter the city name" :validationErrors="validationErrors.city" required />
        </div>
        <div class="flex w-full flex-col justify-start text-left">
            <InputSelectField fieldLabel="Country" fieldName="country" v-model="country" id="country-select"
                placeholder="Select a country" :options="countryList" :validationErrors="validationErrors.country"
                required />
        </div>

        <button
            class="focus-visible:ring-offset-background focus-visible:ring-ring text-sky-700-foreground hover:bg-brand-seagull-500 active:bg-brand-seagull-500 relative mt-2 inline-flex min-h-10 items-center justify-center overflow-hidden whitespace-nowrap rounded-full border border-transparent bg-sky-700 px-4 py-1.5 text-base font-medium text-white transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
            @click="getForecastByCityAndState">
            <span class="inline-flex items-center justify-center gap-2" style="
                    opacity: 1;
                    filter: blur(0px);
                    will-change: opacity, transform, filter;
                    transform: none;
                ">Search</span>
        </button>
    </div>
</template>

<script>
import InputTextField from "@/views/components/InputTextField.vue";
import InputSelectField from "@/views/components/InputSelectField.vue";
import { getForecastByCityAndState, getLocations } from "@/views/pages/Home/WeatherForecast/useLocationController";
import { getCountries } from "@/views/pages/Home/WeatherForecast/useCountryController";

export default {
    components: { InputTextField, InputSelectField },
    props: {},
    data() {
        return {
            city: "",
            country: "",
            countryList: [],
            validationErrors: {
                city: "",
                country: "",
            },
        };
    },
    watch: {},
    computed: {},
    methods: {
        async getForecastByCityAndState() {
            console.log("getForecastByCityAndState");
            try {
                this.isLoading = true;
                const weatherData = await getForecastByCityAndState(
                    { city: this.city, state: this.country }
                );
                this.$emit("getForecast", weatherData);
            } catch (error) {
                console.log(error);
            } finally {
                this.isLoading = false;
            }
        },

        async getCountryList() {
            try {
                this.isLoading = true;
                const response = await getCountries();
                this.countryList = response;
            } catch (error) {
                console.log(error);
            } finally {
                this.isLoading = false;
            }
        },
        setValue(value) {
            this.country = value
        },
    },
    created() { },
    mounted() {
        this.getCountryList();
    },
};
</script>

<style></style>
