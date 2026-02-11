<script setup lang="ts">
import type { Favorite } from '@/types/favorite'

defineProps<{
  favorites: Favorite[]
}>()

const emit = defineEmits(['select', 'delete'])
</script>

<template>
  <div class="favorites" v-if="favorites.length > 0">
    <h3>Favoris / Historique</h3>
    <ul class="favorites-list">
      <li v-for="favorite in favorites" :key="favorite.id" class="favorite-item">
        <strong>{{ favorite.name }}</strong>
        <button @click="emit('select', favorite)" class="select-btn">
          Voir
        </button>
        <button @click="emit('delete', favorite.id)" class="delete-btn">
          Supprimer
        </button>
      </li>
    </ul>
  </div>
</template>

<style scoped></style>
