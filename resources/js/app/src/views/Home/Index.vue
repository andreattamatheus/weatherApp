<template>
    <div class="flex">
        <div class="w-full flex items-startbg-background-primary font-sans vl-parent" ref="formContainer">
            <div class="container-app">
                <div class="flex justify-between items-center">
                    <div
                        class="search-bar flex flex-col space-y-4 sm:space-y-4 p-8 border-solid border-2 border-gray-200 rounded">
                        <div class="header flex justify-center">
                            <h1>WEATHER APP</h1>
                        </div>
                        <div class="flex flex-col justify-start text-left w-full">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                for="grid-first-name">
                                City
                            </label>
                            <input type="text" v-model="city" id="city"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-100 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Enter the city name" required />
                            <span class="text-sm text-red-500 mt-1 ml-1" v-if="this.validationErrors.city">{{
                                this.validationErrors.city
                                }}</span>
                        </div>
                        <div class="flex flex-col justify-start text-left w-full ">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                for="grid-first-name">
                                Country
                            </label>
                            <input type="text" v-model="state" id="state"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-100 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Enter the city country" required />
                            <span class="text-sm text-red-500 mt-1 ml-1" v-if="this.validationErrors.state">{{
                                this.validationErrors.state
                            }}</span>
                        </div>
                        <div class="flex flex-col justify-start text-left w-full ">
                            <button @click="getForecastByCityAndState"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
                        </div>
                    </div>

                    <main v-if="weatherData" class="border-solid border-2 border-gray-200 rounded">
                        <div class="flex flex-col items-center justify-center text-gray-700 p-10 bg-gradient-to-br ">
                            <spinner class="w-full max-w-screen-sm" v-if="isLoading" />
                            <div v-else
                                class="w-full max-w-screen-sm bg-white p-10 rounded-xl ring-8 ring-white ring-opacity-40">
                                <div class="flex justify-between">
                                    <div class="flex flex-col">
                                        <span class="text-6xl font-bold">{{ weatherData?.max_temperature }} °C</span>
                                        <span class="font-semibold mt-1 text-gray-500">{{ weatherData?.city }}, {{
                                            weatherData?.state }}</span>
                                        <span class="font-semibold mt-1 text-gray-500">{{ weatherData?.condition }}</span>
                                    </div>
                                    <svg class="h-24 w-24 fill-current text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                                        height="24" viewBox="0 0 24 24" width="24">
                                        <path d="M0 0h24v24H0V0z" fill="none" />
                                        <path
                                            d="M6.76 4.84l-1.8-1.79-1.41 1.41 1.79 1.79zM1 10.5h3v2H1zM11 .55h2V3.5h-2zm8.04 2.495l1.408 1.407-1.79 1.79-1.407-1.408zm-1.8 15.115l1.79 1.8 1.41-1.41-1.8-1.79zM20 10.5h3v2h-3zm-8-5c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6zm0 10c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm-1 4h2v2.95h-2zm-7.45-.96l1.41 1.41 1.79-1.8-1.41-1.41z" />
                                    </svg>
                                </div>
                                <div class="flex justify-between mt-12">
                                    <div class="flex flex-col items-center">
                                        <span class="font-semibold text-lg">31°C</span>
                                        <svg class="h-10 w-10 fill-current text-gray-400 mt-3"
                                            xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                                            <path d="M0 0h24v24H0V0z" fill="none" />
                                            <path
                                                d="M6.76 4.84l-1.8-1.79-1.41 1.41 1.79 1.79zM1 10.5h3v2H1zM11 .55h2V3.5h-2zm8.04 2.495l1.408 1.407-1.79 1.79-1.407-1.408zm-1.8 15.115l1.79 1.8 1.41-1.41-1.8-1.79zM20 10.5h3v2h-3zm-8-5c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6zm0 10c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm-1 4h2v2.95h-2zm-7.45-.96l1.41 1.41 1.79-1.8-1.41-1.41z" />
                                        </svg>
                                        <span class="font-semibold mt-1 text-sm">1:00</span>
                                        <span class="text-xs font-semibold text-gray-400">PM</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="font-semibold text-lg">32°C</span>
                                        <svg class="h-10 w-10 fill-current text-gray-400 mt-3"
                                            xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                                            <path d="M0 0h24v24H0V0z" fill="none" />
                                            <path
                                                d="M12.01 6c2.61 0 4.89 1.86 5.4 4.43l.3 1.5 1.52.11c1.56.11 2.78 1.41 2.78 2.96 0 1.65-1.35 3-3 3h-13c-2.21 0-4-1.79-4-4 0-2.05 1.53-3.76 3.56-3.97l1.07-.11.5-.95C8.08 7.14 9.95 6 12.01 6m0-2C9.12 4 6.6 5.64 5.35 8.04 2.35 8.36.01 10.91.01 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.64-4.96C18.68 6.59 15.65 4 12.01 4z" />
                                        </svg>
                                        <span class="font-semibold mt-1 text-sm">3:00</span>
                                        <span class="text-xs font-semibold text-gray-400">PM</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="font-semibold text-lg">31°C</span>
                                        <svg class="h-10 w-10 fill-current text-gray-400 mt-3"
                                            xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                                            <path d="M0 0h24v24H0V0z" fill="none" />
                                            <path
                                                d="M12.01 6c2.61 0 4.89 1.86 5.4 4.43l.3 1.5 1.52.11c1.56.11 2.78 1.41 2.78 2.96 0 1.65-1.35 3-3 3h-13c-2.21 0-4-1.79-4-4 0-2.05 1.53-3.76 3.56-3.97l1.07-.11.5-.95C8.08 7.14 9.95 6 12.01 6m0-2C9.12 4 6.6 5.64 5.35 8.04 2.35 8.36.01 10.91.01 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.64-4.96C18.68 6.59 15.65 4 12.01 4z" />
                                        </svg>
                                        <span class="font-semibold mt-1 text-sm">5:00</span>
                                        <span class="text-xs font-semibold text-gray-400">PM</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="font-semibold text-lg">27°C</span>
                                        <svg class="h-10 w-10 fill-current text-gray-400 mt-3"
                                            xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24"
                                            viewBox="0 0 24 24" width="24">
                                            <g>
                                                <rect fill="none" height="24" width="24" />
                                            </g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M19.78,17.51c-2.47,0-6.57-1.33-8.68-5.43C8.77,7.57,10.6,3.6,11.63,2.01C6.27,2.2,1.98,6.59,1.98,12 c0,0.14,0.02,0.28,0.02,0.42C2.61,12.16,3.28,12,3.98,12c0,0,0,0,0,0c0-3.09,1.73-5.77,4.3-7.1C7.78,7.09,7.74,9.94,9.32,13 c1.57,3.04,4.18,4.95,6.8,5.86c-1.23,0.74-2.65,1.15-4.13,1.15c-0.5,0-1-0.05-1.48-0.14c-0.37,0.7-0.94,1.27-1.64,1.64 c0.98,0.32,2.03,0.5,3.11,0.5c3.5,0,6.58-1.8,8.37-4.52C20.18,17.5,19.98,17.51,19.78,17.51z" />
                                                    <path
                                                        d="M7,16l-0.18,0C6.4,14.84,5.3,14,4,14c-1.66,0-3,1.34-3,3s1.34,3,3,3c0.62,0,2.49,0,3,0c1.1,0,2-0.9,2-2 C9,16.9,8.1,16,7,16z" />
                                                </g>
                                            </g>
                                        </svg>
                                        <span class="font-semibold mt-1 text-sm">7:00</span>
                                        <span class="text-xs font-semibold text-gray-400">PM</span>
                                    </div>
                                </div>
                                <button @click="saveUserLocation"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                            </div>

                        </div>

                    </main>
                </div>


                <div class="forecast mt-10">
                    <div class="cast-header bg-gray-700 hover:bg-gray-800 text-white">User locations</div>
                    <spinner v-if="isLoadingForecast" />
                    <div v-else class="forecast-list">
                        <div class="next flex flex-col forecast-card" v-for="(location) in userLocations"
                            :key="location.id">
                            <p class="desc mb-4">{{ location.city }} ({{ location.date }})</p>
                            <div class="flex justify-center align-center">
                                <p class="text-red mr-1">{{ location.max_temperature }} ° / </p>
                                <p class="text-blue"> {{ location.min_temperature }} °</p>
                            </div>
                            <div class="flex justify-center items-center ">
                                <img :src="getIcon(location.icon)" alt="Weather Icon" />
                                <p class="desc">{{ location.condition }}</p>
                            </div>
                            <button @click="deleteUserLocation(location.id, location.date)"
                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Delete</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import Spinner from '@/components/Spinner.vue';
