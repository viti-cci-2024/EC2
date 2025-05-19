<template>
  <div class="w-full py-24">
    <div class="max-w-7xl mx-auto px-4">
      <h1 class="text-4xl font-bold mb-8">La Garderie</h1>
      <p class="mb-6">
        Profitez de vos activités en toute sérénité pendant que vos enfants s'amusent dans notre garderie.
      </p>
      <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <img src="/carou-gard.jpg" alt="Garderie" class="w-full h-64 object-cover">
        <div class="p-6">
          <h3 class="text-2xl font-semibold mb-2">Garderie pour enfants</h3>
          <p class="text-gray-600 mb-4">Un espace sécurisé et amusant pour les enfants de tous âges.</p>
          <RouterLink :to="{ name: 'GarderieResa' }" class="inline-block bg-[#FE8A24] text-white py-2 px-6 rounded-full hover:bg-[#09012B] transition-colors">
            Réserver une place
          </RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';
</script>
