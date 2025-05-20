<!-- ReservationGarderie.vue -->
<template>
    <div class="reservation-garderie p-4">
      <h2 class="text-2xl font-bold mb-4">Veuillez compléter ce formulaire</h2>
      
      <!-- Étape 1 : Vérification du numéro de réservation de chambre -->
      <div v-if="step === 1">
        <p class="mb-2 font-medium">Entrez votre numéro de réservation de chambre :

<!-- Icône d'information avec tooltip en CSS uniquement -->
      <!-- Icône "i" -->
      <span class="relative group ml-2 flex items-center justify-center w-6 h-6 text-blue-500 border border-blue-500 rounded-full cursor-pointer">
        i
      
      <!-- Tooltip : affichage en mobile et desktop -->
      <div class="absolute bottom-full left-1 mb-2 hidden group-hover:block group-focus:block bg-gray-700 text-white text-lg md:text-base rounded py-2 px-3 w-85 z-10">
        Pour réserver la garderie, vous avez besoin de votre numéro de réservation de chambre. Si vous n'en avez pas encore un, veuillez d'abord réserver votre chambre.
      </div>
    </span>


        </p>
        <input
          v-model="roomReservationNumber"
          type="text"
          placeholder="Ex : CH25040013"
          class="border px-2 py-1 w-full"
        />
        <button @click="checkRoomReservation" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">
          Valider
        </button>
        <p v-if="roomError" class="text-red-500 mt-2">{{ roomError }}</p>
      </div>
      
      <!-- Étape 2 : Formulaire de réservation de garderie -->
      <div v-else-if="step === 2">
        <p class="mb-2">
          Votre chambre est réservée du <strong>{{ roomReservation.startDate }}</strong> au <strong>{{ roomReservation.endDate }}</strong>.
        </p>
        
        <!-- Choix de la date de garderie -->
        <div class="mb-4">
          <label class="block text-sm font-medium" for="garderieDate">Date de garderie :</label>
          <input
            id="garderieDate"
            v-model="garderieDate"
            type="date"
            :min="roomReservation.startDate"
            :max="roomReservation.endDate"
            class="border px-2 py-1 w-full"
            required
          />
        </div>
        
<!-- Bloc météo pour la date de la randonnée -->
<div v-if="date" class="mb-4 p-4 border rounded shadow-md">
  <p class="font-medium">
    Prévision météo pour le {{ date }} :
  </p>
  <div v-if="dateWeatherLoading">
    Chargement de la météo...
  </div>
  <div v-else-if="dateForecast">
    <p class="capitalize">{{ dateForecast.description }}</p>
    <p class="text-2xl">{{ Math.round(dateForecast.temp) }}°C</p>
  </div>
  <div v-else>
    Aucune donnée météo disponible pour cette date.
  </div>
