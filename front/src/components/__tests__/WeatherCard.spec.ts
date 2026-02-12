import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import WeatherCard from '../WeatherCard.vue'

describe('WeatherCard', () => {
    const mockWeatherData = {
        city: 'Dijon',
        latitude: 47.31,
        longitude: 5.01,
        temperature: 15,
        apparentTemperature: 13,
        windSpeed: 10,
        relativeHumidity: 60
    }

    it('renders weather data correctly', () => {
        const wrapper = mount(WeatherCard, {
            props: {
                weatherData: mockWeatherData as any,
                isFavorite: false
            }
        })

        expect(wrapper.text()).toContain('Dijon')
        expect(wrapper.text()).toContain('15°C')
        expect(wrapper.text()).toContain('Vitesse du vent: 10 km/h')
    })

    it('renders GPS coordinates when city is missing', () => {
        const wrapper = mount(WeatherCard, {
            props: {
                weatherData: { ...mockWeatherData, city: '' } as any,
                isFavorite: false
            }
        })

        expect(wrapper.text()).toContain('GPS : 47.31, 5.01')
    })

    it('emits toggle event when button is clicked', async () => {
        const wrapper = mount(WeatherCard, {
            props: {
                weatherData: mockWeatherData as any,
                isFavorite: false
            }
        })

        await wrapper.find('.favorite-btn').trigger('click')
        expect(wrapper.emitted().toggle).toBeTruthy()
    })

    it('displays active star when isFavorite is true', () => {
        const wrapper = mount(WeatherCard, {
            props: {
                weatherData: mockWeatherData as any,
                isFavorite: true
            }
        })

        expect(wrapper.find('.favorite-btn.active').exists()).toBe(true)
        expect(wrapper.text()).toContain('★')
    })
})
