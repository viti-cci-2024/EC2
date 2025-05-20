<template>
  <!-- Section hero pleine largeur avec background et hauteur 60vh -->
  <section class="w-full h-[50vh] md:h-[30vh] bg-cover bg-center" :style="{ backgroundImage: 'url(/bagne/bagne-hero.webp)' }">
    <!-- Conteneur pour centrer verticalement le contenu avec padding -->
    <div class="max-w-7xl mx-auto flex flex-col justify-center items-start h-full p-5">
      <!-- Contenu texte en blanc aligné à gauche -->
      <div class="text-white text-left">
        <h1 class="mb-3 text-shadow text-4xl font-bold">
          Une belle visite vous attend
        </h1>
        <h4 class="mb-3 text-shadow text-2xl">
          Planifiez cette activité dès à présent.
        </h4>

      </div>
    </div>
  </section>


<!------------------------- FORMULAIRE RESA BAGNE ------------------------------>
<section class="py-10 px-4 bg-white mt-10">
  <div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold mb-4">Réservation de la visite du Bagne</h1>
    <div class="flex flex-col md:flex-row gap-4">
      <!-- Formulaire de réservation pour les repas -->
      <div class="flex-1">
        <ReservationBagne />
      </div>
        <!-- Widget météo affichant les infos pour la ville de Poum -->
        <div class="flex-1">
          <WeatherWidget />
          <br>
          <p class="mb-3"><strong>Pour réserver votre visite du bagne, vous avez besoin de votre numéro de réservation de chambre.
              Si vous n'en avez pas encore un, veuillez d'abord réserver votre chambre</strong></p>
          <RouterLink :to="{ name: 'ChambreResa' }"
            class="inline-block text-sm font-medium py-2 px-4 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] hover:scale-105">
            Réserver une chambre
          </RouterLink>
        </div>
    </div>
  </div>
</section>

<PrefooterResa />

  

</template>
<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import ReservationBagne from '../components/ReservationBagne.vue';
import PrefooterResa from '../components/PrefooterResa.vue';
import WeatherWidget from '../components/WeatherWidget.vue';



</script>

<style scoped>
/* insérer style ici */
</style>