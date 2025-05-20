<template>
  <div class="weather-widget p-4 border rounded shadow-md">
    <p v-if="loading">Chargement de la météo...</p>
    <div v-else>
      <!-- Météo du jour -->
      <h3 class="text-lg font-bold">
        Météo à {{ city }} le {{ currentDate }}
      </h3>
      <div class="flex items-center space-x-4">
        <!-- Affichage de l'emoji pour la météo courante -->
        <span class="text-4xl">{{ currentEmoji }}</span>
        <div>
          <p class="text-md capitalize">{{ weatherDescription }}</p>
          <p class="text-2xl">{{ temperature }}°C</p>
        </div>
      </div>

      <!-- Prévisions sur 5 jours après le jour en cours -->
      <div class="mt-6">
        <h3 class="text-lg font-bold">Prévisions sur 5 jours</h3>
        <div class="grid grid-cols-3 md:grid-cols-5 gap-3 mt-4">
  <div
    v-for="(day, index) in forecast"
    :key="index"
    class="border rounded shadow-md text-center p-2 aspect-[4/3]"
  >
    <p class="font-bold">{{ formatDate(day.dt) }}</p>
    <span class="text-2xl">{{ getEmoji(day.weather[0].icon) }}</span>
    <p class="text-xl md:text-lg">{{ Math.round(day.main.temp) }}°C</p>
  </div>
</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';

const city = 'Poum';
const apiKey = '69ec84f6c4da6ab8ba6745bca7421a99';

const loading = ref(true);
const temperature = ref(null);
const weatherDescription = ref('');
const currentIcon = ref('');
const forecast = ref([]);

// Table de correspondance entre le code d'icône et l'emoji Unicode
const weatherMapping = {
  '01d': '☀️', // ciel dégagé 
  '01n': '☀️', // ciel dégagé 
  '02d': '⛅', // quelques nuages
  '02n': '⛅', // quelques nuages
  '03d': '☁️', // nuageux
  '03n': '☁️',
  '04d': '☁️', // très nuageux
  '04n': '☁️',
  '09d': '🌧', // pluie légère
  '09n': '🌧',
  '10d': '🌦', // pluie
  '10n': '🌦',
  '11d': '⛈', // orage
  '11n': '⛈',
  '13d': '❄️', // neige
  '13n': '❄️',
  '50d': '🌫', // brouillard
  '50n': '🌫'
};

// Fonction qui retourne l'emoji correspondant, ou une icône par défaut
const getEmoji = (icon) => {
  return weatherMapping[icon] || '❓';
};

// Affichage de l'emoji pour la météo du jour (calculé à partir de currentIcon)
const currentEmoji = computed(() => getEmoji(currentIcon.value));

// Date actuelle formatée
const currentDate = computed(() => {
  return new Date().toLocaleDateString('fr-FR', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
});

// Formatage des dates des prévisions (format court)
const formatDate = (timestamp) => {
  return new Date(timestamp * 1000).toLocaleDateString('fr-FR', {
    weekday: 'short',
    day: 'numeric'
  });
};

const fetchWeather = async () => {
  loading.value = true;
  try {
    // Récupération de la météo du jour
    const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric&lang=fr`;
    const res = await fetch(url);
    if (!res.ok) {
      throw new Error("Erreur lors de la récupération de la météo");
    }
    const data = await res.json();
    temperature.value = Math.round(data.main.temp);
    weatherDescription.value = data.weather[0].description;
    currentIcon.value = data.weather[0].icon;
  } catch (error) {
    console.error("Erreur de récupération météo :", error);
  }
  await fetchForecast();
  loading.value = false;
};

const fetchForecast = async () => {
  try {
    // Récupération des prévisions
    const url = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${apiKey}&units=metric&lang=fr`;
    const res = await fetch(url);
    if (!res.ok) {
      throw new Error("Erreur lors de la récupération des prévisions météo");
    }
    const data = await res.json();
    const daily = [];
    const daysAdded = new Set();

    // On ignore la journée en cours et on ajoute la première occurrence de chaque jour
    const todayStr = new Date().toLocaleDateString('fr-FR', {
      year: 'numeric',
      month: 'numeric',
      day: 'numeric'
    });

    data.list.forEach((item) => {
      const itemDate = new Date(item.dt * 1000).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'numeric',
        day: 'numeric'
      });
      if (itemDate === todayStr) return;
      if (!daysAdded.has(itemDate)) {
        daysAdded.add(itemDate);
        daily.push(item);
      }
    });
    // Limiter aux 5 jours suivants
    forecast.value = daily.slice(0, 5);
  } catch (error) {
    console.error("Erreur de récupération des prévisions météo :", error);
  }
};

onMounted(() => {
  fetchWeather();
});
</script>

<style scoped>
/* Vous pouvez personnaliser davantage les styles selon vos besoins */
</style>
