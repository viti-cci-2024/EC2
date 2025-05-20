<template>
  <!-- HERO ------------------------------------------------------ -->
  <section
    class="w-full h-[60vh] kiosk:h-screen bg-cover bg-center"
    :style="{ backgroundImage: 'url(/hero-main.jpg)' }"
  >
    <div class="max-w-7xl mx-auto flex flex-col justify-center items-start h-full p-5 kiosk:p-12">
      <div class="text-white text-left">
        <h1 class="mb-4 text-shadow text-4xl kiosk:text-6xl font-bold">
          Bienvenue au gîte Pim
        </h1>
        <h4 class="mb-6 text-shadow text-2xl kiosk:text-4xl">
          Venez vous ressourcer dans un cadre idyllique.
        </h4>
        <RouterLink
          :to="{ name: 'Chambre' }"
          class="inline-block font-semibold text-lg kiosk:text-3xl py-3 kiosk:py-6 px-8 kiosk:px-14 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] active:bg-[#09012B] active:scale-105 focus-visible:ring-4"
        >
          En savoir plus
        </RouterLink>
      </div>
    </div>
  </section>

  <!-- BUNGALOWS -------------------------------------------------- -->
  <section class="py-10 kiosk:py-16 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
      <h2 class="text-3xl kiosk:text-5xl font-bold">Nos Bungalows</h2>
      <br />
      <div class="flex flex-col md:flex-row justify-around gap-6 kiosk:gap-10">
        <!-- Bungalow Jardin -->
        <div
          class="relative w-full md:w-1/2 h-[400px] kiosk:h-[500px] bg-cover bg-center rounded-xl shadow-lg overflow-hidden"
          style="background-image: url('/chambre/Bungalow-Jardin-01.png')"
        >
          <div class="absolute inset-0 bg-black/40 z-0" />
          <div class="absolute inset-0 flex flex-col justify-start p-4 md:p-6 kiosk:p-8 z-10">
            <h2 class="text-4xl kiosk:text-5xl font-bold text-white text-shadow mb-4">
              Bungalow Jardin
            </h2>
            <p class="text-white text-xl kiosk:text-2xl text-shadow mb-6">
              Spacieux et confortables dans un cadre verdoyant !
            </p>
            <RouterLink
              :to="{ name: 'Chambre' }"
              class="inline-block font-semibold text-lg kiosk:text-3xl py-3 kiosk:py-6 px-8 kiosk:px-14 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] active:bg-[#09012B] active:scale-105 focus-visible:ring-4"
            >
              En savoir plus
            </RouterLink>
          </div>
        </div>

        <!-- Bungalow Mer -->
        <div
          class="relative w-full md:w-1/2 h-[400px] kiosk:h-[500px] bg-cover bg-center rounded-xl shadow-lg overflow-hidden"
          style="background-image: url('/chambre/Bungalow-Mer-01.png')"
        >
          <div class="absolute inset-0 bg-black/40 z-0" />
          <div class="absolute inset-0 flex flex-col justify-start p-4 md:p-6 kiosk:p-8 z-10">
            <h2 class="text-4xl kiosk:text-5xl font-bold text-white text-shadow mb-4">
              Bungalow Vue Mer
            </h2>
            <p class="text-white text-xl kiosk:text-2xl text-shadow mb-6">
              Laissez-vous bercer par le son des vagues sur la terrasse.
            </p>
            <RouterLink
              :to="{ name: 'Chambre' }"
              class="inline-block font-semibold text-lg kiosk:text-3xl py-3 kiosk:py-6 px-8 kiosk:px-14 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] active:bg-[#09012B] active:scale-105 focus-visible:ring-4"
            >
              En savoir plus
            </RouterLink>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CAROUSEL --------------------------------------------------- -->
  <section class="py-10 kiosk:py-16 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl kiosk:text-5xl font-bold">Nos activités</h2>
      </div>

      <div
        class="relative overflow-hidden"
        v-touch:swipe.left="next"
        v-touch:swipe.right="prev"
      >
        <!-- Nav buttons -->
        <button
          @click="prev"
          :disabled="isAnimating"
          class="absolute top-1/2 left-0 -translate-y-1/2 bg-white text-gray-800 p-4 kiosk:p-6 rounded-full shadow-lg hover:bg-gray-100 transition z-10 disabled:opacity-40"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-6 h-6 kiosk:w-10 kiosk:h-10"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        <button
          @click="next"
          :disabled="isAnimating"
          class="absolute top-1/2 right-0 -translate-y-1/2 bg-white text-gray-800 p-4 kiosk:p-6 rounded-full shadow-lg hover:bg-gray-100 transition z-10 disabled:opacity-40"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-6 h-6 kiosk:w-10 kiosk:h-10"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>

        <!-- Slides -->
        <div
          class="flex transition-transform duration-500 ease-in-out"
          :style="{
            transform: `translateX(-${currentSlide * 100}%)`,
            transition: disableTransition ? 'none' : ''
          }"
          @transitionend="handleTransitionEnd"
        >
          <div
            v-for="(group, index) in extendedSlides"
            :key="index"
            class="flex-shrink-0 w-full flex gap-6 kiosk:gap-10"
          >
            <div
              v-for="item in group"
              :key="item.title"
              class="w-full md:w-1/2 lg:w-1/3 kiosk:w-1/3 bg-white rounded-xl shadow border border-gray-200"
            >
              <img
                :src="item.image"
                :alt="item.title"
                class="w-full h-48 kiosk:h-64 object-cover object-top rounded-t"
              />
              <div class="p-4 kiosk:p-6">
                <h4 class="font-bold text-lg kiosk:text-2xl mb-4">{{ item.title }}</h4>
                <p class="text-base kiosk:text-xl mb-6">{{ item.text }}</p>
                <RouterLink
                  :to="item.link"
                  class="inline-block font-semibold text-sm kiosk:text-xl py-2 kiosk:py-4 px-4 kiosk:px-8 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] active:bg-[#09012B] active:scale-105 focus-visible:ring-4"
                >
                  En savoir plus
                </RouterLink>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SEPARATION ------------------------------------------------- -->
  <section
    class="w-full h-[25vh] kiosk:h-[30vh] bg-cover bg-start mb-8 flex items-center justify-center"
    :style="{ backgroundImage: 'url(/sep-accueil.webp)' }"
  >
    <div class="text-center">
      <span class="text-white text-4xl kiosk:text-6xl font-bold">
        Vivez une expérience unique !
      </span>
    </div>
  </section>

  <!-- TEMOIGNAGES ----------------------------------------------- -->
  <section class="py-10 kiosk:py-16 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
      <h2 class="text-3xl kiosk:text-5xl font-bold">Nos clients ont adoré</h2>
      <br />
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 kiosk:gap-12">
        <div
          v-for="(testimonial, index) in testimonials"
          :key="index"
          class="bg-white rounded-lg shadow p-6 kiosk:p-10 text-center border border-gray-200"
        >
          <img
            :src="testimonial.avatar"
            :alt="`Avatar de ${testimonial.name}`"
            class="w-20 h-20 kiosk:w-28 kiosk:h-28 rounded-full mx-auto mb-6 object-cover"
          />
          <h5 class="text-lg kiosk:text-2xl font-semibold">{{ testimonial.name }}</h5>
          <p class="text-gray-500 mb-2 kiosk:text-lg">{{ testimonial.location }}</p>
          <p class="text-gray-700 mb-6 text-base kiosk:text-xl">
            "{{ testimonial.text }}"
          </p>
          <div class="flex justify-center text-yellow-500 text-xl kiosk:text-3xl">
            <template v-for="n in 5" :key="n">
              <span>{{ n <= Math.floor(testimonial.rating) ? '★' : '☆' }}</span>
            </template>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- BANDEAU PRE-FOOTER ---------------------------------------- -->
  <section
    class="w-full h-[40vh] kiosk:h-[50vh] md:h-[30vh] bg-cover bg-[center_65%] flex items-center justify-center bg-gray-500 bg-blend-multiply"
    :style="{ backgroundImage: 'url(/chambre/Bungalow-Jardin-05.png)' }"
  >
    <div class="text-center">
      <h1 class="mb-6 text-white text-4xl kiosk:text-6xl font-bold">
        Réservez votre bungalow !
      </h1>
      <RouterLink
        :to="{ name: 'ChambreResa' }"
        class="inline-block font-semibold text-lg kiosk:text-3xl py-3 kiosk:py-6 px-8 kiosk:px-14 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] active:bg-[#09012B] active:scale-105 focus-visible:ring-4"
      >
        Je réserve !
      </RouterLink>
    </div>
  </section>
