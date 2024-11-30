import { ApiResponse } from "@/app/types/ApiResponse";
import { useApi } from "@/utils/api";

export const getCountries = async () => {
    try {
        const { dataReturn, error }: ApiResponse = await useApi("v1/countries");
        if (error) {
            return error;
        }

        return dataReturn;
    } catch (error) {
        console.error("Error fetching countries:", error);
        throw error;
    }
};
