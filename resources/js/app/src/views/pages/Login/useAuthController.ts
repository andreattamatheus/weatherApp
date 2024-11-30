import { ApiResponse } from "@/app/types/ApiResponse";
import { useApi } from "@/utils/api";

export interface LoginFormData {
    email: string;
    password: string;
}

export interface LoginResponse {
    dataReturn: {
        accessToken: string;
    };
    error: string;
}

export const login = async ({ email, password }: LoginFormData) => {
    try {
        const { dataReturn, error } = await useApi(
            "v1/login",
            {
                email: email,
                password: password,
            },
            "post",
        );

        if (error) {
            return {
                statusCode: 204,
                body: {
                    errors: error,
                },
            };
        }

        localStorage.setItem("access_token", dataReturn.access_token);

        return {
            statusCode: 200,
            body: {
                accessToken: dataReturn.access_token,
            },
        };
    } catch (error) {
        throw new Error("Failed to login");
    }
};
