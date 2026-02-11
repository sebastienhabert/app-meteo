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
    getFavorites() {
        return apiClient.get('/favorites')
    },
    addFavorite(favorite: { name: string; latitude: number; longitude: number }) {
        return apiClient.post('/favorites', favorite)
    },
    deleteFavorite(id: number) {
        return apiClient.delete(`/favorites/${id}`)
    },
}
