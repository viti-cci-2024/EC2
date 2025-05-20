<template>
  <!-- SECTION HERO -->
  <section class="w-full h-[60vh] bg-cover bg-center " :style="{ backgroundImage: 'url(/kayak/kayak-hero.webp)' }">
    <div class="max-w-7xl mx-auto flex flex-col justify-center items-start h-full p-5">
      <div class="text-white text-left">
        <h1 class="mb-3 text-shadow text-4xl font-bold">Vivez la mer autrement...</h1>
        <h4 class="mb-3 text-shadow text-2xl">à bord d'un kayak !</h4>
        <RouterLink :to="{ name: 'KayakResa' }"
          class="inline-block text-lg font-semibold py-3 px-8 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] hover:scale-105">
          Réserver
        </RouterLink>
      </div>
    </div>
  </section>



  <!-- KAYAK PRESENTATION PHOTO + TEXTE 1 et 2 -->


  <!-- PRESENTATION 001 -->
  <section class="py-10 px-4 bg-white mt-10">
    <div class="max-w-7xl mx-auto">
      <div class="flex flex-col md:flex-row justify-around gap-6">
        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center">
          <div class="absolute inset-0 flex flex-col justify-center p-4 md:p-6">
            <h2 class="text-3xl font-bold">Présentation de l'activité</h2>
            <br />
            <ul class="list-disc list-inside text-lg mb-4 text-gray-700">
              <li class="pb-2 border-b border-gray-300 last:border-0">Horaire : 9h à 16h</li>
              <li class="pb-2 border-b border-gray-300 last:border-0">Kayak simple : 2</li>
              <li class="pb-2 border-b border-gray-300 last:border-0">Kayak double : 3</li>
            </ul>
            <h4 class="font-bold text-lg mb-2 pb-2">Souvent confondu avec le canoë, la pratique sportive est désignée par le terme
              général « canoë-kayak ». Contrairement au canoë ou canot, le kayak se pratique en position assise. </h4>
          </div>
        </div>
        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center rounded-xl shadow-lg"
          style="background-image: url('/kayak/image-6.jpg')"></div>
      </div>
    </div>
  </section>

  <!-- PRESENTATION 002 -->
  <section class="py-10 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
      <div class="flex flex-col md:flex-row justify-around gap-6">
        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center rounded-xl shadow-lg"
          style="background-image: url('/kayak/image-7.webp')"></div>
        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center">
          <div class="absolute inset-0 flex flex-col justify-center p-4 md:p-6">
            <h2 class="text-3xl font-bold">Une expérience unique</h2>
            <br />
            <h4 class="text-lg md:text-xl mb-2 pb-2">
              À l'origine, le kayak était inuit, fabriqué avec des membrures pliées, bordées et recouvertes de peaux, et
              manœuvré à l'aide d'une pagaie simple ou double. Aujourd'hui, il est réalisé en toile imperméable, en
              matériaux synthétiques ou pneumatiques, et se manœuvre avec une pagaie double. Certaines versions modernes comportent un trou, l'hiloire,
              facilitant l'embarquement.  La construction contemporaine distingue notamment le kayak de rivière, de vitesse
              en eaux calmes et le kayak de mer. </h4>

          </div>
        </div>
      </div>
    </div>
  </section>



  

  <!-- SECTION GALERIE (CAROUSEL INFINI) -->
  <section class="py-10 px-4 bg-white">
    <div class="max-w-7xl mx-auto relative overflow-hidden">
      <!-- Bouton Précédent -->
      <button @click="prev" :disabled="isAnimating"
        class="absolute top-1/2 left-0 -translate-y-1/2 bg-white text-gray-800 p-4 rounded-full shadow-lg hover:bg-gray-100 transition z-10">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <!-- Bouton Suivant -->
      <button @click="next" :disabled="isAnimating"
        class="absolute top-1/2 right-0 -translate-y-1/2 bg-white text-gray-800 p-4 rounded-full shadow-lg hover:bg-gray-100 transition z-10">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
      <!-- Conteneur du Carousel -->
      <div class="flex transition-transform duration-500 ease-in-out"
        :style="{ transform: `translateX(-${currentSlide * 100}%)`, transition: disableTransition ? 'none' : '' }"
        @transitionend="handleTransitionEnd">
        <div v-for="(group, index) in extendedSlides" :key="index" class="flex-shrink-0 w-full">
          <div v-for="item in group" :key="item.image" class="w-full">
            <img :src="item.image" alt="Image de la galerie kayak"
              class="w-full h-[350px] md:h-[500px] object-cover rounded-lg shadow-lg" />
          </div>
        </div>
      </div>
    </div>
  </section>

