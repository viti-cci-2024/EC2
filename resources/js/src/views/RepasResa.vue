<template>
  <!------------------------- NEW HERO RESA CHAMBRE ------------------------------>
  <section class="w-full h-[50vh] md:h-[30vh] bg-cover bg-center"
    :style="{ backgroundImage: 'url(/repas/resa-hero-bando2.webp)' }">
    <!-- Conteneur pour centrer verticalement le contenu avec padding -->
    <div class="max-w-7xl mx-auto flex flex-col justify-center items-start h-full p-5">
      <!-- Contenu texte en blanc aligné à gauche -->
      <div class="text-white text-left">
        <h1 class="mb-3 text-shadow text-4xl font-bold">Un bon repas vous attend</h1>
        <h4 class="mb-3 text-shadow text-2xl">
          Planifiez votre expérience culinaire dès maintenant.
        </h4>
      </div>
    </div>
  </section>


  <!------------------------- FORMULAIRE RESA REPAS ------------------------------>
  <section class="py-10 px-4 bg-white mt-10">
    <div class="max-w-7xl mx-auto">
      <h1 class="text-3xl font-bold mb-4">Réservation de Repas</h1>
      <div class="flex flex-col md:flex-row gap-4">
        <!-- Formulaire de réservation pour les repas -->
        <div class="flex-1">
          <ReservationRepas />
        </div>
        <!-- Widget météo affichant les infos pour la ville de Poum -->
        <div class="flex-1">
          <WeatherWidget />
          <br>
          <p class="mb-3"><strong>Pour réserver votre repas, vous avez besoin de votre numéro de réservation de chambre.
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

<!------------------------- SCRIPTS ------------------------------>
<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import WeatherWidget from '../components/WeatherWidget.vue';
import ReservationRepas from '../components/ReservationRepas.vue';
import PrefooterResa from '../components/PrefooterResa.vue';
import { RouterLink } from 'vue-router'


</script>



<!------------------------- STYLES ------------------------------>

<style scoped>
/*  */

</style>
