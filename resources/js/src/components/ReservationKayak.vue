<template>
  <div class="reservation-kayak p-4">
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
        Pour réserver la sortie kayak, vous avez besoin de votre numéro de réservation de chambre. Si vous n'en avez pas encore un, veuillez d'abord réserver votre chambre.
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
    
    <!-- Étape 2 : Formulaire de réservation de kayak -->
    <div v-else-if="step === 2">
      <p class="mb-2">
        Votre chambre est réservée du 
        <strong>{{ roomReservation.startDate }}</strong> au 
        <strong>{{ roomReservation.endDate }}</strong>.
      </p>
      
      <!-- Choix de la date de l'activité -->
      <div class="mb-4">
        <label class="block text-sm font-medium" for="date">Date de l'activité :</label>
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
    Prévision météo pour votre sortie kayak le {{ date }} :
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
            <option v-for="(slot, index) in morningSlots" :key="`morning-${index}`" :value="slot">
              {{ slot }}
            </option>
          </optgroup>
          <optgroup label="Après-midi">
            <option v-for="(slot, index) in afternoonSlots" :key="`afternoon-${index}`" :value="slot">
              {{ slot }}
            </option>
          </optgroup>
        </select>
      </div>
      
      <!-- Nombre de participants (max 8) -->
      <div class="mb-4">
        <p class="font-medium">Nombre de participants :</p>
        <input type="number" v-model.number="participants" min="1" :max="8" class="border px-2 py-1" required />
        <p class="text-sm text-gray-600">Groupes limités à 8 personnes.</p>
      </div>
      
      <!-- Cascade d'allocation des kayaks -->
      <div v-if="participants > 0" class="mb-4 p-4 border rounded shadow-md">
        <p class="font-medium">
          Allocation de kayaks - Il reste {{ remainingParticipants }} place(s) à répartir.
        </p>
        <div v-if="remainingParticipants > 0">
          <p class="mb-2">
            Sélectionnez un type de kayak pour couvrir une partie de {{ remainingParticipants }} place(s) :
          </p>
          <div class="flex gap-4">
            <!-- Option double -->
            <label v-if="remainingParticipants >= 2 && availableDoubleLocal > 0">
              <input type="radio" v-model="currentSelection" value="double" />
              Kayak double (2 places, disponibles : {{ availableDoubleLocal }})
            </label>
            <!-- Option simple -->
            <label v-if="availableSimpleLocal > 0">
              <input type="radio" v-model="currentSelection" value="simple" />
              Kayak simple (1 place, disponibles : {{ availableSimpleLocal }})
            </label>
          </div>
          <button @click="confirmSelection" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">
            Ajouter ce choix
          </button>
          <p v-if="allocationError" class="text-red-500 mt-2">{{ allocationError }}</p>
        </div>
        <div v-else>
          <p class="font-medium">Allocation complète :</p>
          <ul class="list-disc pl-4">
            <li v-for="(choice, index) in allocationSelections" :key="index">
              {{ choice === 'double' ? 'Kayak double' : 'Kayak simple' }}
            </li>
          </ul>
        </div>
      </div>
      
      <!-- Bouton de soumission, uniquement si allocation complète -->
      <button v-if="remainingParticipants === 0" @click="submitKayakReservation"
        class="bg-green-500 text-white px-4 py-2 rounded mt-4">
        Réserver Kayak
      </button>
      <p v-if="submitError" class="text-red-500 mt-2">{{ submitError }}</p>
      
      <!-- Toast pour avertir en cas de dépassement de sélection -->
      <div v-if="toastMessage" class="fixed bottom-4 right-4 bg-black text-white px-4 py-2 rounded shadow-lg z-50">
        {{ toastMessage }}
      </div>
    </div>
    
    <!-- Étape 3 : Confirmation de la réservation -->
    <div v-else-if="step === 3" id="confirmation" ref="confirmationRef" class="mt-4 p-4 border rounded shadow-md">
      <h3 class="text-xl font-bold mb-2">Votre réservation kayak a été enregistrée !</h3>
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
        <span class="font-medium">Répartition de kayaks :</span>
        <span v-for="(choice, index) in allocationSelections" :key="index">
          {{ choice === 'double' ? 'Kayak double' : 'Kayak simple' }}{{ index < allocationSelections.length - 1 ? ', ' : '' }}
        </span>
      </p>
      <p class="mt-2">
        Votre n° de réservation kayak est :
        <span class="font-bold text-green-500">{{ kayakConfirmation.numero }}</span>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, watch } from 'vue';
import { useReservationStore } from '../stores/reservationStore';
import { useKayakReservationStore } from '../stores/kayakReservationStore';

const reservationStore = useReservationStore();
reservationStore.init();

const kayakStore = useKayakReservationStore();
kayakStore.init();

// Gestion des étapes
const step = ref(1);

// Étape 1 : Numéro de réservation de chambre
const roomReservationNumber = ref('');
const roomError = ref('');
const roomReservation = ref(null);

// Étape 2 : Informations de réservation
const date = ref('');
const timeslot = ref('');
const morningSlots = ['8h - 9h', '9h - 10h', '10h - 11h'];
const afternoonSlots = ['14h - 15h', '15h - 16h', '16h - 17h'];
const participants = ref(1);

// IMPORTANT : Déclaration de submitError et kayakConfirmation (elles étaient manquantes)
const submitError = ref('');
const kayakConfirmation = ref(null);

// Cascade d'allocation des kayaks
const allocationSelections = ref([]); // ex: ['double', 'simple', ...]
const currentSelection = ref('');      // choix temporaire dans la cascade
const allocationError = ref('');

