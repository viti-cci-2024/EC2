<template>
  <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-4">
    <h1 class="text-6xl font-bold text-gray-800 mb-4">404</h1>
    <h2 class="text-2xl font-semibold text-gray-600 mb-8">Page non trouvée</h2>
    <p class="text-gray-500 mb-8 text-center max-w-md">
      La page que vous recherchez n'existe pas ou a été déplacée.
    </p>
    <RouterLink :to="{ name: 'Home' }" class="px-6 py-3 bg-[#FE8A24] text-white rounded-full hover:bg-[#09012B] transition-colors">
      Retour à l'accueil
    </RouterLink>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';
</script>
