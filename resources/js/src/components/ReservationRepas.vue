<template>
  <div class="reservation-repas p-4">
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
        Pour réserver votre repas, vous avez besoin de votre numéro de réservation de chambre. Si vous n'en avez pas encore un, veuillez d'abord réserver votre chambre.
      </div>
    </span>


        </p>
      <input
        v-model="roomReservationNumber"
        type="text"
        placeholder="Ex : CH25040013"
        class="border px-2 py-1 w-full"
      />
      <button
        @click="checkRoomReservation"
        class="bg-blue-500 text-white px-4 py-2 rounded mt-2"
      >
        Valider
      </button>
      <p v-if="roomError" class="text-red-500 mt-2">{{ roomError }}</p>
    </div>

    <!-- Étape 2 : Formulaire de réservation des repas -->
    <div v-else-if="step === 2">
      <p class="mb-2">
        Votre chambre est réservée du 
        <strong>{{ roomReservation.startDate }}</strong> au 
        <strong>{{ roomReservation.endDate }}</strong>.
      </p>

      <!-- Saisie de la date du repas (doit être entre les dates de la chambre) -->
      <div class="mb-4">
        <label class="block text-sm font-medium" for="serviceDate">
          Date du repas :
        </label>
        <input
          id="serviceDate"
          v-model="serviceDate"
          type="date"
          :min="roomReservation.startDate"
          :max="roomReservation.endDate"
          class="border px-2 py-1 w-full"
          required
        />
      </div>

<!-- Bloc météo pour le repas -->
<div
  v-if="serviceDate"
  class="mb-4 p-4 border rounded shadow-md"
>
  <p class="font-medium">
    Prévision météo pour votre repas le {{ serviceDate }} :
  </p>
  <div v-if="serviceWeatherLoading">
    Chargement de la météo...
  </div>
  <div v-else-if="serviceForecast">
    <p class="capitalize">{{ serviceForecast.description }}</p>
    <p class="text-2xl">{{ Math.round(serviceForecast.temp) }}°C</p>
  </div>
  <div v-else>
    Aucune donnée météo disponible pour cette date.
  </div>
</div>


      <!-- Choix du service (petit-déjeuner, déjeuner, dîner) -->
      <div class="mb-4">
        <p class="font-medium mb-2">Choisissez le service :</p>
        <div class="flex gap-4">
          <label>
            <input type="radio" value="Petit-déjeuner" v-model="serviceType" />
            Petit-déjeuner
          </label>
          <label>
            <input type="radio" value="Déjeuner" v-model="serviceType" />
            Déjeuner
          </label>
          <label>
            <input type="radio" value="Dîner" v-model="serviceType" />
            Dîner
          </label>
        </div>
      </div>

      <!-- Nombre de couverts -->
      <div class="mb-4">
        <p class="font-medium">Nombre de couverts :</p>
        <input
          type="number"
          v-model.number="covers"
          min="1"
          :max="maxCovers"
          class="border px-2 py-1"
          required
        />
        <p class="text-sm text-gray-600">
          Capacité maximale par service : 30 couverts. Disponibles :
          <strong>{{ availableCovers }}</strong>
        </p>
      </div>

      <button
        @click="submitMealReservation"
        class="bg-green-500 text-white px-4 py-2 rounded"
      >
        Réserver Repas
      </button>
      <p v-if="submitError" class="text-red-500 mt-2">{{ submitError }}</p>
    </div>

    <!-- Étape 3 : Confirmation de la réservation des repas -->
    <div
      v-else-if="step === 3"
      id="confirmation"
      ref="confirmationRef"
      class="mt-4 p-4 border rounded shadow-md"
    >
      <h3 class="text-xl font-bold mb-2">
        Votre réservation de repas a été enregistrée !
      </h3>
      <p>
        <span class="font-medium">Numéro de chambre :</span>
        {{ roomReservationNumber }}
      </p>
      <p>
        <span class="font-medium">Date du repas :</span> {{ serviceDate }}
      </p>
      <p>
        <span class="font-medium">Service :</span> {{ serviceType }}
      </p>
      <p>
        <span class="font-medium">Nombre de couverts :</span> {{ covers }}
      </p>
      <p class="mt-2">
        Votre n° de réservation repas est :
        <span class="font-bold text-green-500">{{ mealConfirmation.numero }}</span>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, watch } from 'vue';
