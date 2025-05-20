<template>
  <!------------------------- NEW HERO RESA CHAMBRE ------------------------------>
  <section class="w-full h-[50vh] md:h-[30vh] bg-cover bg-center" :style="{ backgroundImage: 'url(/chambre/hero-chambre.jpg)' }">
    <!-- Conteneur pour centrer verticalement le contenu avec padding -->
    <div class="max-w-7xl mx-auto flex flex-col justify-center items-start h-full p-5">
      <!-- Contenu texte en blanc aligné à gauche -->
      <div class="text-white text-left">
        <h1 class="mb-3 text-shadow text-4xl font-bold">Un beau séjour vous attend</h1>
        <h4 class="mb-3 text-shadow text-2xl">
          Planifiez votre venue dès à présent.
        </h4>
      </div>
    </div>
  </section>

  <!------------------------- FORMULAIRE RESA CHAMBRE ------------------------------>

  <section class="py-10 px-4 bg-white mt-10">
    <div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold mb-4">Réservation de Chambre</h1>
    <div class="flex flex-col md:flex-row gap-4">
      <!-- Formulaire de réservation pour les chambres -->
      <div class="flex-1">
        <ReservationForm reservationType="Chambre" />
      </div>
        <!-- Widget météo affichant les infos pour la ville de Poum -->
        <div class="flex-1">
          <WeatherWidget />
          <br>
          <p class="mb-3"><strong><span class="text-orange-500">Important :</span> Pour réserver une activité, vous aurez besoin de votre numéro de réservation de chambre.
              Pensez à bien noter ce numéro qui vous sera donné après la validation du formulaire</strong></p>

        </div>
    </div>
  </div>
  </section>

  <PrefooterResa />


</template>

<script setup>
import PrefooterResa from '../components/PrefooterResa.vue';
import ReservationForm from '../components/ReservationForm.vue';
import WeatherWidget from '../components/WeatherWidget.vue';


</script>


<style scoped>


</style>
