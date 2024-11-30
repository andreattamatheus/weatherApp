import { httpClient } from "@/app/plugins/axios";

export class AuthController {
    async login(formData) {
        try {
            const response = await httpClient.post("v1/login", {
                email: formData.email,
                password: formData.password,
            });

            if (response.data.errors) {
                return {
                    statusCode: 204,
                    body: {
                        errors: response.data.errors,
                    },
                };
            }

            localStorage.setItem("access_token", response.data.access_token);

            return {
                statusCode: 200,
                body: {
                    accessToken: response.access_token,
                },
            };
        } catch (error) {
            throw new Error("Failed to login");
        }
    }
}
