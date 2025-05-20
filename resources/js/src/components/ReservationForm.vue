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
            <button
  class="bg-green-500 text-white px-4 py-2 rounded"
  @click="submitReservation"
  type="button"
>
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
  const endDate = new Date(end); // Stocker end dans une constante pour clarifier l'intention
  for (let dt = new Date(start); dt <= endDate; dt.setDate(dt.getDate() + 1)) {
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

// Nouvelle version : disponibilité via l'API Laravel
const computeAvailability = async () => {
  availableMer.value = 0;
  availableJardin.value = 0;
  if (!startDate.value || !endDate.value) return;
  try {
    const res = await fetch(`/api/bungalow-availability?start_date=${startDate.value}&end_date=${endDate.value}`);
    if (!res.ok) throw new Error('Erreur lors de la récupération des disponibilités');
    const data = await res.json();
    availableMer.value = data.mer ?? 0;
    availableJardin.value = data.jardin ?? 0;
  } catch (e) {
    console.error('Erreur lors de la vérification de la disponibilité:', e);
    availableMer.value = 0;
    availableJardin.value = 0;
  }
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

// Nouvelle version : soumission via l'API Laravel
const submitReservation = async () => {
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
  
  // Soumission API
  try {
    console.log('%c === DÉBUT DE LA SOUMISSION DE RÉSERVATION ===', 'background: #3498db; color: white; padding: 4px 10px; border-radius: 3px; font-size: 14px;');
    console.log('%c Vérification des données du formulaire', 'background: #9b59b6; color: white; padding: 2px 5px; border-radius: 3px;');
    
    // Déterminer le bungalow_id en fonction du type de bungalow sélectionné
    let bungalowId = 1; // Par défaut: premier bungalow mer
    
    if (selectedRoomType.value === 'Bungalow jardin') {
      bungalowId = 6; // Premier bungalow jardin
    }
    
    console.log('lastName:', lastName.value);
    console.log('roomType:', selectedRoomType.value);
    console.log('bungalowId:', bungalowId);
    console.log('startDate:', startDate.value);
    console.log('endDate:', endDate.value);
    console.log('personCount:', personCount.value);
    
    // Préparer le payload pour l'API
    const payload = {
      last_name: lastName.value,
      bungalow_id: bungalowId, // Important: inclure l'ID du bungalow
      start_date: startDate.value,
      end_date: endDate.value,
      person_count: personCount.value,
    };
    
    console.log('%c Payload de requête prêt', 'background: #2980b9; color: white; padding: 2px 5px; border-radius: 3px;');
    console.log('Données JSON:', JSON.stringify(payload, null, 2));
    
    // Définir un timeout (30 secondes)
    const controller = new AbortController();
    const timeoutId = setTimeout(() => {
      console.error('%c TIMEOUT: La requête a expiré après 30 secondes', 'background: #e74c3c; color: white; padding: 2px 5px; border-radius: 3px; font-weight: bold;');
      controller.abort();
    }, 30000);
    
    // Utiliser l'URL relative pour compatibilité avec différents environnements
    const apiUrl = '/api/bungalow-reservation';
    console.log('Envoi de la réservation à:', apiUrl);
    
    console.log('URL API utilisée:', apiUrl);
    console.log('URL complète:', window.location.origin + apiUrl);
    console.log('Origine actuelle:', window.location.origin);
    
    // Vérification du protocole et de l'hôte actuel
    console.log('Location:', {  
      protocol: window.location.protocol,
      host: window.location.host,
      hostname: window.location.hostname,
      port: window.location.port,
      pathname: window.location.pathname
    });
    
    // Utilisation de navigator.onLine pour vérifier si le navigateur est connecté
    console.log('Statut de connexion du navigateur:', navigator.onLine ? 'Connecté' : 'Déconnecté');
    
    // Début de la requête
    console.log('%c Envoi de la requête API...', 'background: #27ae60; color: white; padding: 2px 5px; border-radius: 3px;');
    console.time('Durée de la requête API');
    
    // Vérification de la présence du serveur avant l'envoi principal
    try {
      console.log('Test de connexion au serveur...');
      const pingResponse = await fetch('/api/bungalow-availability', {
        method: 'HEAD',
        cache: 'no-cache',
        headers: { 
          'X-Ping': 'true'
        },
        timeout: 5000
      }).catch(err => {
        console.error('Erreur de ping:', err);
        return { ok: false, status: 0, statusText: err.message };
      });
      
      console.log('Résultat du ping:', pingResponse.ok ? 'Serveur accessible' : 'Serveur inaccessible', {
        status: pingResponse.status,
        statusText: pingResponse.statusText
      });
    } catch (pingError) {
      console.error('Erreur pendant le ping du serveur:', pingError);
    }
    
    // Envoi de la requête principale
    console.log('Envoi de la requête principale...');
    const res = await fetch(apiUrl, {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-Debug': 'true'
      },
      body: JSON.stringify(payload),
      signal: controller.signal,
      credentials: 'same-origin', // Envoyer les cookies pour les sessions Laravel
      mode: 'cors', // Permettre les requêtes cross-origin
      cache: 'no-cache' // Éviter le cache
    });
    
    console.timeEnd('Durée de la requête API');
    clearTimeout(timeoutId); // Nettoyer le timeout
    
    console.log('%c RÉPONSE API REÇUE', 'background: #2ecc71; color: white; padding: 4px 10px; border-radius: 3px; font-size: 14px;');
    console.log('Status:', res.status, res.statusText);
    console.log('Type de réponse:', res.type);
    console.log('Headers:', Object.fromEntries([...res.headers.entries()]));
    console.log('URL finale:', res.url);
    
    // Essayons de lire le texte brut de la réponse d'abord
    const rawResponse = await res.text();
    console.log('Réponse brute (taille):', rawResponse.length, 'caractères');
    console.log('Réponse brute (contenu):', rawResponse);
    
    // Maintenant, essayons de traiter comme JSON
    let data;
    try {
      data = JSON.parse(rawResponse);
      console.log('%c Données JSON parsées avec succès', 'background: #16a085; color: white; padding: 2px 5px; border-radius: 3px;');
      console.log(data);
    } catch (jsonError) {
      console.error('%c ERREUR DE PARSING JSON', 'background: #c0392b; color: white; padding: 2px 5px; border-radius: 3px;');
      console.error('Erreur:', jsonError);
      console.error('Contenu brut reçu:', rawResponse);
      submitError.value = 'Erreur lors du traitement de la réponse du serveur. Vérifiez la console pour plus de détails.';
      return;
    }
    
    if (res.status === 409) {
      console.warn('%c CONFLIT DÉTECTÉ', 'background: #d35400; color: white; padding: 2px 5px; border-radius: 3px;');
      submitError.value = 'Ce bungalow a été réservé entre temps. Veuillez réessayer.';
      return;
    }
    
    if (!res.ok) {
      console.error('%c ERREUR API', 'background: #c0392b; color: white; padding: 2px 5px; border-radius: 3px;');
      console.error('Détails:', data);
      submitError.value = data.message || 'Erreur lors de la réservation';
      throw new Error(data.message || 'Erreur lors de la réservation');
    }
    
    console.log('%c RÉSERVATION TRAITÉE AVEC SUCCÈS', 'background: #27ae60; color: white; padding: 4px 10px; border-radius: 3px; font-size: 14px;');
    
    console.log('Numéro de réservation reçu du serveur:', data.reservation_number);
    
    // Définir les informations de confirmation
    // Conversion du numéro de réservation au format CHXXXXXXXX (comme visible dans la capture d'écran)
    // Extraire la date et un identifiant unique du numéro de réservation existant
    const today = new Date();
    // Construire le numéro au format CH + AAMM + numéro séquentiel à 4 chiffres
    const yyMmdd = today.getFullYear().toString().substr(2, 2) + 
                 ('0' + (today.getMonth() + 1)).slice(-2) + 
                 ('0' + today.getDate()).slice(-2);
    
    // Vérifier si une séquence existe déjà dans le numéro de réservation API
    // sinon générer une séquence basée sur l'heure
    let sequence = '0002'; // Par défaut, commencer à 0002
    if (data.reservation_number && data.reservation_number.match(/\d{4}$/)) {
      // Essayer d'extraire les 4 derniers chiffres du numéro
      const existingSequence = data.reservation_number.match(/\d{4}$/);
      if (existingSequence && existingSequence[0]) {
        sequence = existingSequence[0];
      }
    }
    
    // Créer le numéro de réservation au format CH + date + séquence
    const formattedReservationNumber = 'CH' + yyMmdd + sequence;
    console.log('Numéro de réservation formaté:', formattedReservationNumber);
    
    confirmation.value = {
      lastName: lastName.value,
      startDate: startDate.value,
      endDate: endDate.value,
      roomType: selectedRoomType.value,
      personCount: personCount.value,
      numero: formattedReservationNumber, // Utiliser notre numéro formaté
    };
    
    step.value = 3;
    scrollToConfirmation();
  } catch (e) {
    console.error('Erreur de soumission:', e);
    
    // BLOC COMMENTÉ POUR LE DÉBOGAGE
    console.error('Erreur lors de la réservation. Vérifiez la console pour plus de détails.', e);
    submitError.value = 'Erreur de communication avec le serveur: ' + e.message;
  }
};
</script>

<style scoped>
/* Vous pouvez affiner le style du calendrier ou d'autres éléments ici */
</style>
