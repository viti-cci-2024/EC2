<template>
  <header class="fixed top-0 left-0 w-full bg-gradient-to-r from-[#09012B] to-[#605BF1] shadow z-50">
    <!-- Conteneur principal -->
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <!-- Logo / Lien vers l'accueil -->
      <RouterLink :to="{ name: 'Home' }" class="flex items-center">
        <img
          src="/logo-gite-BLANC.webp"
          alt="Accueil"
          class="w-40 h-auto hover:scale-110 transition-transform duration-300"
          title="Accueil"
        />
      </RouterLink>

      <!-- Bouton hamburger (mobile) -->
      <button
        @click="toggleMenu"
        class="lg:hidden bg-transparent text-white focus:outline-none"
        aria-label="Toggle navigation"
      >
        <svg
          v-if="!isOpen"
          xmlns="http://www.w3.org/2000/svg"
          class="h-8 w-8"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="2"
          stroke="white"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg
          v-else
          xmlns="http://www.w3.org/2000/svg"
          class="h-8 w-8"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="2"
          stroke="white"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <!-- Navigation Desktop -->
      <nav class="hidden lg:flex gap-4">
        <RouterLink :to="{ name: 'Chambre' }" class="text-white hover:text-gray-300 transition">
          Nos chambres
        </RouterLink>
        <RouterLink :to="{ name: 'Repas' }" class="text-white hover:text-gray-300 transition">
          Notre restaurant
        </RouterLink>
        <RouterLink :to="{ name: 'Cheval' }" class="text-white hover:text-gray-300 transition">
          Randonnée équestre
        </RouterLink>
        <RouterLink :to="{ name: 'Kayak' }" class="text-white hover:text-gray-300 transition">
          Sortie en kayak
        </RouterLink>
        <RouterLink :to="{ name: 'Bagne' }" class="text-white hover:text-gray-300 transition">
          Visite du bagne
        </RouterLink>
        <RouterLink :to="{ name: 'Garderie' }" class="text-white hover:text-gray-300 transition">
          La garderie
        </RouterLink>
      </nav>
    </div>

    <!-- Navigation Mobile -->
    <transition
      @before-enter="beforeEnter"
      @enter="enter"
      @after-enter="afterEnter"
      @before-leave="beforeLeave"
      @leave="leave"
      @after-leave="afterLeave"
    >
      <div v-if="isOpen" ref="mobileMenu" class="lg:hidden overflow-hidden">
        <div class="px-6 pb-4 space-y-4 text-right">
          <RouterLink
            @click="closeMenu"
            :to="{ name: 'Chambre' }"
            class="block text-lg text-white hover:text-gray-300 transition"
          >
            Nos chambres
          </RouterLink>
          <RouterLink
            @click="closeMenu"
            :to="{ name: 'Repas' }"
            class="block text-lg text-white hover:text-gray-300 transition"
          >
            Notre restaurant
          </RouterLink>
          <RouterLink
            @click="closeMenu"
            :to="{ name: 'Cheval' }"
            class="block text-lg text-white hover:text-gray-300 transition"
          >
            Randonnée équestre
          </RouterLink>
          <RouterLink
            @click="closeMenu"
            :to="{ name: 'Kayak' }"
            class="block text-lg text-white hover:text-gray-300 transition"
          >
            Sortie en kayak
          </RouterLink>
          <RouterLink
            @click="closeMenu"
            :to="{ name: 'Bagne' }"
            class="block text-lg text-white hover:text-gray-300 transition"
          >
            Visite du bagne
          </RouterLink>
          <RouterLink
            @click="closeMenu"
            :to="{ name: 'Garderie' }"
            class="block text-lg text-white hover:text-gray-300 transition"
          >
            La garderie
          </RouterLink>
        </div>
      </div>
    </transition>
  </header>
</template>

<script setup>
import { ref, nextTick } from 'vue'
import { RouterLink } from 'vue-router'

const isOpen = ref(false)

const toggleMenu = () => {
  isOpen.value = !isOpen.value
}

const closeMenu = () => {
  isOpen.value = false
}

// Hooks de transition pour animer le menu mobile
function beforeEnter(el) {
  el.style.height = '0'
}

function enter(el) {
  el.style.transition = 'height 0.3s ease'
  nextTick(() => {
    el.style.height = el.scrollHeight + 'px'
  })
}

function afterEnter(el) {
  // Une fois l'animation terminée, remettre la hauteur automatique
  el.style.height = 'auto'
}

function beforeLeave(el) {
  el.style.height = el.scrollHeight + 'px'
}

function leave(el) {
  el.style.transition = 'height 0.3s ease'
  nextTick(() => {
    el.style.height = '0'
  })
}

function afterLeave(el) {
  // Nettoyage du style inline
  el.style.height = ''
}
</script>