import { LocationController } from "../../controllers/LocationController";

export default {
    name: "Home",
    components: {
        Loading,
        Spinner,
    },
    data() {
        return {
            isLoading: false,
            isLoadingForecast: false,
            fullPage: false,
            imageUrl: process.env.VUE_APP_IMAGE_WEATHER_URL,
            city: "",
            state: "",
            weatherData: null,
            userLocations: [],
            validationErrors: {},
        };
    },
    mounted() {
        this.fetchUserData();
    },
    methods: {
        async fetchUserData() {
            try {
                this.isLoadingForecast = true;
                const locationController = new LocationController(this.$axios);
                this.userLocations = await locationController.get();
            } catch (error) {
                this.$toast.error("Error fetching locations");
            } finally {
                this.isLoadingForecast = false;
            }
        },

        async getForecastByCityAndState() {
            try {
                this.isLoading = true;
                const locationController = new LocationController(this.$axios);
                this.weatherData = await locationController.getForecastByCityAndState(this.city, this.state);
            } catch (error) {
                this.$toast.error("Error fetching locations");
            } finally {
                this.isLoading = false;
            }
        },

        async saveUserLocation() {
            try {
                this.isLoading = true;
                const locationController = new LocationController(this.$axios);
                this.weatherData = await locationController.save(this.city, this.state, this.weatherData);
                await this.fetchUserData();
            } catch (error) {
                this.$toast.error("Error saving user location");
            } finally {
                this.isLoading = false;
            }
        },

        async deleteUserLocation(locationId, date) {
            try {
                this.isLoading = true;
                const locationController = new LocationController(this.$axios);
                this.weatherData = await locationController.delete(locationId, date);
                await this.fetchUserData();
            } catch (error) {
                this.$toast.error("Error saving user location");
            } finally {
                this.isLoading = false;
            }
        },

        getIcon(icon) {
            return this.imageUrl + `/${icon}@2x.png`;
        }

    },
};
</script>

