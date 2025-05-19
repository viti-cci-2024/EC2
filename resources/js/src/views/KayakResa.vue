<template>
  <div class="w-full py-24">
    <div class="max-w-7xl mx-auto px-4">
      <h1 class="text-4xl font-bold mb-8">Réservation de Sortie en Kayak</h1>
      
      <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <img src="/carou-kayak.jpg" alt="Sortie en Kayak" class="w-full h-64 object-cover">
        <div class="p-6">
          <h3 class="text-2xl font-semibold mb-2">Excursion en kayak</h3>
          <p class="text-gray-600 mb-4">Une aventure nautique pour toute la famille.</p>
          <ul class="list-disc list-inside mb-4 text-gray-600">
            <li>Durée : 2h à 4h selon le circuit</li>
            <li>Départ : plage du gîte</li>
            <li>Difficulté : adaptée à votre niveau</li>
          </ul>
        </div>
      </div>
      
      <ReservationForm 
        title="Réservation de sortie en kayak" 
        buttonText="Réserver ma sortie"
        :maxPersonnes="12"
        type="kayak"
      />
    </div>
  </div>
</template>

<script setup>
import ReservationForm from '../components/ReservationForm.vue';
</script>