</template>

<!-- SCRIPTS ------------------------------------------------------ -->
<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue';
import { RouterLink } from 'vue-router';

/* --------------------- CAROUSEL --------------------- */

// Vos items de carousel
const items = [
  { title: 'Visite du bagne', text: "Replongez avec notre guide dans l'histoire du pays sur l'île de Pam.", image: '/carou-bagne.jpg', link: { name: 'Bagne' } },
  { title: 'Randonnée équestre', text: "De belles balades dans la nature avec notre guide. À ne pas manquer !", image: '/carou-cheval.jpg', link: { name: 'Cheval' } },
  { title: 'Notre garderie', text: "Confiez-nous avec sérénité vos enfants pendant vos activités sportives ou de repos.", image: '/carou-gard.jpg', link: { name: 'Garderie' } },
  { title: 'Club nautique', text: "Balades en famille ou avec vos amis, détente ou sportive en kayak sur le lagon.", image: '/carou-kayak.jpg', link: { name: 'Kayak' } },
  { title: 'Restaurant Pim', text: "Pour les gourmets et les gourmands, notre carte saura vous ravir !", image: '/carou-repas.jpg', link: { name: 'Repas' } },
  { title: 'Nos bungalows', text: "Jardin ou vue mer, choisissez votre bungalow pour passer un séjour idéal.", image: '/carou-bung.jpg', link: { name: 'Chambre' } },
]

