<template>
  <div class="flex flex-col min-h-screen">
    <!-- Masqué sur borne -->
    <Navbar class="kiosk:hidden" />

    <!-- Le padding‑top ne sert que lorsqu’on affiche la barre -->
    <main class="flex-grow pt-24 kiosk:pt-0 w-full">
      <router-view />
    </main>

    <!-- Masqué sur borne -->
    <Footer class="kiosk:hidden" />
  </div>
</template>

<script setup>
import Navbar from './components/Navbar.vue';
import Footer  from './components/Footer.vue';
</script>

<style>
/* La classe utilitaire reste globale */
:global(.text-shadow) {
  text-shadow: 4px 4px 10px rgba(0, 0, 0, 1);
}
</style>
