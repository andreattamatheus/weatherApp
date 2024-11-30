import { httpClient } from "@/app/plugins/axios";

export const useApi = async (
    url: string,
    params: object = {},
    method: string = "get",
) => {
    let dataReturn: any = {};
    let error = null;

    await httpClient({
        url,
        method,
        params,
    })
        .then(({ data }) => {
            dataReturn = data.data;
        })
        .catch((err) => {
            error = err;
        });

    return { dataReturn, error };
};
