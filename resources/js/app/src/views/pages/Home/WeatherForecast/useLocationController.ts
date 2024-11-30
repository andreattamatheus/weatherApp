import { FormattedLocation } from "@/app/types/FormattedLocation";
import { useApi } from "@/utils/api";
import { ApiResponse } from "@/app/types/ApiResponse";
import { Location } from "@/app/types/Location";
import { Forecast } from "@/app/types/Forecast";

export const getLocations = async (): Promise<FormattedLocation[] | string> => {
    try {
        const { dataReturn, error }: ApiResponse =
            await useApi("v1/users/locations");
        if (error) {
            return error;
        }

        return formatUserData(dataReturn);
    } catch (error) {
        console.error("Error fetching locations:", error);
        throw new Error("Failed to fetch locations");
    }
};

export const formatUserData = (locations: Location[]): FormattedLocation[] => {
    const formattedData: FormattedLocation[] = [];
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
};

export const getForecastByCityAndState = async (params: {
    city: string;
    state: string;
}) => {
    try {
        const { dataReturn, error }: ApiResponse = await useApi(
            "v1/users/locations",
            params,
        );

        if (error) {
            return convertErrorFromArray(error);
        }

        return dataReturn;
    } catch (error) {
        console.error("Error fetching forecast data:", error);
        throw new Error("Failed to fetch forecasts");
    }
};

export const convertErrorFromArray = (error: object = {}) => {
    const transformedErrors = {};
    for (let field in error) {
        console.log("field", field);
    }
    return transformedErrors;
};

// export const save = async (city, state, weatherData) => {
//     try {
//         const response = await httpClient.post("v1/users/locations", {
//             city: city,
//             state: state,
//             weatherData: weatherData,
//         });

//         if (response.status !== 200) {
//             return response.data[0];
//         }

//         return true;
//     } catch (error) {
//         console.error("Error saving user location:", error);
//         throw new Error("Failed to save location");
//     }
// };

// export const deleteLocation = async (locationId, date) => {
//     try {
//         const response = await httpClient.delete(
//             `v1/users/locations/${locationId}/${date}`,
//         );
//         if (response.status !== 204) {
//             return response.data[0];
//         }
//     } catch (error) {
//         console.error("Error deleting user location:", error);
//         throw new Error("Failed to delete location");
//     }
// };
