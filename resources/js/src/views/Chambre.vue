<template>
  <div class="w-full py-24">
    <div class="max-w-7xl mx-auto px-4">
      <h1 class="text-4xl font-bold mb-8">Nos Chambres</h1>
      <p class="mb-6">
        Découvrez nos bungalows confortables avec vue sur jardin ou sur mer.
      </p>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <img src="/chambre/Bungalow-Jardin-01.png" alt="Bungalow Jardin" class="w-full h-64 object-cover">
          <div class="p-6">
            <h3 class="text-2xl font-semibold mb-2">Bungalow Jardin</h3>
            <p class="text-gray-600 mb-4">Profitez d'un cadre verdoyant et paisible.</p>
            <RouterLink :to="{ name: 'ChambreResa' }" class="inline-block bg-[#FE8A24] text-white py-2 px-6 rounded-full hover:bg-[#09012B] transition-colors">
              Réserver
            </RouterLink>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <img src="/chambre/Bungalow-Mer-01.png" alt="Bungalow Vue Mer" class="w-full h-64 object-cover">
          <div class="p-6">
            <h3 class="text-2xl font-semibold mb-2">Bungalow Vue Mer</h3>
            <p class="text-gray-600 mb-4">Admirez l'océan depuis votre terrasse.</p>
            <RouterLink :to="{ name: 'ChambreResa' }" class="inline-block bg-[#FE8A24] text-white py-2 px-6 rounded-full hover:bg-[#09012B] transition-colors">
              Réserver
            </RouterLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';
</script>
