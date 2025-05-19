<template>
  <div class="w-full py-24">
    <div class="max-w-7xl mx-auto px-4">
      <h1 class="text-4xl font-bold mb-8">Réservation de Garderie</h1>
      
      <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <img src="/carou-gard.jpg" alt="Garderie" class="w-full h-64 object-cover">
        <div class="p-6">
          <h3 class="text-2xl font-semibold mb-2">Garderie pour enfants</h3>
          <p class="text-gray-600 mb-4">Un espace sécurisé et amusant pour les enfants de tous âges.</p>
          <ul class="list-disc list-inside mb-4 text-gray-600">
            <li>Horaires : 8h à 18h</li>
            <li>Âges : de 3 à 12 ans</li>
            <li>Activités : jeux, activités manuelles, lectures</li>
          </ul>
        </div>
      </div>
      
      <ReservationForm 
        title="Réservation de garderie" 
        buttonText="Réserver une place"
        :maxPersonnes="5"
        type="garderie"
      >
        <template #additional-fields>
          <div>
            <label for="ageEnfant" class="block text-sm font-medium text-gray-700 mb-1">Âge de l'enfant</label>
            <input type="number" id="ageEnfant" v-model="ageEnfant" min="3" max="12" class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-[#FE8A24] focus:border-transparent" required>
          </div>
        </template>
      </ReservationForm>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import ReservationForm from '../components/ReservationForm.vue';

const ageEnfant = ref(3);
</script>
