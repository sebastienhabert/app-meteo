<script setup lang="ts">
import type { Favorite } from '@/types/favorite'

defineProps<{
  favorites: Favorite[]
}>()

const emit = defineEmits(['select', 'delete'])
</script>

<template>
  <div class="favorites" v-if="favorites.length > 0">
    <h3>Mes favoris</h3>
    <nav class="favorites-list">
      <li v-for="favorite in favorites" :key="favorite.id" class="favorite-item">
        <button @click="emit('select', favorite)" class="select-btn">
          {{ favorite.name }}
        </button>
        <button @click="emit('delete', favorite.id)" class="delete-btn">
          X
        </button>
      </li>
    </nav>
  </div>
</template>

<style scoped>
h3 {
  margin: 1rem 0;
}

.favorites {
  margin-top: 1rem;
}

.favorites-list {
  list-style: none;
  padding: 0;
}

.favorite-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-top: 1px solid #333;
  gap: .5rem;
}

.favorite-item:last-child {
  border-bottom: 1px solid #333;
}

.select-btn {
  padding: .5rem;
  flex: 1;
  font-size: 1rem;
  text-align: left;
  border: none;
  cursor: pointer;
  background: none;
  font-weight: bold;
}

.delete-btn {
  border: none;
  padding: .5rem;
  cursor: pointer;
  background: none;
}
</style>
