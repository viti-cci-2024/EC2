<template>
  <div class="reservation-cheval p-4">
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
        Pour réserver la randonnée équestre, vous avez besoin de votre numéro de réservation de chambre. Si vous n'en avez pas encore un, veuillez d'abord réserver votre chambre.
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

    <!-- Étape 2 : Formulaire de réservation cheval -->
    <div v-else-if="step === 2">
      <p class="mb-2">
        Votre chambre est réservée du 
        <strong>{{ roomReservation.startDate }}</strong> au 
        <strong>{{ roomReservation.endDate }}</strong>.
      </p>

      <!-- Choix de la date de la randonnée -->
      <div class="mb-4">
        <label class="block text-sm font-medium" for="date">
          Date de la randonnée :
        </label>
        <input
          id="date"
          v-model="date"
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
    Prévision météo pour votre sortie le {{ date }} :
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
            <option v-for="slot in morningSlots" :key="slot" :value="slot">
              {{ slot }}
            </option>
          </optgroup>
          <optgroup label="Après-midi">
            <option v-for="slot in afternoonSlots" :key="slot" :value="slot">
              {{ slot }}
            </option>
          </optgroup>
        </select>
      </div>

      <!-- Nombre de participants (max 8) -->
      <div class="mb-4">
        <p class="font-medium">Nombre de participants :</p>
        <input
          type="number"
          v-model.number="participants"
          min="1"
          :max="8"
          class="border px-2 py-1"
          required
        />
        <p class="text-sm text-gray-600">
          Groupes limités à 8 personnes.
        </p>
      </div>

      <!-- Sélection des chevaux via thumbnails -->
      <div v-if="date && timeslot" class="mb-4 p-4 border rounded shadow-md">
        <p class="font-medium mb-2">
          Choisissez {{ participants }} compagnon(s) de route parmi ceux disponibles :
        </p>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
          <div
            v-for="horse in filteredHorses"
            :key="horse"
            class="relative cursor-pointer border rounded p-2 flex flex-col items-center justify-center hover:scale-105 transition-transform"
            :class="{
              'opacity-50 cursor-not-allowed': !isHorseAvailable(horse),
              'border-2 border-green-500': selectedHorses.includes(horse)
            }"
            @click="toggleHorseSelection(horse)"
          >
            <img
              :src="getHorseImage(horse)"
              alt=""
              class="w-24 h-24 object-cover"
            />
            <p class="mt-2 font-medium text-center">{{ horse }}</p>
          </div>
        </div>
        <p class="text-sm text-gray-600 mt-2">
          Chevaux déjà attribués aux moniteurs : {{ moniteurHorses.join(', ') }}<br />
          (Ces chevaux ne sont pas disponibles pour vous).
        </p>
      </div>

      <button
        @click="submitHorseReservation"
        class="bg-green-500 text-white px-4 py-2 rounded mt-4"
      >
        Réserver Randonnée
      </button>
      <p v-if="submitError" class="text-red-500 mt-2">{{ submitError }}</p>

      <!-- Toast pour avertir si le nombre maximum de chevaux sélectionnables est dépassé -->
      <div v-if="toastMessage" class="fixed bottom-20 left-4 bg-orange-500 text-white px-6 py-3 text-lg font-bold rounded shadow-lg z-50">
        {{ toastMessage }}
      </div>
    </div>

    <!-- Étape 3 : Confirmation de la réservation -->
    <div
      v-else-if="step === 3"
      id="confirmation"
      ref="confirmationRef"
      class="mt-4 p-4 border rounded shadow-md"
    >
      <h3 class="text-xl font-bold mb-2">
        Votre réservation de randonnée a été enregistrée !
      </h3>
      <p>
        <span class="font-medium">Numéro de chambre :</span>
        {{ roomReservationNumber }}
      </p>
      <p>
        <span class="font-medium">Date :</span> {{ date }}
      </p>
      <p>
        <span class="font-medium">Créneau :</span> {{ timeslot }}
      </p>
      <p>
        <span class="font-medium">Participants :</span> {{ participants }}
      </p>
      <p>
        <span class="font-medium">Chevaux sélectionnés :</span>
        {{ selectedHorses.join(', ') }}
      </p>
      <p class="mt-2">
        Votre n° de réservation cheval est :
        <span class="font-bold text-green-500">{{ horseConfirmation.numero }}</span>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, watch } from 'vue';
import { useReservationStore } from '../stores/reservationStore';
import { useHorseReservationStore } from '../stores/horseReservationStore';

const reservationStore = useReservationStore();
reservationStore.init();

const horseStore = useHorseReservationStore();
horseStore.init();

// Gestion des étapes
const step = ref(1);

// Étape 1 : Numéro de réservation de chambre
const roomReservationNumber = ref('');
const roomError = ref('');
const roomReservation = ref(null);

// Étape 2 : Champs pour la réservation de randonnée
const date = ref('');
const timeslot = ref('');
const participants = ref(1);
const selectedHorses = ref([]);
const submitError = ref('');
const horseConfirmation = ref(null);

// Créneaux horaires
const morningSlots = ['8h - 9h', '9h - 10h', '10h - 11h'];
const afternoonSlots = ['14h - 15h', '15h - 16h', '16h - 17h'];

// Gestion des chevaux moniteurs : 2 chevaux choisis aléatoirement
const MONITEUR_HORSES = ref([]);
function generateMoniteurHorses() {
  const shuffled = [...horseStore.horses].sort(() => 0.5 - Math.random());
  MONITEUR_HORSES.value = shuffled.slice(0, 2);
}
generateMoniteurHorses();
const moniteurHorses = computed(() => MONITEUR_HORSES.value);

