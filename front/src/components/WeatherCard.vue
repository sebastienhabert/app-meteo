<script setup lang="ts">
import type { WeatherData } from '@/types/weatherData';

defineProps<{
  weatherData: WeatherData
  isFavorite: boolean
}>()

const emit = defineEmits(['save'])
</script>

<template>
  <div class="weather-card">
    <div class="weather-header">
      <h2 v-if="weatherData?.city">{{ weatherData?.city }} ({{ weatherData?.latitude }}, {{ weatherData?.longitude }})</h2>
      <h2 v-else>GPS : {{ weatherData?.latitude }}, {{ weatherData?.longitude }}</h2>
      <button
        @click="isFavorite ? null : emit('save')"
        class="favorite-btn"
        :class="{ active: isFavorite }"
      >
        {{ isFavorite ? '★' : '☆' }}
      </button>
    </div>
    
    <div class="weather-content">
      <div class="temperature">
        {{ weatherData?.temperature }}°C / {{ weatherData?.apparentTemperature }}°C
      </div>
      <div class="details">
        <div>Vitesse du vent: {{ weatherData.windSpeed }} km/h</div>
        <div>Humidité relative: {{ weatherData.relativeHumidity }} %</div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.weather-card {
  background: white;
  border-radius: 1rem;
  padding: 1.25rem;
  box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
}

.weather-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.weather-header h2 {
  margin: 0;
  font-weight: 600;
  font-size: 1.25rem;
  line-height: 2rem;
}

.favorite-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  line-height: 2rem;
  cursor: pointer;
  color: orange;
}

.weather-content {
  margin-top: 1rem;
}

.temperature {
  font-size: 2.5rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.details > div {
  margin: 0.25rem 0;
  font-size: 1rem;
  color: #333;
}
</style>
