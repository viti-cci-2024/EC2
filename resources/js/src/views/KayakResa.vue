<template>
  <!-- SECTION HERO -->
  <section class="w-full h-[50vh] md:h-[30vh] bg-cover bg-center" :style="{ backgroundImage: 'url(/kayak/kayak-hero.webp)' }">
    <div class="max-w-7xl mx-auto flex flex-col justify-center items-start h-full p-5">
      <div class="text-white text-left">
        <h1 class="mb-3 text-shadow text-4xl font-bold">Une belle sortie vous attend</h1>
        <h4 class="mb-3 text-shadow text-2xl">Planifiez votre balade dès maintenant.</h4>

      </div>
    </div>
  </section>


<!------------------------- FORMULAIRE RESA KAYAK ------------------------------>
<section class="py-10 px-4 bg-white mt-10">
  <div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold mb-4">Réservation de votre sortie en Kayak</h1>
    <div class="flex flex-col md:flex-row gap-4">
      <!-- Formulaire de réservation pour les repas -->
      <div class="flex-1">
        <ReservationKayak />
      </div>
        <!-- Widget météo affichant les infos pour la ville de Poum -->
        <div class="flex-1">
          <WeatherWidget />
          <br>
          <p class="mb-3"><strong>Pour réserver votre sortie en kayak, vous avez besoin de votre numéro de réservation de chambre.
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
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import WeatherWidget from '../components/WeatherWidget.vue';
import PrefooterResa from '../components/PrefooterResa.vue';
import ReservationKayak from '../components/ReservationKayak.vue';
import { RouterLink } from 'vue-router'

</script>

<style scoped>

</style>