import { useReservationStore } from '../stores/reservationStore';
import { useMealReservationStore } from '../stores/mealReservationStore';

const reservationStore = useReservationStore();
reservationStore.init();

const mealReservationStore = useMealReservationStore();
mealReservationStore.init();

// Gestion des étapes du formulaire
const step = ref(1);

// Champs du formulaire de réservation des repas
const roomReservationNumber = ref('');
const roomError = ref('');
const roomReservation = ref(null);
const serviceDate = ref('');
const serviceType = ref('');
const covers = ref(1);
const submitError = ref('');
const mealConfirmation = ref(null);

// Variables pour la météo liée à la date de repas
const serviceWeatherLoading = ref(false);
const serviceForecast = ref(null);
const openWeatherApiKey = '69ec84f6c4da6ab8ba6745bca7421a99';
const city = 'Poum';

// Capacité maximale de couverts par service
const maxCovers = 30;
const coversBooked = computed(() => {
  if (!serviceDate.value || !serviceType.value) return 0;
  return mealReservationStore.reservations
    .filter(mr => mr.serviceDate === serviceDate.value && mr.serviceType === serviceType.value)
    .reduce((acc, mr) => acc + mr.covers, 0);
});
const availableCovers = computed(() => maxCovers - coversBooked.value);

// Récupération de la météo pour la date de repas dès que serviceDate change
watch(serviceDate, async (newDate) => {
  if (!newDate) {
    serviceForecast.value = null;
    return;
  }
  serviceWeatherLoading.value = true;
  try {
    const url = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${openWeatherApiKey}&units=metric&lang=fr`;
    const res = await fetch(url);
    if (!res.ok) throw new Error("Erreur de récupération météo pour le repas");
    const data = await res.json();
    // Recherche de la prévision correspondant à newDate
    const forecastItem = data.list.find(item => item.dt_txt.startsWith(newDate));
    if (forecastItem) {
      serviceForecast.value = {
        temp: forecastItem.main.temp,
        description: forecastItem.weather[0].description,
      };
    } else {
      serviceForecast.value = null;
    }
  } catch (error) {
    console.error("Erreur lors de la récupération de la météo pour le repas:", error);
    serviceForecast.value = null;
  }
  serviceWeatherLoading.value = false;
});

// Vérification du numéro de réservation de chambre
const checkRoomReservation = () => {
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
};

// Soumission de la réservation des repas
const submitMealReservation = () => {
  submitError.value = '';
  if (!roomReservationNumber.value.trim()) {
    submitError.value = "Veuillez renseigner votre numéro de réservation de chambre.";
    return;
  }
  if (!roomReservation.value) {
    submitError.value = "Numéro de chambre invalide.";
    return;
  }
  if (!serviceDate.value) {
    submitError.value = "Veuillez sélectionner la date du repas.";
    return;
  }
  // Vérifier que la date du repas se situe dans l'intervalle de la réservation de chambre
  if (
    serviceDate.value < roomReservation.value.startDate ||
    serviceDate.value > roomReservation.value.endDate
  ) {
    submitError.value = "La date du repas doit être comprise entre les dates de réservation de la chambre.";
    return;
  }
  if (!serviceType.value) {
    submitError.value = "Veuillez sélectionner le service (Petit-déjeuner, Déjeuner ou Dîner).";
    return;
  }
  if (covers.value < 1 || covers.value > availableCovers.value) {
    submitError.value = "Le nombre de couverts demandé est invalide ou dépasse la disponibilité.";
    return;
  }
  const mealData = {
    roomReservationNumber: roomReservationNumber.value,
    serviceDate: serviceDate.value,
    serviceType: serviceType.value,
    covers: covers.value,
  };
  const newMealReservation = mealReservationStore.addReservation(mealData);
  mealConfirmation.value = { ...mealData, numero: newMealReservation.numero };
  step.value = 3;
  
  nextTick(() => {
    const el = document.getElementById('confirmation');
    if (el) {
      window.location.hash = '#confirmation';
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
};
</script>

<style scoped>
/* Ajoutez ici vos styles complémentaires si nécessaire */
</style>