<!-- Section Conditions sécurité -->
<section class="bg-[#5852de] bg-opacity-100 py-10">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center text-center md:text-left">
      
      <!-- Texte à gauche -->
      <div class="md:w-1/2 flex flex-col justify-center">
        <h2 class="text-3xl text-white font-bold mb-6">Consignes de sécurité en kayak</h2>
        <ul class="list-disc list-inside space-y-4 text-lg text-white">
          <li>Le port du gilet de sauvetage est obligatoire</li>
          <li>Respectez les consignes du moniteur avant et pendant l'activité</li>
          <li>Ne vous éloignez jamais seul du groupe, restez toujours visible</li>
        </ul>
      </div>

      <!-- Image à droite -->
      <div class="md:w-1/2 flex justify-center">
        <img src="/kayak/gilet-sauvetage-det.png"
             alt="Sécurité" title="Gilet de sauvetage"
             class="w-[280px] md:w-[380px] h-auto object-contain">
      </div>
    </div>
  </div>
</section>









  <!------------------------- BANDEAU PRE-FOOTER ------------------------------>

  <section
    class="w-full h-[40vh] md:h-[30vh] bg-cover bg-[center_90%]  flex items-center justify-center bg-gray-500 bg-blend-multiply"
    :style="{ backgroundImage: 'url(/kayak/image-1.jpg)' }">
    <div class="text-center">
      <h1 class="mb-3 text-white text-4xl font-bold">
        Réservez votre sortie en kayak !
      </h1>
      <!-- Bouton Réservez via RouterLink vers la page "Chambre" -->
      <RouterLink :to="{ name: 'KayakResa' }"
        class="inline-block text-lg font-semibold py-3 px-8 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] hover:scale-105">
        Je réserve !
      </RouterLink>
    </div>
  </section>





</template>

<script setup>
import { ref, computed, nextTick } from 'vue'
import { RouterLink } from 'vue-router'

// Tableau d'images de la galerie (chacun encapsulé dans un objet pour être compatible avec la logique du carousel)
const galleryImages = [
  '/kayak/image-1.jpg',
  '/kayak/image-2.jpeg',
  '/kayak/image-3.jpg',
  '/kayak/image-4.jpg',
  '/kayak/image-5.jpg',
  '/kayak/image-6.jpg',
  '/kayak/image-7.webp',
  '/kayak/image-8.jpg',
  '/kayak/image-9.jpg'
]

// Pour simplifier, chaque item devient un objet { image }
const galleryItems = galleryImages.map((img) => ({ image: img }))

// Pour ce carousel, on souhaite afficher 1 image par slide.
const itemsPerSlide = 1

// Création des groupes de slides
const slideGroups = computed(() => {
  const result = []
  for (let i = 0; i < galleryItems.length; i += itemsPerSlide) {
    result.push(galleryItems.slice(i, i + itemsPerSlide))
  }
  return result
})

// Création du tableau étendu pour simuler l'infini (clone du dernier groupe au début et du premier à la fin)
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
const disableTransition = ref(false)
const isAnimating = ref(false)

// Passage à l'image suivante
const next = () => {
  if (isAnimating.value || extendedSlides.value.length === 0) return
  isAnimating.value = true
  currentSlide.value++
}

// Passage à l'image précédente
const prev = () => {
  if (isAnimating.value || extendedSlides.value.length === 0) return
  isAnimating.value = true
  currentSlide.value--
}

// Gestion de la fin de transition pour l'effet infini
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
</script>

<style scoped>
.text-shadow {
  text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
}
</style>
