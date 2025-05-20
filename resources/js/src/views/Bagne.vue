<template>
  <!-- Section hero pleine largeur avec background et hauteur 60vh -->
  <section class="w-full h-[60vh] bg-cover bg-center" :style="{ backgroundImage: 'url(/bagne/bagne-hero.webp)' }">
    <!-- Conteneur pour centrer verticalement le contenu avec padding -->
    <div class="max-w-7xl mx-auto flex flex-col justify-center items-start h-full p-5">
      <!-- Contenu texte en blanc aligné à gauche -->
      <div class="text-white text-left">
        <h1 class="mb-3 text-shadow text-4xl font-bold">
          Découvrez les secrets du passé
        </h1>
        <h4 class="mb-3 text-shadow text-2xl">
          là où l'histoire ne dort jamais.
        </h4>
        <!-- Bouton Réservez via RouterLink vers la page "Chambre" -->
        <RouterLink :to="{ name: 'BagneResa' }"
          class="inline-block text-lg font-semibold py-3 px-8 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] hover:scale-105">
          Réserver
        </RouterLink>
      </div>
    </div>
  </section>

  <!-- BAGNE PHOTO + TEXTE 1 et 2 -->
  <!-- BAGNE 001 -->
  <section class="py-10 px-4 bg-white mt-10">
    <div class="max-w-7xl mx-auto">
      <div class="flex flex-col md:flex-row justify-around gap-6">
        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center">
          <div class="absolute inset-0 flex flex-col justify-center p-4 md:p-6">
            <h2 class="text-3xl font-bold">Plongez dans l'histoire</h2>
            <br />
            <h4 class="text-lg md:text-xl mb-2">
              Avant de commencer la visite, laissez-vous transporter par l'atmosphère unique de ces vestiges du passé.
              Chaque pierre raconte une histoire qui ne demande qu'à être entendue.
            </h4>
          </div>
        </div>
        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center rounded-xl shadow-lg"
          style="background-image: url('/bagne/bagne-1.jpeg')"></div>
      </div>
    </div>
  </section>

  <!-- BAGNE 002 -->
  <section class="py-10 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
      <div class="flex flex-col md:flex-row justify-around gap-6">
        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center rounded-xl shadow-lg"
          style="background-image: url('/bagne/bagne-2.jpg')"></div>
        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center">
          <div class="absolute inset-0 flex flex-col justify-center p-4 md:p-6">
            <h2 class="text-3xl font-bold">Une expérience unique</h2>
            <br />
            <h4 class="text-lg md:text-xl mb-2 pb-2">
              Explorez les ruines et découvrez les récits poignants des anciens détenus. La visite du bagne est bien
              plus qu'un simple voyage dans le passé : c'est une immersion dans une histoire riche en émotions.
            </h4><br>
            <ul class="space-y-4">
              <li class="pb-2 border-b border-gray-300 last:border-0">
                ⏳ Durée de la visite : 90 minutes
              </li>
              <li class="pb-2 border-b border-gray-300 last:border-0">
                🧑‍🏫 Guide expérimenté inclus
              </li>
              <li class="pb-2 border-b border-gray-300 last:border-0">
                👥 Groupes limités à 10 personnes
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- BANDEAU PRE-FOOTER avec le slider -->
  <PrefooterBagne />



</template>
<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import PrefooterBagne from '../components/PrefooterBagne.vue';

</script>