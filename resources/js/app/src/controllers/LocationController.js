export class LocationController {
    constructor(axios) {
        this.axios = axios;
    }

    async get() {
        try {
            const response = await this.axios.get("v1/users/locations");
            if (response.status !== 200) {
                return response.data[0];
            }

            return this.formatUserData(response.data.data);
        } catch (error) {
            console.error("Error fetching locations:", error);
            throw new Error("Failed to fetch locations");
        }
    }

    formatUserData(locations) {
        const formattedData = [];
        locations.forEach((location) => {
            location.forecasts.forEach((forecast) => {
                formattedData.push({
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

        return formattedData;
    }

    async getForecastByCityAndState(city, state) {
        try {
            const response = await this.axios.get("v1/get-location-forecast", {
                params: {
                    city: city,
                    state: state,
                },
            });

            if (response.status !== 200) {
                return this.convertErrorFromArray(response.data);
            }

            return response.data.data;
        } catch (error) {
            console.error("Error fetching forecast data:", error);
            throw new Error("Failed to fetch forecasts");
        }
    }

    convertErrorFromArray(error) {
        const transformedErrors = {};
        for (let field in error.data) {
            transformedErrors[field] = error.data[field][0];
        }
        return transformedErrors;
    }

    async save(city, state, weatherData) {
        try {
            const response = await this.axios.post("v1/users/locations", {
                city: city,
                state: state,
                weatherData: weatherData,
            });

            if (response.status !== 200) {
                return response.data[0];
            }

            return true;
        } catch (error) {
            console.error("Error saving user location:", error);
            throw new Error("Failed to save location");
        }
    }

    async delete(locationId, date) {
        try {
            const response = await this.axios.delete(
                `v1/users/locations/${locationId}/${date}`
            );
            if (response.status !== 204) {
                return response.data[0];
            }
        } catch (error) {
            console.error("Error deleting user location:", error);
            throw new Error("Failed to delete location");
        }
    }
}
