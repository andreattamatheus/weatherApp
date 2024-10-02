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
                        <h2>{{ this.city }},
                            {{ this.state }}</h2>
                            <p class="time">{{ weatherData.date }}</p>

                        <div class="flex flex-col justify-center align-center text-center">
                            <img :src="iconUrl" alt="Weather Icon" class="weather-icon" />
                            <p class="temp-max">{{ weatherData.max_temperature }} °C</p>
                            <p class="temp-min">{{ weatherData.min_temperature }} °C</p>
                        </div>
                        <span class="clouds">{{ weatherData.condition }}</span>
                    </div>
                </main>

                <div class="forecast">
                    <div class="cast-header">Locations forecast</div>
                    <div class="forecast-list">
                        <div class="next" v-for="(location, index) in userLocations" :key="index">
                            <div>
                                <p class="time">{{ location.date }}</p>
                                <p class="temp-max">{{ location.max_temperature }} °C</p>
                                <p class="temp-min">{{ location.min_temperature }} °C</p>
                            </div>
                            <p class="desc">{{ location.city }}</p>
                            <div class="flex row justify-center align-center">

                                <p class="desc">{{ location.condition }}</p>

                            </div>
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
                const response = await this.$axios.get('v1/users/locations', {
                    headers: {
                        Authorization: `Bearer ${this.apiKey}`,
                    },
                });
                this.getUserLocations(response.data.data);
            } catch (error) {
                console.error("Error fetching weather data:", error);
            }
        },

        getUserLocations(locations) {
            this.userLocations = [];
            locations.forEach(location => {
                location.forecasts.forEach(forecast => {
                    this.userLocations.push({
                        city: location.city,
                        date: forecast.date,
                        min_temperature: forecast.min_temperature,
                        max_temperature: forecast.max_temperature,
                        condition: forecast.condition
                    });
                });
            });
        },

        async getForecastByCityAndState() {
            try {
                const response = await this.$axios.get('v1/get-location-forecast', {
                    headers: {
                        Authorization: `Bearer ${this.apiKey}`,
                    },
                    params: {
                        city: this.city,
                        state: this.state,
                    },
                });
                if (!response.success) {
                    this.$toast.error(response.data[0]);
                    return;
                }
                this.weatherData = response.data;
                console.log(response);
            } catch (error) {
                console.error("Error fetching forecast data:", error);
            }
        },



        async fetchCurrentLocationWeather() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(async (position) => {
                    const { latitude, longitude } = position.coords;
                    const url = `ht
tp://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=${this.apikey}`;
                    await this.fetchWeatherData(url);
                });
            }
        },
        async fetchWeatherData(url) {
            try {
                const response = await this.$axios.get(url);
                this.weatherData = response.data;
                // Fetch forecast data
                await this.fetchForecast(this.weatherData.name);
            } catch (error) {
                console.error("Error fetching weather data:", error);
            }
        },
        async fetchForecast(city) {
            const urlcast =
                `http://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${this.apikey}`;
            try {
                const response = await this.$axios.get(urlcast);
                const forecast = response.data;
                this.dayForecast(forecast);
            } catch (error) {
                console.error("Error fetching forecast data:", error);
            }
        },
        async searchByCity() {
            if (!this.city) return;
            try {
                const urlsearch =
                    `http://api.openweathermap.org/data/2.5/weather?q=${this.city}&appid=${this.apikey}`;
                const response = await this.$axios.get(urlsearch);
                this.weatherData = response.data;
                // Fetch forecast data
                await this.fetchForecast(this.weatherData.name);
            } catch (error) {
                console.error("Error fetching weather data:", error);
            }
            this.city = "";
        }
    },
    computed: {
        temperature() {
            return this.weatherData
                ? Math.floor(this.weatherData.main.temp - 273)
                : null;
        },
        iconUrl() {
            return null;
        },
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
