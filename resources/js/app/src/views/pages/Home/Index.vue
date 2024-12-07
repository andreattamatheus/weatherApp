<script setup>
import { ref, onMounted } from 'vue';
import Spinner from '@/views/components/Spinner.vue';
import IconLocation from '@/views/components/icons/IconLocation.vue';
import ListFilter from '@/views/components/ListFilter.vue';
import ForecastCard from '@/views/pages/Home/WeatherForecast/ForecastCard.vue';
import SearchBar from '@/views/pages/Home/WeatherForecast/SearchBar.vue';
import LocationCard from './WeatherForecast/LocationCard.vue';
import {
    getLocations,
    deleteLocation,
} from "@/views/pages/Home/WeatherForecast/useLocationController";
import { useWeatherDataStore } from "@/store/WeatherData";

const weatherDataStore = useWeatherDataStore();
const isLoading = ref(false);
const isLoadingForecast = ref(false);
const userLocations = ref([]);

const fetchUserData = async () => {
    try {
        isLoadingForecast.value = true;
        userLocations.value = await getLocations();
    } catch (error) {
        console.log(error);
    } finally {
        isLoadingForecast.value = false;
    }
};

const deleteUserLocation = async (locationId, date) => {
    try {
        isLoading.value = true;
        await deleteLocation(locationId, date);
        await fetchUserData();
    } catch (error) {
        console.log(error);
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchUserData();
});
</script>

<template>
    <div class="flex">
        <div class="w-full flex flex-col items-start bg-background-primary " ref="formContainer">
            <div class="toolbar w-full px-1 py-0 bg-gray-100">
                <div class="w-[1280px] mx-auto lg:w-3/4 flex justify-between items-center ">
                    <div class="flex align-center items-center">
                        <img src="../../../assets/logo-weather-app.png" width="100px" alt="Logo">
                        <h1 class="font-semibold text-lg">WEATHER APP</h1>
                    </div>
                    <div>
                        <button
                            class="text-gray-700 hover:text-gray-800 focus:ring-2 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:text-gray-300 dark:hover:text-gray-400 dark:focus:ring-gray-600">
                            <i class="fas fa-expand-alt"></i>
                        </button>
                    </div>

                </div>
            </div>
            <div class="max-w-[1280px] w-ful h-full mx-auto p-8 ">
                <div class="flex lg:flex-row flex-col space-x-0 lg:space-x-4 items-center lg:items-start ">
                    <SearchBar />
                    <div v-if="weatherDataStore.weatherData && Object.keys(weatherDataStore.weatherData).length" class="w-full lg:w-1/2 border-solid border-2 border-gray-200 rounded">
                        <ForecastCard :isLoading="isLoading" />
                    </div>
                </div>

                <div class="flex-1 mt-10">
                    <div class="flex items-center gap-4 md:pt-6 mb-4">
                        <div
                            class="grid size-9 place-items-center rounded-lg border border-border-muted bg-secondary bg-[radial-gradient(65%_65%_at_50%_35%,#20282D_0%,#0D1113_100%)] md:size-10">
                            <IconLocation class="w-6 h-6 text-white" />
                        </div>
                        <h2 class="text-xl font-semibold tracking-tighter md:text-2xl lg:text-3xl">User locations
                        </h2>
                    </div>

                    <ListFilter />
                    <Spinner class="w-full max-w-screen-sm" v-if="isLoadingForecast" />
                    <div v-else class="forecast-list">
                        <div class="next flex pb-2 flex-col relative cursor-pointer rounded-xl border border-border-muted"
                            v-for="(location) in userLocations" :key="location.id">
                            <LocationCard :location="location" @delete="deleteUserLocation"/>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>


<style>
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
    width: 1280px;
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