// Calculs pour la cascade
const selectedDoubleCount = computed(() => allocationSelections.value.filter(x => x === 'double').length);
const selectedSimpleCount = computed(() => allocationSelections.value.filter(x => x === 'simple').length);
const remainingParticipants = computed(() => {
  return participants.value - (selectedDoubleCount.value * 2 + selectedSimpleCount.value);
});

// Capacité globale par créneau (définies par le système)
// 3 kayaks doubles et 2 kayaks simples au total
const reservedDouble = computed(() => {
  const existing = kayakStore.getKayakReservationsForTimeslot(date.value, timeslot.value);
  return existing.filter(r => r.kayakAllocation && r.kayakAllocation.includes('double')).length;
});
const reservedSimple = computed(() => {
  const existing = kayakStore.getKayakReservationsForTimeslot(date.value, timeslot.value);
  return existing.filter(r => r.kayakAllocation && r.kayakAllocation.includes('simple')).length;
});
const availableDouble = computed(() => Math.max(3 - reservedDouble.value, 0));
const availableSimple = computed(() => Math.max(2 - reservedSimple.value, 0));
const availableDoubleLocal = computed(() => Math.max(availableDouble.value - selectedDoubleCount.value, 0));
const availableSimpleLocal = computed(() => Math.max(availableSimple.value - selectedSimpleCount.value, 0));

// Météo pour la date de l'activité
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
    if (!res.ok) throw new Error("Erreur de récupération météo pour l'activité");
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
    console.error("Erreur météo:", error);
    dateForecast.value = null;
  }
  dateWeatherLoading.value = false;
});

// Toast pour avertir de dépassement de sélection
const toastMessage = ref('');
function showToast(message) {
  console.log("Toast:", message);
  toastMessage.value = message;
  setTimeout(() => {
    toastMessage.value = '';
  }, 3000);
}

// Watch sur participants pour avertir si la sélection dépasse
watch(participants, (newVal) => {
  if (newVal < (selectedDoubleCount.value * 2 + selectedSimpleCount.value)) {
    showToast("Le nombre de participants a été réduit. Veuillez retirer des sélections.");
  }
});

// ===== Gestion de la cascade d'allocation =====
function confirmSelection() {
  allocationError.value = '';
  if (!currentSelection.value) {
    showToast("Veuillez sélectionner un type de kayak pour cette allocation.");
    return;
  }
  if (currentSelection.value === 'double') {
    if (remainingParticipants.value < 2) {
      showToast("Pour un kayak double, au moins 2 participants sont nécessaires.");
      return;
    }
    if (availableDoubleLocal.value < 1) {
      showToast("Plus aucun kayak double disponible pour ce créneau.");
      return;
    }
  } else if (currentSelection.value === 'simple') {
    if (availableSimpleLocal.value < 1) {
      showToast("Plus aucun kayak simple disponible pour ce créneau.");
      return;
    }
  }
  console.log("Allocation confirmée:", currentSelection.value);
  allocationSelections.value.push(currentSelection.value);
  currentSelection.value = '';
}

// ===== Vérification du numéro de réservation de chambre =====
function checkRoomReservation() {
  console.log("checkRoomReservation appelé");
  roomError.value = '';
  const found = reservationStore.reservations.find(
    r => r.numero === roomReservationNumber.value
  );
  if (!found) {
    roomError.value = "Numéro de chambre invalide ou non trouvé.";
    console.log("Aucun numéro de chambre trouvé");
    return;
  }
  roomReservation.value = found;
  step.value = 2;
  console.log("Numéro de chambre validé:", found.numero);
}

// ===== Soumission finale de la réservation de kayak =====
function submitKayakReservation() {
  console.log("submitKayakReservation appelé");
  submitError.value = '';
  if (!roomReservationNumber.value.trim()) {
    submitError.value = "Veuillez renseigner votre numéro de réservation de chambre.";
    console.log("Erreur: numéro de chambre vide");
    return;
  }
  if (!roomReservation.value) {
    submitError.value = "Numéro de chambre invalide.";
    console.log("Erreur: numéro de chambre invalide");
    return;
  }
  if (!date.value) {
    submitError.value = "Veuillez sélectionner la date de l'activité.";
    console.log("Erreur: date non renseignée");
    return;
  }
  if (date.value < roomReservation.value.startDate || date.value > roomReservation.value.endDate) {
    submitError.value = "La date doit être comprise entre les dates de réservation de la chambre.";
    console.log("Erreur: date hors intervalle");
    return;
  }
  if (!timeslot.value) {
    submitError.value = "Veuillez sélectionner un créneau horaire.";
    console.log("Erreur: créneau non sélectionné");
    return;
  }
  if (participants.value < 1 || participants.value > 8) {
    submitError.value = "Le nombre de participants doit être compris entre 1 et 8.";
    console.log("Erreur: participants hors borne");
    return;
  }
  if (remainingParticipants.value > 0) {
    submitError.value = "Veuillez compléter l'allocation des kayaks pour tous les participants.";
    console.log("Erreur: allocation incomplète, restants:", remainingParticipants.value);
    return;
  }
  
  const resData = {
    roomReservationNumber: roomReservationNumber.value,
    date: date.value,
    timeslot: timeslot.value,
    participants: participants.value,
    kayakAllocation: [...allocationSelections.value]
  };
  console.log("Données de réservation envoyées:", resData);
  const newRes = kayakStore.addReservation(resData);
  kayakConfirmation.value = newRes;
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
/* Les effets de hover, de grossissement et de grisage sont gérés via Tailwind dans le template */
</style>
