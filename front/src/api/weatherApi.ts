import axios from 'axios'

const apiClient = axios.create({
    baseURL: 'http://localhost/api',
    headers: {
        'Content-Type': 'application/json',
    },
})

export default {
    getWeather(query: string) {
        return apiClient.get('/weather', { params: { query } })
    },
}
