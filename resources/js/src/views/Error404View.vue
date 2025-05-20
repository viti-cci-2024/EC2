<template>
  <div class="bg-gray-50 flex items-center justify-center py-20">
    <div class="max-w-md text-center p-6 bg-white rounded-xl shadow-lg">
      <h1 class="text-6xl font-bold text-gray-800">404</h1>
      <h2 class="mt-4 text-3xl font-semibold text-gray-700">Page non trouvée</h2>
      <p class="mt-2 text-gray-600">La page que vous cherchez n'existe pas.</p>
      <!-- Bouton Réservez via RouterLink vers la page "RepasResa" -->
      <RouterLink
        :to="{ name: 'Home' }"
        class="mt-6 inline-block text-lg font-semibold py-3 px-8 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] hover:scale-105"
      >
        Retour Accueil
      </RouterLink>
    </div>
  </div>
</template>