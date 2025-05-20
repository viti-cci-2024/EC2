<template>

<div class="reservation-form p-4">
        <h2 class="text-2xl font-bold mb-4">Veuillez compléter ce formulaire</h2>

        <!-- Étape 1 : Saisie des dates -->
        <div v-if="step === 1">
            <p class="mb-2 font-medium">Sélectionnez vos dates de séjour :
<!-- Icône d'information avec tooltip en CSS uniquement -->
      <!-- Icône "i" -->
      <span class="relative group ml-2 flex items-center justify-center w-6 h-6 text-blue-500 border border-blue-500 rounded-full cursor-pointer">
        i
      
      <!-- Tooltip : affichage en mobile et desktop -->
      <div class="absolute bottom-full left-1 mb-2 hidden group-hover:block group-focus:block bg-gray-700 text-white text-lg md:text-base rounded py-2 px-3 w-85 z-10">
        Important : Pour réserver une activité, vous aurez besoin de votre numéro de réservation de chambre. Pensez à bien noter ce numéro qui vous sera donné après la validation du formulaire.
      </div>
    </span>


            </p>
            <div class="flex flex-col sm:flex-row gap-4 mb-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium" for="startDate">Date de début :</label>
                    <input id="startDate" v-model="startDate" type="date" :min="getTodayNoumea()"
                        class="border px-2 py-1 w-full" required />
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium" for="endDate">Date de fin :</label>
                    <input id="endDate" v-model="endDate" type="date" :min="startDate || getTodayNoumea()"
                        class="border px-2 py-1 w-full" required />
                </div>
            </div>
            <button @click="validateDates" class="bg-blue-500 text-white px-4 py-2 rounded">
                Valider les dates
            </button>
            <p v-if="dateError" class="text-red-500 mt-2">{{ dateError }}</p>
        </div>

        <!-- Étape 2 : Affichage des informations et suite du formulaire -->
        <div v-else-if="step === 2">


            <!-- Bloc prévision météo pour la période de réservation -->
            <div class="mb-4 p-4 border rounded shadow-md">
                <p class="font-medium">
                    Prévision météo pour votre séjour du {{ startDate }} au {{ endDate }} :
                </p>
                <div v-if="weatherLoading">
                    Chargement de la météo de réservation...
                </div>
                <div v-else-if="forecast">
                    <p class="capitalize">{{ forecast.description }}</p>
                    <p class="text-2xl">{{ forecast.temp }}°C</p>
                </div>
                <div v-else>
                    Aucune donnée météo disponible pour ces dates.
                </div>
            </div>

            <!-- Bloc disponibilité des bungalows -->
            <div class="mb-4 p-4 border rounded shadow-md">
                <p class="font-medium mb-2">
                    Bungalows disponibles pour les dates sélectionnées :
                </p>
                <p>
                    Bungalow vue mer : <span class="font-bold">{{ availableMer }}</span> / {{ capacityMer }}
                </p>
                <p>
                    Bungalow vue jardin : <span class="font-bold">{{ availableJardin }}</span> / {{ capacityJardin }}
                </p>
                <div class="mt-4">
                    <p class="mb-2 font-medium">Calendrier de disponibilité :</p>
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="border p-1">Date</th>
                                <th class="border p-1">Bungalow mer</th>
                                <th class="border p-1">Bungalow jardin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="day in calendarDays" :key="day">
                                <td class="border p-1">{{ day }}</td>
                                <td class="border p-1">
                                    <span :class="availabilityIndicator(availableMer)">●</span>
                                </td>
                                <td class="border p-1">
                                    <span :class="availabilityIndicator(availableJardin)">●</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Suite du formulaire : Nom, choix du type de bungalow et nombre de personnes -->
            <div class="mb-4">
                <label class="block text-sm font-medium" for="lastName">Nom de famille :</label>
                <input id="lastName" v-model="lastName" type="text" class="border px-2 py-1 w-full" required />
            </div>

            <div class="mb-4">
                <p class="font-medium mb-2">Choisissez votre type de chambre :</p>
                <div class="flex items-center gap-4">
                    <div v-if="availableMer > 0">
                        <input type="radio" id="mer" value="Bungalow mer" v-model="selectedRoomType" />
                        <label for="mer">Bungalow vue mer</label>
                    </div>
                    <div v-if="availableJardin > 0">
                        <input type="radio" id="jardin" value="Bungalow jardin" v-model="selectedRoomType" />
                        <label for="jardin">Bungalow vue jardin</label>
                    </div>
                </div>
                <p v-if="!availableMer && !availableJardin" class="text-red-500 mt-2">
                    Aucun bungalow disponible pour les dates sélectionnées.
                </p>
            </div>

            <!-- Champ pour le nombre de personnes avec limitation dynamique -->
            <div class="mb-4">
                <label class="block text-sm font-medium" for="personCount">Nombre de personnes :</label>
                <input id="personCount" v-model.number="personCount" type="number" min="1" :max="personMax"
                    class="border px-2 py-1 w-full" required />
                <p v-if="selectedRoomType === 'Bungalow mer'" class="text-sm text-gray-600">
                    Maximum 2 personnes pour vue mer.
                </p>
                <p v-if="selectedRoomType === 'Bungalow jardin'" class="text-sm text-gray-600">
                    Maximum 4 personnes pour vue jardin.
                </p>
            </div>

            <!-- Bouton de soumission -->
            <button @click="submitReservation" class="bg-green-500 text-white px-4 py-2 rounded">
                Réserver
            </button>
            <p v-if="submitError" class="text-red-500 mt-2">{{ submitError }}</p>
        </div>

