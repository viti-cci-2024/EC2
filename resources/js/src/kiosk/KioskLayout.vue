<template>
    <router-view />            <!-- pas de Navbar/Footer -->
  </template>
  
  <script setup>
  import { useIdle } from '@vueuse/core';
  import { useRouter } from 'vue-router';
  import { watch } from 'vue';
  
  const router = useRouter();
  /* 5 min sans toucher l'écran → retour page kiosk d'accueil */
  const { idle } = useIdle(300_000);
  watch(idle, v => v && router.push({ name:'KioskHome' }));
  </script>
