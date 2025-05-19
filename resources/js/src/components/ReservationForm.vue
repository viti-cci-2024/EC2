<template>
  <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
    <h2 class="text-2xl font-semibold mb-4">{{ title }}</h2>
    <p class="mb-6 text-gray-600">
      Ce formulaire sera bientôt connecté au backend Laravel pour gérer les réservations.
    </p>
    
    <form @submit.prevent="submitForm" class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
          <input type="text" id="nom" v-model="form.nom" class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-[#FE8A24] focus:border-transparent" required>
        </div>
        
        <div>
          <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
          <input type="text" id="prenom" v-model="form.prenom" class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-[#FE8A24] focus:border-transparent" required>
        </div>
        
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input type="email" id="email" v-model="form.email" class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-[#FE8A24] focus:border-transparent" required>
        </div>
        
        <div>
          <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
          <input type="tel" id="telephone" v-model="form.telephone" class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-[#FE8A24] focus:border-transparent" required>
        </div>
        
        <div>
          <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
          <input type="date" id="date" v-model="form.date" class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-[#FE8A24] focus:border-transparent" required>
        </div>
        
        <div>
          <label for="heure" class="block text-sm font-medium text-gray-700 mb-1">Heure</label>
          <input type="time" id="heure" v-model="form.heure" class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-[#FE8A24] focus:border-transparent" required>
        </div>
        
        <div>
          <label for="nombrePersonnes" class="block text-sm font-medium text-gray-700 mb-1">Nombre de personnes</label>
          <input type="number" id="nombrePersonnes" v-model="form.nombrePersonnes" min="1" :max="maxPersonnes" class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-[#FE8A24] focus:border-transparent" required>
        </div>
        
        <slot name="additional-fields"></slot>
      </div>
      
      <div>
        <label for="commentaires" class="block text-sm font-medium text-gray-700 mb-1">Commentaires ou demandes spéciales</label>
        <textarea id="commentaires" v-model="form.commentaires" rows="4" class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-[#FE8A24] focus:border-transparent"></textarea>
      </div>
      
      <div class="flex justify-end">
        <button type="submit" class="px-6 py-3 bg-[#FE8A24] text-white font-semibold rounded-full hover:bg-[#09012B] transition-colors">
          {{ buttonText }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';

// Props pour personnaliser le formulaire
const props = defineProps({
  title: {
    type: String,
    default: 'Formulaire de réservation'
  },
  buttonText: {
    type: String,
    default: 'Réserver maintenant'
  },
  maxPersonnes: {
    type: Number,
    default: 10
  },
  type: {
    type: String,
    required: true
  }
});

// Formulaire de réservation
const form = ref({
  nom: '',
  prenom: '',
  email: '',
  telephone: '',
  date: '',
  heure: '',
  nombrePersonnes: 1,
  commentaires: '',
  type: props.type
});

// Fonction de soumission du formulaire
const submitForm = () => {
  // Cette fonction sera connectée au backend Laravel
  alert(`Le système de réservation pour ${props.type} sera bientôt connecté au backend Laravel.`);
  console.log('Données du formulaire:', form.value);
  
  // Réinitialiser le formulaire après soumission
  form.value = {
    nom: '',
    prenom: '',
    email: '',
    telephone: '',
    date: '',
    heure: '',
    nombrePersonnes: 1,
    commentaires: '',
    type: props.type
  };
};
</script>