<!-- Étape 3 : Confirmation de réservation -->
<div
  v-else-if="step === 3"
  id="confirmation"
  ref="confirmationRef"
  class="mt-4 p-4 border rounded shadow-md"
>
  <h3 class="text-xl font-bold mb-2">Votre réservation a été enregistrée !</h3>
  <p>
    <span class="font-medium">Nom :</span> {{ confirmation.lastName }}
  </p>
  <p>
    <span class="font-medium">Du :</span> {{ confirmation.startDate }} 
    <span class="font-medium">au</span> {{ confirmation.endDate }}
  </p>
  <p>
    <span class="font-medium">Type de chambre :</span> {{ confirmation.roomType }}
  </p>
  <p>
    <span class="font-medium">Nombre de personnes :</span> {{ confirmation.personCount }}
  </p>
  <p class="mt-2">
  Votre n° de réservation est : <span class="font-bold text-green-500">{{ confirmation.numero }}</span>
</p>
<p class="mt-2">
    <span class="font-bold text-red-500"> Notez bien ce numéro qui vous permettra de réserver vos activités</span></p>
</div>
    </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue';
import { useReservationStore } from '../stores/reservationStore';

// Initialisation du store et chargement des réservations existantes
const reservationStore = useReservationStore();
reservationStore.init();

// Variables pour la gestion du formulaire et des étapes
const step = ref(1);
const startDate = ref('');
const endDate = ref('');
const dateError = ref('');

const lastName = ref('');
const selectedRoomType = ref('');
const personCount = ref(1);
const submitError = ref('');
const confirmation = ref(null);

// Référence pour le bloc de confirmation
const confirmationRef = ref(null);

// Fonction pour obtenir la date d'aujourd'hui en tenant compte du fuseau horaire de Nouméa (UTC+11)
const getTodayNoumea = () => {
  return new Date().toLocaleDateString('en-CA', { timeZone: 'Pacific/Noumea' });
};

const todayStr = getTodayNoumea();

// Variables pour la météo du jour
const todayLoading = ref(false);
const todayForecast = ref(null);
// Variables pour la prévision météo liée aux dates de réservation
const weatherLoading = ref(false);
const forecast = ref(null);
const openWeatherApiKey = '69ec84f6c4da6ab8ba6745bca7421a99';
const city = 'Poum';

// Capacités de base pour les chambres
const capacityMer = 5;
const capacityJardin = 10;
const availableMer = ref(capacityMer);
const availableJardin = ref(capacityJardin);

// Calcul du maximum de personnes autorisé en fonction du type sélectionné
const personMax = computed(() => {
  if (selectedRoomType.value === 'Bungalow mer') return 2;
  if (selectedRoomType.value === 'Bungalow jardin') return 4;
  return undefined;
});

// Générer un tableau de dates entre startDate et endDate
const calendarDays = computed(() => {
  if (!startDate.value || !endDate.value) return [];
  const start = new Date(startDate.value);
  const end = new Date(endDate.value);
  const days = [];
  for (let dt = new Date(start); dt <= end; dt.setDate(dt.getDate() + 1)) {
    days.push(dt.toISOString().split('T')[0]);
  }
  return days;
});

// Validation des dates et passage à l'étape 2
const validateDates = () => {
  dateError.value = '';
  if (!startDate.value || !endDate.value) {
    dateError.value = 'Veuillez renseigner les deux dates.';
    return;
  }
  if (startDate.value < getTodayNoumea()) {
    dateError.value = "La date de début ne peut pas être passée.";
    return;
  }
  if (startDate.value > endDate.value) {
    dateError.value = 'La date de début doit être antérieure à la date de fin.';
    return;
  }
  // Récupérer la météo de réservation et calculer la disponibilité
  fetchForecast();
  computeAvailability();
  step.value = 2;
};

