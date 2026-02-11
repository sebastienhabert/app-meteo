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

<style scoped>
.search-bar {
  display: flex;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.search-bar input {
  flex: 1;
  padding: 0.75rem 1rem;
  border-radius: 0.5rem;
  border: none;
  outline: none;
  font-size: 1rem;
}

.search-bar button {
  padding: 0.75rem 1rem;
  border-radius: 0.5rem;
  border: none;
  background: #4f46e5;
  color: white;
  cursor: pointer;
}

.search-bar button:hover {
  background: #4338ca;
}
</style>
