import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import weatherApi from '@/api/weatherApi'
import type { Favorite } from '@/types/favorite'
import type { WeatherData } from '@/types/weatherData'

export const useWeatherStore = defineStore('weather', () => {
    const currentWeatherData = ref<WeatherData | null>(null)
    const favorites = ref<Favorite[]>([])
    const loading = ref(false)
    const error = ref('')

    const isFavorite = computed(() => Boolean(currentFavorite.value))

    const currentFavorite = computed(() => {
        if (!currentWeatherData.value) return null
        return favorites.value.find(fav =>
            fav.latitude === currentWeatherData.value?.latitude &&
            fav.longitude === currentWeatherData.value?.longitude
        ) || null
    })

    async function toggleFavorite() {
        if (!currentWeatherData.value) return

        const favorite = currentFavorite.value
        if (favorite) {
            await deleteFavorite(favorite.id)
        } else {
            await saveFavorite()
        }
    }

    async function fetchFavorites() {
        try {
            favorites.value = await weatherApi.getFavorites()
        } catch (err) {
            console.error('Failed to fetch favorites', err)
        }
    }

    async function searchWeather(query: string) {
        loading.value = true
        error.value = ''
        currentWeatherData.value = null
        try {
            const response = await weatherApi.getWeather(query)
            currentWeatherData.value = response.data
        } catch (err: any) {
            error.value = err.response?.data?.error || 'Une erreur est survenue'
        } finally {
            loading.value = false
        }
    }

    async function selectFavorite(favorite: Favorite) {
        loading.value = true
        error.value = ''
        try {
            const response = await weatherApi.getWeather(favorite.name)
            currentWeatherData.value = response.data
        } catch (err: any) {
            error.value = 'Erreur lors du chargement du favori'
        } finally {
            loading.value = false
        }
    }

    async function saveFavorite() {
        if (!currentWeatherData.value) return
        try {
            await weatherApi.addFavorite({
                name: currentWeatherData.value.city,
                latitude: currentWeatherData.value.latitude,
                longitude: currentWeatherData.value.longitude
            })
            await fetchFavorites()
        } catch (err) {
            console.error('Failed to save favorite', err)
        }
    }

    async function deleteFavorite(id: number) {
        try {
            await weatherApi.deleteFavorite(id)
            await fetchFavorites()
        } catch (err) {
            console.error('Failed to delete favorite', err)
        }
    }

    return {
        currentWeatherData,
        favorites,
        loading,
        error,
        isFavorite,
        fetchFavorites,
        searchWeather,
        selectFavorite,
        saveFavorite,
        deleteFavorite,
        toggleFavorite
    }
})
