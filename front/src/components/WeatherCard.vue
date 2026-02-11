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
    <div class="header">
      <h2 v-if="weatherData?.city">{{ weatherData?.city }} ({{ weatherData?.latitude }}, {{ weatherData?.longitude }})</h2>
      <h2 v-else-if="weatherData?.latitude">GPS : {{ weatherData?.latitude }}, {{ weatherData?.longitude }}</h2>
      <h2 v-else>Aucun résultat trouvé</h2>
      <button @click="emit('save')" v-if="!isFavorite">
        Ajouter aux favoris
      </button>
    </div>
    
    <div class="content">
      <div class="temperature">
        {{ weatherData?.temperature }}°C
      </div>
      <div class="details">
        <div>Vitesse du vent: {{ weatherData.windSpeed }} km/h</div>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>
