<template>
  <div class="w-full py-24">
    <div class="max-w-7xl mx-auto px-4">
      <h1 class="text-4xl font-bold mb-8">Réservation de Visite du Bagne</h1>
      
      <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <img src="/carou-bagne.jpg" alt="Visite du Bagne" class="w-full h-64 object-cover">
        <div class="p-6">
          <h3 class="text-2xl font-semibold mb-2">Visite guidée historique</h3>
          <p class="text-gray-600 mb-4">Plongez dans l'histoire du bagne avec notre guide expérimenté.</p>
          <ul class="list-disc list-inside mb-4 text-gray-600">
            <li>Durée : environ 2 heures</li>
            <li>Départ : accueil du gîte</li>
            <li>Difficulté : facile, accessible à tous</li>
          </ul>
        </div>
      </div>
      
      <ReservationForm 
        title="Réservation de visite du bagne" 
        buttonText="Réserver ma visite"
        :maxPersonnes="15"
        type="bagne"
      />
    </div>
  </div>
</template>

<script setup>
import ReservationForm from '../components/ReservationForm.vue';
</script>
