<template>
    <div class="flex w-full flex-col gap-4 lg:w-1/2">
        <div class="mr-auto">
            <h2
                class="font-display my-6 text-2xl font-semibold tracking-tighter"
            >
                Discover the weather:
            </h2>
        </div>
        <div class="flex w-full flex-col justify-start text-left">
            <InputTextField
                fieldLabel="City"
                v-model="city"
                id="city"
                placeholder="Enter the city name"
                :validationErrors="validationErrors.city"
                required
            />
        </div>
        <div class="flex w-full flex-col justify-start text-left">
            <InputSelectField
                fieldLabel="Country"
                v-model="state"
                id="country-select"
                placeholder="Select a country"
                :options="countryList"
                :validationErrors="validationErrors.state"
                required
            />
        </div>

        <button
            class="focus-visible:ring-offset-background focus-visible:ring-ring text-sky-700-foreground hover:bg-brand-seagull-500 active:bg-brand-seagull-500 relative mt-2 inline-flex min-h-10 items-center justify-center overflow-hidden whitespace-nowrap rounded-full border border-transparent bg-sky-700 px-4 py-1.5 text-base font-medium text-white transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
            @click="getForecastByCityAndState"
        >
            <span
                class="inline-flex items-center justify-center gap-2"
                style="
                    opacity: 1;
                    filter: blur(0px);
                    will-change: opacity, transform, filter;
                    transform: none;
                "
                >Search</span
            >
        </button>
    </div>
</template>

<script>
import InputTextField from "@/components/InputTextField.vue";
import { LocationController } from "@/controllers/LocationController";
import { CountryController } from "@/controllers/CountryController";
import InputSelectField from "@/components/InputSelectField.vue";

export default {
    components: { InputTextField, InputSelectField },
    props: {},
    data() {
        return {
            city: "",
            state: "",
            countryList: [],
            validationErrors: {
                city: "",
                state: "",
            },
        };
    },
    watch: {},
    computed: {},
    methods: {
        async getForecastByCityAndState() {
            console.log("getForecastByCityAndState");
            console.log(this.city, this.state);

            try {
                this.isLoading = true;
                const locationController = new LocationController();
                const weatherData =
                    await locationController.getForecastByCityAndState(
                        this.city,
                        this.state["name"],
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
                const countryController = new CountryController();
                const response = await countryController.get();
                this.countryList = response.data;
            } catch (error) {
                console.log(error);
            } finally {
                this.isLoading = false;
            }
        },
    },
    created() {},
    mounted() {
        this.getCountryList();
    },
};
</script>

<style></style>
