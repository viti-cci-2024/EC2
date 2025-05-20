<template>
  <!-- BANDEAU PRE-FOOTER avec le slider -->
  <section
    class="w-full h-[50vh] md:h-[40vh] bg-center bg-cover flex items-center justify-center bg-gray-500 bg-blend-multiply overflow-x-hidden"
    :style="{ backgroundImage: 'url(/bagne/bagne-3.webp)' }"
  >
    <div class="text-center">
      <h1 class="mb-3 text-white text-4xl font-bold">
        Réservez votre visite ! 
      </h1>

      <!-- Conteneur du slider : position relative, largeur 100% et overflow-hidden -->
      <div class="relative w-full h-16">
        <transition name="slide" mode="out-in">
          <p
            :key="currentIndex"
            class="absolute inset-0 flex items-center justify-center text-2xl font-semibold text-white whitespace-normal md:whitespace-nowrap"
          >
            {{ currentPhrase }}
          </p>
        </transition>
      </div>

      <RouterLink
        :to="{ name: 'BagneResa' }"
        class="mt-4 inline-block text-lg font-semibold py-3 px-8 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] hover:scale-105"
      >
        Je réserve !
      </RouterLink>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'

// Phrases du slider
const phrases = [
  "Découvrez une histoire émouvante au cœur du bagne.",
  "Un guide expérimenté pour vous accompagner dans ce voyage dans le passé.",
  "Plongez dans les récits poignants des anciens détenus.",
  "Une expérience unique qui restera gravée dans votre mémoire."  
]

const currentIndex = ref(0)
let intervalId = null

const currentPhrase = computed(() => phrases[currentIndex.value])

function startSlider() {
  intervalId = setInterval(() => {
    currentIndex.value = (currentIndex.value + 1) % phrases.length
  }, 3300)
}

onMounted(startSlider)
onBeforeUnmount(() => {
  clearInterval(intervalId)
})
</script>

<style scoped>
/* Animation de slide pour l'entrée et la sortie */
.slide-enter-active,
.slide-leave-active {
  transition: transform 0.5s ease, opacity 0.5s ease;
}

.slide-enter-from {
  transform: translateX(100%);
  opacity: 0;
}

.slide-enter-to {
  transform: translateX(0);
  opacity: 1;
}

.slide-leave-from {
  transform: translateX(0);
  opacity: 1;
}

.slide-leave-to {
  transform: translateX(-100%);
  opacity: 0;
}
</style>
