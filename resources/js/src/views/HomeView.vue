<template>
  <!-- Page d'accueil -->
  <div class="w-full">
    <!-- HERO -->
    <section
      class="w-full h-[60vh] bg-cover bg-center"
      :style="{ backgroundImage: 'url(/hero-main.jpg)' }"
    >
      <div class="max-w-7xl mx-auto flex flex-col justify-center items-start h-full p-5">
        <div class="text-white text-left">
          <h1 class="mb-4 text-shadow text-4xl font-bold">
            Bienvenue au gîte Pim
          </h1>
          <h4 class="mb-6 text-shadow text-2xl">
            Venez vous ressourcer dans un cadre idyllique.
          </h4>
          <RouterLink
            :to="{ name: 'Chambre' }"
            class="inline-block font-semibold text-lg py-3 px-8 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B]"
          >
            En savoir plus
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- CONTENU PRINCIPAL -->
    <section class="py-10 px-4 bg-white">
      <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold mb-6">Bienvenue sur le nouveau site du Gîte Pim</h2>
        <p class="mb-4">
          Notre site est en cours d'intégration avec un système de réservation en ligne.
        </p>
        <p>
          Vous pourrez bientôt réserver directement vos séjours et activités depuis notre plateforme.
        </p>
      </div>
    </section>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';
</script>

<style scoped>
.text-shadow {
  text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
}
</style>
