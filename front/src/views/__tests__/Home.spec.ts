import { describe, it, expect, beforeEach, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import Home from '../Home.vue'
import { useWeatherStore } from '@/stores/weather'
import weatherApi from '@/api/weatherApi'

vi.mock('@/api/weatherApi')

describe('Home.vue', () => {
    beforeEach(() => {
        const pinia = createPinia()
        setActivePinia(pinia)
        vi.clearAllMocks()
        vi.mocked(weatherApi.getFavorites).mockResolvedValue([])
    })

    it('renders the initial state properly', async () => {
        const pinia = createPinia()
        const wrapper = mount(Home, {
            global: {
                plugins: [pinia]
            }
        })
        expect(wrapper.find('h1').text()).toBe('Prévisions Météo')
        expect(wrapper.findComponent({ name: 'SearchBar' }).exists()).toBe(true)
    })

    it('shows loading state when store is loading', async () => {
        const pinia = createPinia()
        const store = useWeatherStore(pinia)
        store.loading = true

        const wrapper = mount(Home, {
            global: {
                plugins: [pinia]
            }
        })

        expect(wrapper.find('.status').text()).toBe('Chargement...')
    })

    it('displays weather card when data is available', async () => {
        const pinia = createPinia()
        const store = useWeatherStore(pinia)

        const wrapper = mount(Home, {
            global: {
                plugins: [pinia]
            }
        })

        store.currentWeatherData = { city: 'Dijon', latitude: 47.31, longitude: 5.01, temperature: 15 } as any
        await wrapper.vm.$nextTick()

        expect(wrapper.findComponent({ name: 'WeatherCard' }).exists()).toBe(true)
        expect(wrapper.text()).toContain('Dijon')
    })

    it('calls searchWeather when SearchBar emits search', async () => {
        const pinia = createPinia()
        const store = useWeatherStore(pinia)
        const searchSpy = vi.spyOn(store, 'searchWeather')

        const wrapper = mount(Home, {
            global: {
                plugins: [pinia]
            }
        })

        await wrapper.findComponent({ name: 'SearchBar' }).vm.$emit('search', 'Dijon')

        expect(searchSpy).toHaveBeenCalledWith('Dijon')
    })
})
