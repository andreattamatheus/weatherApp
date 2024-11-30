import { Forecast } from "./Forecast";

export interface Location {
    id: string;
    city: string;
    forecasts: Forecast[];
}