// Récupération de la météo du jour
const fetchTodayWeather = async () => {
  todayLoading.value = true;
  try {
    const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${openWeatherApiKey}&units=metric&lang=fr`;
    const res = await fetch(url);
    if (!res.ok) throw new Error("Erreur de récupération météo du jour");
    const data = await res.json();
    todayForecast.value = {
      temp: data.main.temp,
      description: data.weather[0].description,
    };
  } catch (error) {
    console.error("Erreur lors de la récupération de la météo du jour :", error);
    todayForecast.value = null;
  }
  todayLoading.value = false;
};

// Récupération de la prévision météo pour la période de réservation (basée sur la date de début)
const fetchForecast = async () => {
  weatherLoading.value = true;
  try {
    const url = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${openWeatherApiKey}&units=metric&lang=fr`;
    const res = await fetch(url);
    if (!res.ok) throw new Error('Erreur de récupération météo de réservation');
    const data = await res.json();
    const targetDate = startDate.value;
    const forecastItem = data.list.find(item => item.dt_txt.startsWith(targetDate));
    if (forecastItem) {
      forecast.value = {
        temp: forecastItem.main.temp,
        description: forecastItem.weather[0].description,
      };
    } else {
      forecast.value = null;
    }
  } catch (error) {
    console.error("Erreur lors de la récupération de la météo de réservation :", error);
    forecast.value = null;
  }
  weatherLoading.value = false;
};

// Au montage, récupérer la météo du jour.
fetchTodayWeather();

// Fonction de vérification de chevauchement entre deux intervalles de dates
const isOverlap = (start1, end1, start2, end2) => {
  return start1 <= end2 && start2 <= end1;
};

// Calcul de la disponibilité en tenant compte des réservations existantes
const computeAvailability = () => {
  let bookedMer = 0;
  let bookedJardin = 0;
  reservationStore.reservations.forEach(res => {
    if (
      res.type === 'Chambre' &&
      res.startDate && res.endDate &&
      isOverlap(startDate.value, endDate.value, res.startDate, res.endDate)
    ) {
      if (res.roomType === 'Bungalow mer') bookedMer++;
      if (res.roomType === 'Bungalow jardin') bookedJardin++;
    }
  });
  availableMer.value = capacityMer - bookedMer;
  availableJardin.value = capacityJardin - bookedJardin;
};

// Classe pour l'indicateur de disponibilité
const availabilityIndicator = (avail) => {
  return avail > 0 ? 'text-green-500 text-xl' : 'text-red-500 text-xl';
};

// Vérification que le formulaire est prêt à être soumis et affichage des erreurs si nécessaire
const canSubmit = computed(() => {
  return lastName.value && selectedRoomType.value && personCount.value > 0;
});

// Fonction pour scroller vers l'ancre de confirmation
const scrollToConfirmation = () => {
  nextTick(() => {
    const el = document.getElementById('confirmation');
    if (el) {
      // Option 1 : Modifier l'ancre
      window.location.hash = '#confirmation';
      // Option 2 (complémentaire) : Défilement fluide
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
};

// Soumission finale du formulaire avec vérification des champs requis
const submitReservation = () => {
  submitError.value = '';
  // Vérifier que le nom est renseigné
  if (!lastName.value.trim()) {
    submitError.value = 'Veuillez renseigner votre nom de famille.';
    return;
  }
  // Vérifier que le type de bungalow est choisi
  if (!selectedRoomType.value) {
    submitError.value = 'Veuillez choisir un type de bungalow.';
    return;
  }
  // Validation du nombre de personnes selon le type de bungalow
  if (selectedRoomType.value === 'Bungalow mer' && personCount.value > 2) {
    submitError.value = 'Vous ne pouvez réserver plus de 2 personnes pour un bungalow vue mer.';
    return;
  }
  if (selectedRoomType.value === 'Bungalow jardin' && personCount.value > 4) {
    submitError.value = 'Vous ne pouvez réserver plus de 4 personnes pour un bungalow vue jardin.';
    return;
  }
  const reservationData = {
    type: 'Chambre',
    startDate: startDate.value,
    endDate: endDate.value,
    lastName: lastName.value,
    roomType: selectedRoomType.value,
    personCount: personCount.value,
  };
  const newReservation = reservationStore.addReservation(reservationData);
  confirmation.value = {
    lastName: lastName.value,
    startDate: startDate.value,
    endDate: endDate.value,
    roomType: selectedRoomType.value,
    personCount: personCount.value,
    numero: newReservation.numero,
  };
  step.value = 3;
  
  // Faire défiler vers le message de confirmation en utilisant l'ancre
  scrollToConfirmation();
};
</script>

<style scoped>
/* Vous pouvez affiner le style du calendrier ou d'autres éléments ici */
</style>
