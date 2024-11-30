import { httpClient } from "@/app/plugins/axios";

export const useApi = async (url: string, params: object = {}) => {
    let dataReturn: any[] = [];
    const error = null;

    await httpClient
        .get(url, { params })
        .then(({ data }) => {
            dataReturn = data.data;
        })
        .catch((error) => (error.value = error));

    return { dataReturn, error };
};
