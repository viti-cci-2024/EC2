<template>
  <div class="w-full py-24">
    <div class="max-w-7xl mx-auto px-4">
      <h1 class="text-4xl font-bold mb-8">Réservation de Table au Restaurant</h1>
      
      <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <img src="/carou-repas.jpg" alt="Restaurant" class="w-full h-64 object-cover">
        <div class="p-6">
          <h3 class="text-2xl font-semibold mb-2">Restaurant Pim</h3>
          <p class="text-gray-600 mb-4">Une expérience culinaire unique avec des produits frais et locaux.</p>
          <ul class="list-disc list-inside mb-4 text-gray-600">
            <li>Horaires : 12h-14h et 19h-22h</li>
            <li>Cuisine : locale et internationale</li>
            <li>Vue : terrasse avec vue sur le lagon</li>
          </ul>
        </div>
      </div>
      
      <ReservationForm 
        title="Réservation de table" 
        buttonText="Réserver ma table"
        :maxPersonnes="10"
        type="repas"
      >
        <template #additional-fields>
          <div>
            <label for="service" class="block text-sm font-medium text-gray-700 mb-1">Service</label>
            <select id="service" v-model="service" class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-[#FE8A24] focus:border-transparent" required>
              <option value="">Sélectionnez un service</option>
              <option value="midi">Déjeuner (12h-14h)</option>
              <option value="soir">Dîner (19h-22h)</option>
            </select>
          </div>
        </template>
      </ReservationForm>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import ReservationForm from '../components/ReservationForm.vue';

const service = ref('');
</script>
