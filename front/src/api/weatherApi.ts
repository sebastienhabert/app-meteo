import axios from 'axios'
import type { Favorite } from '@/types/favorite'

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
    async getFavorites(): Promise<Favorite[]> {
        const response = await apiClient.get<Favorite[]>('/favorites')
        return response.data
    },
    async addFavorite(favorite: Omit<Favorite, 'id' | 'createdAt'>): Promise<Favorite> {
        const response = await apiClient.post<Favorite>('/favorites', favorite)
        return response.data
    },
    deleteFavorite(id: number) {
        return apiClient.delete(`/favorites/${id}`)
    },
}
