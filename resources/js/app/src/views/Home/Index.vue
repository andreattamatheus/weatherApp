<template>
    <div class="flex">
        <div class="w-full flex items-start justify-left bg-background-primary font-sans vl-parent" ref="formContainer">
            <loading :active="isLoading" :is-full-page="fullPage" />
            <div class="container">
                <div class="header">
                    <h1>WEATHER APP</h1>
                    <div class="search-bar">
                        <input type="text" v-model="city" placeholder="Enter city name" class="search-input" />
                        <input type="text" v-model="state" placeholder="Enter city State" class="search-input" />
                        <button @click="getForecastByCityAndState" class="search-button">Search</button>
                    </div>
                </div>

                <main class="main-section">
                    <div v-if="weatherData" class="weather">
                        <h2>{{ weatherData.city }},
                            {{ weatherData.state }}</h2>
                            <p class="time">{{ weatherData.date }}</p>
                        <div class="flex flex-col justify-center align-center text-center">
                            <p class="temp-max">{{ weatherData.max_temperature }} °C</p>
                        </div>
                        <div class="flex justify-center items-center ">
                            <img :src="getIcon(weatherData.icon)" alt="Weather Icon"  />
                            <p class="desc">{{ weatherData.condition }}</p>
                        </div>
                        <button @click="saveUserLocation" class="search-button mt-4">Save</button>
                    </div>
                </main>

                <div class="forecast">
                    <div class="cast-header">Locations forecast</div>
                    <div class="forecast-list">
                        <div class="next" v-for="(location) in userLocations" :key="location.id">
                            <p class="desc mb-4">{{ location.city }} ({{ location.date }})</p>
                            <div class="flex  justify-center align-center">
                                <p class="text-red mr-1">{{ location.max_temperature }} ° / </p>
                                <p class="text-blue"> {{ location.min_temperature }} °</p>
                            </div>
                            <div class="flex justify-center items-center ">
                                <img :src="getIcon(location.icon)" alt="Weather Icon"  />
                                <p class="desc">{{ location.condition }}</p>
                            </div>
                            <button @click="deleteUserLocation(location.id, location.date)" class="search-button">Delete</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import HandleUserData from '@/mixins/HandleUserData';

export default {
    name: "Home",
    components: {
        Loading,
    },
    mixins: [HandleUserData],
    data() {
        return {
            isLoading: false,
            fullPage: false,
            apikey: process.env.VUE_APP_API_KEY_WEATHER_APP,
            apiKey: localStorage.getItem('access_token'),
            city: "",
            state: "",
            weatherData: null,
            userLocations: [],
        };
    },
    mounted() {
        this.fetchUserData();
    },
    methods: {
        async fetchUserData() {
            try {
                this.isLoading = true;
                const response = await this.$axios.get('v1/users/locations', {
                    headers: {
                        Authorization: `Bearer ${this.apiKey}`,
                    },
                });
                if (!response.data.success) {
                    this.$toast.error(response.data[0]);
                    this.isLoading = false;
                    return;
                }
                await this.getUserLocations(response.data.data);
                this.isLoading = false;
            } catch (error) {
                this.isLoading = false;
                console.error("Error fetching weather data:", error);
            }
        },

        async getUserLocations(locations) {
            this.userLocations = [];
            locations.forEach(location => {
                location.forecasts.forEach(forecast => {
                    this.userLocations.push({
                        id: location.id,
                        city: location.city,
                        date: forecast.date,
                        min_temperature: forecast.min_temperature,
                        max_temperature: forecast.max_temperature,
                        condition: forecast.condition,
                        icon: forecast.icon,
                    });
                });
            });
        },

        async getForecastByCityAndState() {
            try {
                this.isLoading = true;
                const response = await this.$axios.get('v1/get-location-forecast', {
                    headers: {
                        Authorization: `Bearer ${this.apiKey}`,
                    },
                    params: {
                        city: this.city,
                        state: this.state,
                    },
                });
                if (!response.data.success) {
                    this.$toast.error(response.data[0]);
                    this.isLoading = false;
                    return;
                }
                this.isLoading = false;
                this.weatherData = response.data.data;
            } catch (error) {
                console.error("Error fetching forecast data:", error);
            }
        },

        async saveUserLocation() {
            this.isLoading = true;

            try {
                const response = await this.$axios.post('v1/users/locations', {
                    city: this.city,
                    state: this.state,
                    weatherData: this.weatherData,
                }, {
                    headers: {
                        Authorization: `Bearer ${this.apiKey}`,
                    },
                })
                if (!response.data.success) {
                    this.isLoading = false;
                    this.$toast.error(response.data[0]);
                    return;
                }
                await this.fetchUserData();
                this.isLoading = false;
            } catch (error) {
                this.isLoading = false;
                console.error("Error saving user location:", error);
            }
        },

        async deleteUserLocation(locationId, date) {
            this.isLoading = true;
            try {
                const response = await this.$axios.delete(`v1/users/locations/${locationId}/${date}`, {
                    headers: {
                        Authorization: `Bearer ${this.apiKey}`,
                    },
                });
                if (!response.data.success) {
                    this.isLoading = false;
                    this.$toast.error(response.data[0]);
                    return;
                }
                this.isLoading = false;
                this.userLocations = this.userLocations.filter(location => location.id !== locationId);
            } catch (error) {
                this.isLoading = false;
                console.error("Error deleting user location:", error);
            }
        },
        getIcon(icon) {
            return `http://openweathermap.org/img/wn/${icon}@2x.png`;
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

.container {
    max-width: 1200px;
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
