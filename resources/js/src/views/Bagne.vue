<template>
  <div class="w-full py-24">
    <div class="max-w-7xl mx-auto px-4">
      <h1 class="text-4xl font-bold mb-8">Visite du Bagne</h1>
      <p class="mb-6">
        Découvrez l'histoire fascinante du bagne de l'île Pam avec nos visites guidées.
      </p>
      <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <img src="/carou-bagne.jpg" alt="Visite du Bagne" class="w-full h-64 object-cover">
        <div class="p-6">
          <h3 class="text-2xl font-semibold mb-2">Visite guidée historique</h3>
          <p class="text-gray-600 mb-4">Plongez dans l'histoire du bagne avec notre guide expérimenté.</p>
          <RouterLink :to="{ name: 'BagneResa' }" class="inline-block bg-[#FE8A24] text-white py-2 px-6 rounded-full hover:bg-[#09012B] transition-colors">
            Réserver une visite
          </RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';
</script>
