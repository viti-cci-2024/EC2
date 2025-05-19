<template>
  <div class="w-full py-24">
    <div class="max-w-7xl mx-auto px-4">
      <h1 class="text-4xl font-bold mb-8">Notre Restaurant</h1>
      <p class="mb-6">
        Savourez une cuisine locale délicieuse dans notre restaurant avec vue sur le lagon.
      </p>
      <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <img src="/carou-repas.jpg" alt="Restaurant" class="w-full h-64 object-cover">
        <div class="p-6">
          <h3 class="text-2xl font-semibold mb-2">Restaurant Pim</h3>
          <p class="text-gray-600 mb-4">Une expérience culinaire unique avec des produits frais et locaux.</p>
          <RouterLink :to="{ name: 'RepasResa' }" class="inline-block bg-[#FE8A24] text-white py-2 px-6 rounded-full hover:bg-[#09012B] transition-colors">
            Réserver une table
          </RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';
</script>