// Réactivité de la largeur de la fenêtre
const windowWidth = ref(window.innerWidth)
const updateWidth = () => {
  windowWidth.value = window.innerWidth
}
onMounted(() => {
  window.addEventListener('resize', updateWidth)
})
onUnmounted(() => {
  window.removeEventListener('resize', updateWidth)
})

// Calculer le nombre d'items par slide selon la largeur
const itemsPerSlide = computed(() => {
  if (windowWidth.value < 768) return 1
  if (windowWidth.value < 1024) return 2
  return 3
})

// Création des groupes de slides
const slideGroups = computed(() => {
  const result = []
  for (let i = 0; i < items.length; i += itemsPerSlide.value) {
    result.push(items.slice(i, i + itemsPerSlide.value))
  }
  return result
})

// Construction du tableau étendu pour simuler l'infini (ajout des clones)
const extendedSlides = computed(() => {
  const groups = slideGroups.value
  if (groups.length > 0) {
    const firstClone = groups[0]
    const lastClone = groups[groups.length - 1]
    return [lastClone, ...groups, firstClone]
  }
  return []
})

// Le carousel démarre sur le premier groupe réel (index 1)
const currentSlide = ref(1)

// Flag pour désactiver temporairement la transition (pour le saut instantané)
const disableTransition = ref(false)

// Flag pour éviter les clics pendant l'animation
const isAnimating = ref(false)

// Passage au slide suivant
const next = () => {
  if (isAnimating.value || extendedSlides.value.length === 0) return
  isAnimating.value = true
  currentSlide.value++
}

// Passage au slide précédent
const prev = () => {
  if (isAnimating.value || extendedSlides.value.length === 0) return
  isAnimating.value = true
  currentSlide.value--
}

// Gestion de la fin de la transition avec délai pour le saut instantané
const handleTransitionEnd = () => {
  if (currentSlide.value === extendedSlides.value.length - 1) {
    disableTransition.value = true
    currentSlide.value = 1
  } else if (currentSlide.value === 0) {
    disableTransition.value = true
    currentSlide.value = extendedSlides.value.length - 2
  }
  nextTick(() => {
    setTimeout(() => {
      disableTransition.value = false
      isAnimating.value = false
    }, 50)
  })
}

// ▶️ AJOUT : Watcher pour réinitialiser le carousel lors du redimensionnement
watch(itemsPerSlide, () => {
  // Réinitialise currentSlide à 1 pour correspondre aux nouveaux groupes
  currentSlide.value = 1
  disableTransition.value = true
  nextTick(() => {
    disableTransition.value = false
  })
})

/* --------------------- FIN CAROUSEL --------------------- */

const testimonials = [
  { name: 'Billy Gaytes', location: 'USA, Chicago', text: "Notre séjour était fantastique ! Wonderful ! I'll be back, c'est certain !", avatar: '/avatar1.png', rating: 4.5 },
  { name: 'Elsa Gorithme', location: 'France, Nice', text: 'Le personnel du gîte était au top et la qualité de la literie est exceptionnelle !', avatar: '/avatar2.png', rating: 5 },
  { name: 'Olivia Jax', location: 'Vanuatu, Port-Vila', text: 'Incroyable site et le personnel était merveilleux. Je reviendrai !', avatar: '/avatar3.png', rating: 4 },
]
</script>

<!------------------------- STYLES ------------------------------>

<style scoped>
.text-shadow {
  text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
}
</style>
