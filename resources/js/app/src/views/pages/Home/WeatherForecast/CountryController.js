import { httpClient } from "@/app/plugins/axios";

export class CountryController {
    async get() {
        try {
            const response = await httpClient.get("v1/countries");
            return response.data;
        } catch (error) {
            console.error("Error fetching countries:", error);
            throw error;
        }
    }
}
