import { OpenStreetMapProvider } from 'leaflet-geosearch';
import axios from 'axios';

const openCageDataApiKey = '90118bf7d9aa477f8dc1398445c9341d';

export default new OpenStreetMapProvider();

export async function geolocateByCords( lat: number, lng: number ): Promise<OpenCageResponse>
{

    let url = `https://api.opencagedata.com/geocode/v1/json?q=${ lat }+${ lng }&key=${ openCageDataApiKey }&language=en`;

    const response = await axios.get( url );

    return response.data;

}

export interface OpenStreetMapProviderResponse
{
    x: string;
    y: string;
    label: string;
    bounds: any[];
}

export interface OpenCageResponse
{
    results: OpenCageResult[];
}

export interface OpenCageResult
{
    components: {
        country: string;
        state: string;
        village: string;
        county: string;
        city: string;
        continent: string;
        neighbourhood: string;
        road: string;
    }
    formatted: string;
}