</div>
        
        <!-- Sélection de l'horaire de garderie -->
        <div class="mb-4">
          <!-- Texte informatif -->
          <p class="text-sm text-gray-600 mb-2">
            Veuillez sélectionner uniquement des heures pleines. La réservation ne peut être faite que pour une durée maximale de 4 heures consécutives.
          </p>
          <label class="block text-sm font-medium" for="startTime">Heure de début :</label>
          <select id="startTime" v-model="startTime" class="border px-2 py-1 w-full" required>
            <option disabled value="">-- Choisissez une heure de début --</option>
            <option v-for="time in availableStartTimes" :key="time" :value="time">{{ time }}</option>
          </select>
        </div>
        
        <div class="mb-4">
          <label class="block text-sm font-medium" for="endTime">Heure de fin :</label>
          <select id="endTime" v-model="endTime" class="border px-2 py-1 w-full" required>
            <option disabled value="">-- Choisissez une heure de fin --</option>
            <option v-for="time in availableEndTimes" :key="time" :value="time">{{ time }}</option>
          </select>
          <p class="text-sm text-gray-600">
            Durée minimale : 1h, maximale : 4h.
          </p>
        </div>
        
        <!-- Nombre d'enfants à inscrire -->
        <div class="mb-4">
          <label class="block text-sm font-medium" for="children">Nombre d'enfants :</label>
          <input
            id="children"
            v-model.number="children"
            type="number"
            min="1"
            :max="15"
            class="border px-2 py-1 w-full"
            required
          />
          <p class="text-sm text-gray-600">
            Capacité maximale : 15 enfants simultanés.
          </p>
          <p class="text-sm text-gray-600">
            Enfants déjà inscrits pour ce créneau : {{ currentChildren }} - Places restantes : {{ availablePlaces }}
          </p>
        </div>
        
        <button @click="submitGarderieReservation" class="bg-green-500 text-white px-4 py-2 rounded">
          Réserver la garderie
        </button>
        <p v-if="submitError" class="text-red-500 mt-2">{{ submitError }}</p>
        
        <!-- Toast pour avertir l'utilisateur -->
        <div v-if="toastMessage" class="fixed bottom-4 right-4 bg-black text-white px-4 py-2 rounded shadow-lg z-50">
          {{ toastMessage }}
        </div>
      </div>
      
      <!-- Étape 3 : Confirmation -->
      <div v-else-if="step === 3" id="confirmation" ref="confirmationRef" class="mt-4 p-4 border rounded shadow-md">
        <h3 class="text-xl font-bold mb-2">Votre réservation en garderie a été enregistrée !</h3>
        <p>
          <span class="font-medium">Numéro de chambre :</span> {{ roomReservationNumber }}
        </p>
        <p>
          <span class="font-medium">Date :</span> {{ garderieDate }}
        </p>
        <p>
          <span class="font-medium">Horaire :</span> de {{ startTime }} à {{ endTime }}
        </p>
        <p>
          <span class="font-medium">Nombre d'enfants :</span> {{ children }}
        </p>
        <p class="mt-2">
          Votre numéro de réservation en garderie est :
          <span class="font-bold text-green-500">{{ garderieConfirmation.numero }}</span>
        </p>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, nextTick, watch } from 'vue';
  import { useReservationStore } from '../stores/reservationStore';
  import { useGarderieReservationStore } from '../stores/garderieReservationStore';
  
  const reservationStore = useReservationStore();
  reservationStore.init();
  
  const garderieStore = useGarderieReservationStore();
  garderieStore.init();
  
  // Gestion des étapes
  const step = ref(1);
  
  // Étape 1 : Vérification du numéro de réservation de chambre
  const roomReservationNumber = ref('');
  const roomError = ref('');
  const roomReservation = ref(null);
  
  // Étape 2 : Informations de réservation de garderie
  const garderieDate = ref('');
  const startTime = ref('');
  const endTime = ref('');
  const children = ref(1);
  const submitError = ref('');
  const garderieConfirmation = ref(null);
  
  // Limite d'enfants simultanés
  const MAX_CHILDREN = 15;
  
  // Génération de la liste des heures pleines de 00:00 à 23:00
  const allHours = Array.from({ length: 24 }, (_, i) => (i < 10 ? '0' : '') + i + ":00");
  
  const availableStartTimes = computed(() => {
    // Vous pouvez limiter la plage des heures de départ si besoin,
    // par exemple en retournant uniquement les heures entre 08:00 et 20:00
    return allHours;
  });
  
  const availableEndTimes = computed(() => {
    if (!startTime.value) return [];
    const startIndex = allHours.indexOf(startTime.value);
    // Pour l'heure de fin : au moins 1 heure après et au maximum 4 heures après le début
    return allHours.filter((time, idx) => idx > startIndex && idx <= startIndex + 4);
  });
  
  // Calcul du nombre d'enfants déjà réservés pour le créneau donné
  const currentChildren = computed(() => {
    if (!garderieDate.value || !startTime.value || !endTime.value) return 0;
    return garderieStore.getGarderieChildrenForTimeRange(garderieDate.value, startTime.value, endTime.value);
  });
  
  const availablePlaces = computed(() => {
    return MAX_CHILDREN - currentChildren.value;
  });
  
  // Gestion de la météo pour la date de garderie
  const dateWeatherLoading = ref(false);
  const dateForecast = ref(null);
  const openWeatherApiKey = '69ec84f6c4da6ab8ba6745bca7421a99';
  const city = 'Poum';
  
  watch(garderieDate, async (newDate) => {
    if (!newDate) {
      dateForecast.value = null;
      return;
    }
    dateWeatherLoading.value = true;
    try {
      const url = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${openWeatherApiKey}&units=metric&lang=fr`;
      const res = await fetch(url);
      if (!res.ok) throw new Error("Erreur de récupération météo");
      const data = await res.json();
      const forecastItem = data.list.find(item => item.dt_txt.startsWith(newDate));
      if (forecastItem) {
        dateForecast.value = {
          temp: forecastItem.main.temp,
          description: forecastItem.weather[0].description
        };
      } else {
        dateForecast.value = null;
      }
    } catch (error) {
      console.error("Erreur météo garderie:", error);
      dateForecast.value = null;
    }
    dateWeatherLoading.value = false;
  });
  
  // Toast pour avertir l'utilisateur
  const toastMessage = ref('');
  function showToast(message) {
    toastMessage.value = message;
    setTimeout(() => {
      toastMessage.value = '';
    }, 3000);
  }
  
  // Vérification du numéro de réservation de chambre (Étape 1)
  function checkRoomReservation() {
    roomError.value = '';
    const found = reservationStore.reservations.find(
      r => r.numero === roomReservationNumber.value
    );
    if (!found) {
      roomError.value = "Numéro de chambre invalide ou non trouvé.";
      return;
    }
    roomReservation.value = found;
    step.value = 2;
  }
  
  // Soumission finale de la réservation de garderie (Étape 2)
  function submitGarderieReservation() {
    submitError.value = '';
    // Vérifications de base
    if (!roomReservationNumber.value.trim()) {
      submitError.value = "Veuillez renseigner votre numéro de réservation de chambre.";
      return;
    }
    if (!roomReservation.value) {
      submitError.value = "Numéro de chambre invalide.";
      return;
    }
    if (!garderieDate.value) {
      submitError.value = "Veuillez sélectionner la date de garderie.";
      return;
    }
    if (garderieDate.value < roomReservation.value.startDate || garderieDate.value > roomReservation.value.endDate) {
      submitError.value = "La date de garderie doit être comprise entre les dates de réservation de la chambre.";
      return;
    }
    if (!startTime.value || !endTime.value) {
      submitError.value = "Veuillez sélectionner l'heure de début et l'heure de fin.";
      return;
    }
    
    // Conversion des heures en minutes pour valider la durée
    const startMinutes = convertTimeToMinutes(startTime.value);
    const endMinutes = convertTimeToMinutes(endTime.value);
    const duration = endMinutes - startMinutes;
    if (duration < 60) {
      submitError.value = "La durée minimale de la garderie est d'une heure.";
      return;
    }
    if (duration > 240) {
      submitError.value = "La durée maximale de la garderie est de 4 heures.";
      return;
    }
    if (children.value < 1 || children.value > MAX_CHILDREN) {
      submitError.value = "Le nombre d'enfants doit être compris entre 1 et 15.";
      return;
    }
    // Vérification de la capacité restante
    if (children.value > availablePlaces.value) {
      submitError.value = `Il ne reste que ${availablePlaces.value} place(s) disponible(s) pour ce créneau.`;
      return;
    }
    
    const resData = {
      roomReservationNumber: roomReservationNumber.value,
      date: garderieDate.value,
      startTime: startTime.value,
      endTime: endTime.value,
      children: children.value
    };
    const newRes = garderieStore.addReservation(resData);
    garderieConfirmation.value = newRes;
    step.value = 3;
    
    nextTick(() => {
      const el = document.getElementById('confirmation');
      if (el) {
        window.location.hash = '#confirmation';
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  }
  
  // Utilitaire pour convertir "HH:MM" en minutes
  function convertTimeToMinutes(time) {
    const [hours, minutes] = time.split(':').map(Number);
    return hours * 60 + minutes;
  }
  </script>
  
  <style scoped>
  /* Ajoutez ici vos styles complémentaires si nécessaire */
  </style>
  