<style>
/* styles.css */
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@300;700&display=swap');

/* General Styles */
html,
body {
    height: 100%;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Roboto', sans-serif;
    background-color: #f8fafa;
    color: #333;
}

.container-app {
    width: 1000px;
    margin: 0 auto;
    padding: 2rem;
}

/* Header */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.header h1 {
    font-family: 'Orbitron', sans-serif;
    font-size: 2.5rem;
    color: #2b6cb0;
    margin: 0;
}

/* Search Bar */
.search-bar {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;
}

.search-input {
    font-size: 1rem;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 0.25rem;
    background-color: #f5f5f5;
    width: 60%;
    margin-right: 1rem;
}

.search-button {
    font-size: 1rem;
    background-color: #4a90e2;
    color: white;
    border: none;
    border-radius: 0.25rem;
    padding: 0.5rem 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-button:hover {
    background-color: #357dbf;
}

/* Weather Widget */
.weather {
    text-align: center;
    color: #fff;
    margin-bottom: 3rem;
    padding: 2rem;
    background-color: #4caf50;
    border-radius: 1rem;
    box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.1);
}

.weather h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.weather-icon {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background-color: rgba(240, 248, 255, 0.5);
}

.temperature {
    font-size: 3rem;
    margin-top: 1rem;
}

.clouds {
    display: inline-block;
    background-color: #f5f5f5;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 1.2rem;
    color: #4caf50;
}

/* Forecast */
.forecast {
    margin-bottom: 3rem;
}

.cast-header {
    font-size: 1.5rem;
    color: #333;
    background-color: #ffc107;
    padding: 0.5rem 1rem;
    border-radius: 1rem;
    margin-bottom: 1rem;
}

.divider {
    height: 10px;
    background-color: #ffc107;
    border-radius: 1rem;
    margin-bottom: 2rem;
}

.forecast-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.next {
    width: calc(50% - 1rem);
    padding: 1.5rem;
    border-radius: 1rem;
    background-color: #fff;
    box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.1);
    margin-bottom: 1rem;
    transition: transform 0.3s ease;
}

.next:hover {
    transform: translateY(-5px);
}

.time {
    font-size: 1.2rem;
    color: #4a90e2;
    margin-bottom: 0.5rem;
}

.temp-max,
.temp-min {
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
}

.desc {
    color: #555;
}

/* Responsive Styles */
@media screen and (max-width: 768px) {
    .header {
        flex-direction: column;
        align-items: stretch;
    }

    .search-bar {
        flex-direction: column;
    }

    .search-input,
    .search-button {
        width: 100%;
        border-radius: 0.5rem;
    }

    .search-input {
        margin-right: 0;
        margin-bottom: 1rem;
    }

    .weather-icon {
        width: 100px;
        height: 100px;
    }

    .temperature {
        font-size: 2.5rem;
    }

    .cast-header {
        font-size: 1.2rem;
    }

    .divider {
        height: 7px;
    }

    .next {
        width: 100%;
    }
}
</style>
