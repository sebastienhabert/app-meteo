import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useWeatherStore } from '../weather'
import weatherApi from '@/api/weatherApi'

vi.mock('@/api/weatherApi')

describe('weatherStore', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('should initialize with default state', () => {
        const store = useWeatherStore()
        expect(store.currentWeatherData).toBe(null)
        expect(store.favorites).toEqual([])
        expect(store.loading).toBe(false)
        expect(store.error).toBe('')
    })

    it('should search weather and update state', async () => {
        const store = useWeatherStore()
        const mockWeather = { data: { city: 'Dijon', latitude: 47.31, longitude: 5.01, temperature: 15 } }
        vi.mocked(weatherApi.getWeather).mockResolvedValue(mockWeather as any)

        await store.searchWeather('Dijon')

        expect(store.currentWeatherData).toEqual(mockWeather.data)
        expect(store.loading).toBe(false)
        expect(store.error).toBe('')
    })

    it('should handle search errors', async () => {
        const store = useWeatherStore()
        vi.mocked(weatherApi.getWeather).mockRejectedValue({
            response: { data: { error: 'City not found' } }
        })

        await store.searchWeather('Unknown')

        expect(store.currentWeatherData).toBe(null)
        expect(store.error).toBe('City not found')
    })

    it('should toggle favorite (add)', async () => {
        const store = useWeatherStore()
        store.currentWeatherData = { city: 'Dijon', latitude: 47.31, longitude: 5.01 } as any
        vi.mocked(weatherApi.getFavorites).mockResolvedValue([])
        vi.mocked(weatherApi.addFavorite).mockResolvedValue({ id: 1 } as any)

        await store.toggleFavorite()

        expect(weatherApi.addFavorite).toHaveBeenCalled()
        expect(weatherApi.getFavorites).toHaveBeenCalled()
    })

    it('should toggle favorite (remove)', async () => {
        const store = useWeatherStore()
        store.currentWeatherData = { city: 'Dijon', latitude: 47.31, longitude: 5.01 } as any
        store.favorites = [{ id: 1, name: 'Dijon', latitude: 47.31, longitude: 5.01 }] as any

        vi.mocked(weatherApi.deleteFavorite).mockResolvedValue({} as any)
        vi.mocked(weatherApi.getFavorites).mockResolvedValue([])

        await store.toggleFavorite()

        expect(weatherApi.deleteFavorite).toHaveBeenCalledWith(1)
        expect(weatherApi.getFavorites).toHaveBeenCalled()
    })

    it('should compute isFavorite correctly', () => {
        const store = useWeatherStore()
        store.currentWeatherData = { latitude: 47.31, longitude: 5.01 } as any
        store.favorites = [{ latitude: 47.31, longitude: 5.01 }] as any

        expect(store.isFavorite).toBe(true)

        store.currentWeatherData = { latitude: 49, longitude: 3 } as any
        expect(store.isFavorite).toBe(false)
    })
})
