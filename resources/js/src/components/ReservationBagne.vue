<template>
    <div class="reservation-bagne p-4">
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
        Pour réserver la visite du bagne, vous avez besoin de votre numéro de réservation de chambre. Si vous n'en avez pas encore un, veuillez d'abord réserver votre chambre.
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
      
      <!-- Étape 2 : Formulaire de réservation de visite du bagne -->
      <div v-else-if="step === 2">
        <p class="mb-2">
          Votre chambre est réservée du 
          <strong>{{ roomReservation.startDate }}</strong> au 
          <strong>{{ roomReservation.endDate }}</strong>.
        </p>
        
        <!-- Choix de la date de visite -->
        <div class="mb-4">
          <label class="block text-sm font-medium" for="visitDate">Date de visite :</label>
          <input
            id="visitDate"
            v-model="visitDate"
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
    Prévision météo pour votre visite le {{ date }} :
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
        
        <!-- Choix du créneau horaire -->
        <div class="mb-4">
          <p class="font-medium mb-2">Sélectionnez un créneau horaire :</p>
          <select v-model="timeslot" class="border px-2 py-1 w-full">
            <option disabled value="">-- Choisissez un créneau --</option>
            <optgroup label="Matin">
              <option value="08h30-10h00">08h30 → 10h00</option>
              <option value="10h00-11h30">10h00 → 11h30</option>
            </optgroup>
            <optgroup label="Après-midi">
              <option value="14h00-15h30">14h00 → 15h30</option>
              <option value="15h30-17h00">15h30 → 17h00</option>
            </optgroup>
          </select>
        </div>
        
        <!-- Nombre de visiteurs -->
        <div class="mb-4">
          <label class="block text-sm font-medium" for="visitors">Nombre de visiteurs :</label>
          <input
            id="visitors"
            v-model.number="visitors"
            type="number"
            min="1"
            :max="10"
            class="border px-2 py-1 w-full"
            required
          />
          <p class="text-sm text-gray-600">
            Capacité maximale : 10 visiteurs par session.
          </p>
          <p class="text-sm text-gray-600">
            Places déjà réservées pour ce créneau : {{ currentVisitors }} - Restantes : {{ availablePlaces }}
          </p>
        </div>
        
        <button @click="submitBagneReservation" class="bg-green-500 text-white px-4 py-2 rounded">
          Réserver Visite du bagne
        </button>
        <p v-if="submitError" class="text-red-500 mt-2">{{ submitError }}</p>
        
        <!-- Toast -->
        <div v-if="toastMessage" class="fixed bottom-4 right-4 bg-black text-white px-4 py-2 rounded shadow-lg z-50">
          {{ toastMessage }}
        </div>
      </div>
      
      <!-- Étape 3 : Confirmation -->
      <div v-else-if="step === 3" id="confirmation" ref="confirmationRef" class="mt-4 p-4 border rounded shadow-md">
        <h3 class="text-xl font-bold mb-2">Votre réservation du bagne a été enregistrée !</h3>
        <p>
          <span class="font-medium">Numéro de chambre :</span>
          {{ roomReservationNumber }}
        </p>
        <p>
          <span class="font-medium">Date de visite :</span> {{ visitDate }}
        </p>
        <p>
          <span class="font-medium">Créneau :</span> {{ timeslot }}
        </p>
        <p>
          <span class="font-medium">Nombre de visiteurs :</span> {{ visitors }}
        </p>
        <p class="mt-2">
          Votre n° de réservation bagne est :
          <span class="font-bold text-green-500">{{ bagneConfirmation.numero }}</span>
        </p>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, nextTick, watch } from 'vue';
  import { useReservationStore } from '../stores/reservationStore';
  import { useBagneReservationStore } from '../stores/bagneReservationStore';
  
  const reservationStore = useReservationStore();
  reservationStore.init();
  
  const bagneStore = useBagneReservationStore();
  bagneStore.init();
  
  // Gestion des étapes
  const step = ref(1);
  
  // Étape 1 : Vérification du numéro de réservation de chambre
  const roomReservationNumber = ref('');
  const roomError = ref('');
  const roomReservation = ref(null);
  
  // Étape 2 : Informations de réservation bagne
  const visitDate = ref('');
  const timeslot = ref('');
  const visitors = ref(1);
  const submitError = ref('');
  const bagneConfirmation = ref(null);
  
  // Capacité maximale pour une visite
  const MAX_VISITORS = 10;
  
  // Calcul des visiteurs déjà réservés pour une date et créneau donnés
  const currentVisitors = computed(() => {
    return bagneStore.getBagneVisitorsForTimeslot(visitDate.value, timeslot.value);
  });
  
  const availablePlaces = computed(() => {
    return MAX_VISITORS - currentVisitors.value;
  });
  
  // Météo pour la date de visite
  const dateWeatherLoading = ref(false);
  const dateForecast = ref(null);
  const openWeatherApiKey = '69ec84f6c4da6ab8ba6745bca7421a99';
  const city = 'Poum';
  
  watch(visitDate, async (newDate) => {
    if (!newDate) {
      dateForecast.value = null;
      return;
    }
    dateWeatherLoading.value = true;
    try {
      const url = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${openWeatherApiKey}&units=metric&lang=fr`;
      const res = await fetch(url);
      if (!res.ok) throw new Error("Erreur de récupération météo pour la visite");
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
      console.error("Erreur météo bagne:", error);
      dateForecast.value = null;
    }
    dateWeatherLoading.value = false;
  });
  
  // Toast pour avertir (ex. si le nombre de visiteurs demandé dépasse la capacité)
  const toastMessage = ref('');
  function showToast(message) {
    console.log("Toast:", message);
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
  
  // Soumission finale de la réservation de visite du bagne
  function submitBagneReservation() {
    console.log("submitBagneReservation appelé");
    submitError.value = '';
    if (!roomReservationNumber.value.trim()) {
      submitError.value = "Veuillez renseigner votre numéro de réservation de chambre.";
      return;
    }
    if (!roomReservation.value) {
      submitError.value = "Numéro de chambre invalide.";
      return;
    }
    if (!visitDate.value) {
      submitError.value = "Veuillez sélectionner la date de visite.";
      return;
    }
    if (visitDate.value < roomReservation.value.startDate || visitDate.value > roomReservation.value.endDate) {
      submitError.value = "La date de visite doit être comprise entre les dates de réservation de la chambre.";
      return;
    }
    if (!timeslot.value) {
      submitError.value = "Veuillez sélectionner un créneau horaire.";
      return;
    }
    if (visitors.value < 1 || visitors.value > MAX_VISITORS) {
      submitError.value = "Le nombre de visiteurs doit être compris entre 1 et 10.";
      return;
    }
    if (visitors.value > availablePlaces.value) {
      submitError.value = `Il ne reste que ${availablePlaces.value} place(s) disponible(s) pour ce créneau.`;
      return;
    }
    
    const resData = {
      roomReservationNumber: roomReservationNumber.value,
      date: visitDate.value,
      timeslot: timeslot.value,
      visitors: visitors.value
    };
    console.log("Données de réservation bagne :", resData);
    const newRes = bagneStore.addReservation(resData);
    bagneConfirmation.value = newRes;
    step.value = 3;
    
    nextTick(() => {
      const el = document.getElementById('confirmation');
      if (el) {
        window.location.hash = '#confirmation';
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        console.log("Scroll vers la confirmation");
      }
    });
  }
  </script>
  
  <style scoped>
  /* Ajoutez ici vos styles complémentaires si nécessaire */
  </style>
  