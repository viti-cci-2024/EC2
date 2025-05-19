<template>
  <div class="w-full py-24">
    <div class="max-w-7xl mx-auto px-4">
      <h1 class="text-4xl font-bold mb-8">Sortie en Kayak</h1>
      <p class="mb-6">
        Explorez le lagon en kayak et découvrez des paysages à couper le souffle.
      </p>
      <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <img src="/carou-kayak.jpg" alt="Sortie en Kayak" class="w-full h-64 object-cover">
        <div class="p-6">
          <h3 class="text-2xl font-semibold mb-2">Excursion en kayak</h3>
          <p class="text-gray-600 mb-4">Une aventure nautique pour toute la famille.</p>
          <RouterLink :to="{ name: 'KayakResa' }" class="inline-block bg-[#FE8A24] text-white py-2 px-6 rounded-full hover:bg-[#09012B] transition-colors">
            Réserver une sortie
          </RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';
</script>