// ===== Météo pour la date de la randonnée =====
const dateWeatherLoading = ref(false);
const dateForecast = ref(null);
const openWeatherApiKey = '69ec84f6c4da6ab8ba6745bca7421a99';
const city = 'Poum';

watch(date, async (newDate) => {
  if (!newDate) {
    dateForecast.value = null;
    return;
  }
  dateWeatherLoading.value = true;
  try {
    const url = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${openWeatherApiKey}&units=metric&lang=fr`;
    const res = await fetch(url);
    if (!res.ok) throw new Error("Erreur de récupération météo de la randonnée");
    const data = await res.json();
    const forecastItem = data.list.find(item => item.dt_txt.startsWith(newDate));
    if (forecastItem) {
      dateForecast.value = {
        temp: forecastItem.main.temp,
        description: forecastItem.weather[0].description,
      };
    } else {
      dateForecast.value = null;
    }
  } catch (error) {
    console.error("Erreur lors de la récupération de la météo pour la randonnée:", error);
    dateForecast.value = null;
  }
  dateWeatherLoading.value = false;
});

// ===== Toast pour avertir si le nombre max de chevaux sélectionnables est dépassé =====
const toastMessage = ref('');
function showToast(message) {
  toastMessage.value = message;
  setTimeout(() => {
    toastMessage.value = '';
  }, 4000);
}

// Watch sur participants pour détecter si la valeur devient inférieure au nombre de chevaux déjà sélectionnés
watch(participants, (newVal) => {
  if (newVal < selectedHorses.value.length) {
    showToast("Le nombre de participants a été réduit. Veuillez retirer des chevaux sélectionnés.");
  }
});

// Mapping pour les images : noms affichés (avec accents) → noms de fichiers (sans accents)
const horseFileMap = {
  'Comète': 'Comete.jpg',
  'Éclair': 'Eclair.jpg',
  'Koné': 'Kone.jpg',
  'Inanouï': 'Iananoui.jpg',
  'Paillette': 'Paillette.jpg',
  'Nidguep': 'Nidguep.jpg',
  'Tim': 'Tim.jpg'
  // Ajoutez d'autres correspondances si nécessaire
};

function getHorseImage(horseName) {
  if (horseFileMap[horseName]) {
    return `/cheval/${horseFileMap[horseName]}`;
  }
  const normalized = horseName.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
  return `/cheval/${normalized}.jpg`;
}

// Filtrer les chevaux disponibles (exclure moniteurs et déjà utilisés pour ce créneau)
const filteredHorses = computed(() => {
  if (!date.value || !timeslot.value) {
    return horseStore.horses.filter(h => !moniteurHorses.value.includes(h));
  }
  const used = horseStore.getHorsesForTimeslot(date.value, timeslot.value);
  return horseStore.horses.filter(
    h => !moniteurHorses.value.includes(h) && !used.includes(h)
  );
});

// Vérifier la disponibilité d'un cheval
function isHorseAvailable(horseName) {
  return filteredHorses.value.includes(horseName);
}

// Sélection/désélection d'un cheval avec toast si dépassement
function toggleHorseSelection(horseName) {
  if (!isHorseAvailable(horseName)) return;
  if (!selectedHorses.value.includes(horseName)) {
    if (selectedHorses.value.length >= participants.value) {
      showToast("Vous avez atteint le nombre maximum de chevaux sélectionnables.");
      return;
    }
    selectedHorses.value.push(horseName);
  } else {
    selectedHorses.value = selectedHorses.value.filter(h => h !== horseName);
  }
}

// Étape 1 : Vérification du numéro de réservation de chambre
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

// Soumission de la réservation de randonnée
function submitHorseReservation() {
  submitError.value = '';
  if (!roomReservationNumber.value.trim()) {
    submitError.value = "Veuillez renseigner votre numéro de réservation de chambre.";
    return;
  }
  if (!roomReservation.value) {
    submitError.value = "Numéro de chambre invalide.";
    return;
  }
  if (!date.value) {
    submitError.value = "Veuillez sélectionner la date de la randonnée.";
    return;
  }
  if (date.value < roomReservation.value.startDate || date.value > roomReservation.value.endDate) {
    submitError.value = "La date doit être comprise entre les dates de réservation de la chambre.";
    return;
  }
  if (!timeslot.value) {
    submitError.value = "Veuillez sélectionner un créneau horaire.";
    return;
  }
  if (participants.value < 1 || participants.value > 8) {
    submitError.value = "Le nombre de participants doit être compris entre 1 et 8.";
    return;
  }
  if (selectedHorses.value.length !== participants.value) {
    submitError.value = "Le nombre de chevaux sélectionnés doit correspondre au nombre de participants.";
    return;
  }
  const resData = {
    roomReservationNumber: roomReservationNumber.value,
    date: date.value,
    timeslot: timeslot.value,
    participants: participants.value,
    selectedHorses: [...selectedHorses.value]
  };
  const newRes = horseStore.addReservation(resData);
  horseConfirmation.value = newRes;
  step.value = 3;

  nextTick(() => {
    const el = document.getElementById('confirmation');
    if (el) {
      window.location.hash = '#confirmation';
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
}
</script>

<style scoped>
/* Les effets de hover, de grossissement et de grisage sont gérés via Tailwind dans le template */
</style>
