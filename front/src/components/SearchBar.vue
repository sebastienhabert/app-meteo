<script setup lang="ts">
import { ref } from 'vue'

const emit = defineEmits(['search'])
const query = ref('')

const handleSearch = () => {
  if (query.value.trim()) {
    emit('search', query.value)
    query.value = ''
  }
}
</script>

<template>
  <div class="search-bar">
    <input 
      v-model="query" 
      type="text" 
      placeholder="Rechercher une ville..." 
      @keyup.enter="handleSearch"
      class="search-input"
    />
    <button @click="handleSearch" class="search-button">
      Rechercher
    </button>
  </div>
</template>

<style scoped></style>
