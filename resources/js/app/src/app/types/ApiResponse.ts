import { Location } from "./Location";

export interface ApiResponse {
    dataReturn: Location[];
    error: string | null;
}
