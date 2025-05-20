<template>
  <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
    <h2 class="text-2xl font-semibold mb-4">{{ title }}</h2>
    <div v-if="message" :class="['p-4 mb-6 rounded-md', messageClass]">
      {{ message }}
    </div>
    
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
          <label for="dateDebut" class="block text-sm font-medium text-gray-700 mb-1">Date d'arrivée</label>
          <input type="date" id="dateDebut" v-model="form.dateDebut" class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-[#FE8A24] focus:border-transparent" required>
        </div>
        
        <div>
          <label for="dateFin" class="block text-sm font-medium text-gray-700 mb-1">Date de départ</label>
          <input type="date" id="dateFin" v-model="form.dateFin" class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-[#FE8A24] focus:border-transparent" required>
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
        <button type="submit" class="px-6 py-3 bg-[#FE8A24] text-white font-semibold rounded-full hover:bg-[#09012B] transition-colors" :disabled="loading">
          <span v-if="loading">Traitement en cours...</span>
          <span v-else>{{ buttonText }}</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import ReservationService from '../services/ReservationService';

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

// État du formulaire
const loading = ref(false);
const message = ref('');
const success = ref(false);

// Classe CSS pour le message
const messageClass = computed(() => {
  return success.value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
});

// Formulaire de réservation
const form = ref({
  nom: '',
  prenom: '',
  email: '',
  telephone: '',
  dateDebut: '',
  dateFin: '',
  nombrePersonnes: 1,
  commentaires: '',
  type: props.type
});

// Fonction de soumission du formulaire
const submitForm = async () => {
  loading.value = true;
  message.value = '';
  
  try {
    // Création de la réservation
    await ReservationService.createReservation({
      client: {
        nom: form.value.nom,
        prenom: form.value.prenom,
        email: form.value.email,
        telephone: form.value.telephone
      },
      reservation: {
        date_debut: form.value.dateDebut,
        date_fin: form.value.dateFin,
        // Utiliser un ID d'utilisateur par défaut pour le moment (admin)
        cree_par: 1
      },
      // Ajouter les détails spécifiques en fonction du type de réservation
      [props.type]: {
        nb_personnes: form.value.nombrePersonnes,
        commentaire: form.value.commentaires
      }
    });
    
    success.value = true;
    message.value = 'Votre réservation a été enregistrée avec succès !';
    
    // Réinitialiser le formulaire après soumission
    form.value = {
      nom: '',
      prenom: '',
      email: '',
      telephone: '',
      dateDebut: '',
      dateFin: '',
      nombrePersonnes: 1,
      commentaires: '',
      type: props.type
    };
    
  } catch (error) {
    success.value = false;
    message.value = `Erreur lors de la réservation: ${error.response?.data?.message || error.message || 'Erreur inconnue'}`;
    console.error('Erreur de réservation:', error);
  } finally {
    loading.value = false;
  }
};
</script>
