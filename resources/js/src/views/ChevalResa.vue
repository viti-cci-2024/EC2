<template>
  <div class="w-full py-24">
    <div class="max-w-7xl mx-auto px-4">
      <h1 class="text-4xl font-bold mb-8">Réservation de Randonnée Équestre</h1>
      
      <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <img src="/carou-cheval.jpg" alt="Randonnée Équestre" class="w-full h-64 object-cover">
        <div class="p-6">
          <h3 class="text-2xl font-semibold mb-2">Balade à cheval</h3>
          <p class="text-gray-600 mb-4">Une expérience inoubliable pour tous les niveaux.</p>
          <ul class="list-disc list-inside mb-4 text-gray-600">
            <li>Durée : 1h30 à 3h selon le circuit</li>
            <li>Départ : écurie du gîte</li>
            <li>Difficulté : adaptée à votre niveau</li>
          </ul>
        </div>
      </div>
      
      <ReservationForm 
        title="Réservation de randonnée équestre" 
        buttonText="Réserver ma balade"
        :maxPersonnes="8"
        type="cheval"
      />
    </div>
  </div>
</template>

<script setup>
import ReservationForm from '../components/ReservationForm.vue';
</script>
