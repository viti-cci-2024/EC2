<template>
<!-- Section hero pleine largeur avec background et hauteur 60vh -->
<section
    class="w-full h-[60vh] bg-cover bg-center"
    :style="{ backgroundImage: 'url(/cheval/cheval-hero.webp)' }"
  >
    <!-- Conteneur pour centrer verticalement le contenu avec padding -->
    <div class="max-w-7xl mx-auto flex flex-col justify-center items-start h-full p-5">
      <!-- Contenu texte en blanc aligné à gauche -->
      <div class="text-white text-left">
        <h1 class="mb-3 text-shadow text-4xl font-bold">Vivez la nature autrement...</h1>
        <h4 class="mb-3 text-shadow text-2xl">
          à dos de cheval !
        </h4>
        <!-- Bouton Réservez via RouterLink vers la page "Chambre" -->
        <RouterLink
          :to="{ name: 'ChevalResa' }"
          class="inline-block text-lg font-semibold py-3 px-8 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] hover:scale-105"
        >
          Réserver
        </RouterLink>
      </div>
    </div>
  </section>


  <section class="bg-[#605bf1]/10 py-20">
    <div class="max-w-7xl mx-auto px-4">
      <h2 class="text-3xl text-left font-bold mb-8">
        Vivez une aventure équestre inoubliable !
      </h2>
      <!-- Conteneur flex qui réordonne les éléments en fonction du breakpoint -->
      <div class="flex flex-col md:flex-row items-center justify-center gap-6">
        <!-- Bloc texte : en mobile, affiché en premier ; sur desktop, à droite -->
        <div class="order-1 md:order-2 flex-1">
          <div class="bg-white p-8 rounded-xl shadow-lg">
            <h4 class="font-medium text-lg mb-2">
              Partez à la découverte de la faune et de la flore locales lors d'une expérience exceptionnelle à dos de cheval.
              Choisissez votre cheval parmi nos 16 compagnons, chacun avec son propre caractère, et suivez un itinéraire enchanteur
              à travers des paysages verdoyants, jusqu'à un point de vue offrant un panorama à couper le souffle.
            </h4>
          </div>
        </div>
        <!-- Bloc image : en mobile, affiché en deuxième ; sur desktop, à gauche -->
        <!-- Le conteneur de l'image a une largeur fixe et ne peut pas se réduire -->
        <div class="order-2 md:order-1 flex-shrink-0">
          <img
            src="/cheval/horse-ride-left.webp"
            alt="Aventure équestre"
            class="w-[350px] h-auto object-contain"
          />
        </div>
      </div>
    </div>
  </section>



 <!-- GARDERIE EQUIPES PHOTO + TEXTE 1 et 2 -->

 
 <section class="py-10 px-4 bg-white">
    <div class="max-w-7xl mx-auto">

   <!-- 001 -->
      <div class="flex flex-col md:flex-row justify-around gap-6">
        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center">
          <div class="absolute inset-0 flex flex-col justify-center  md:p-6">
            <h2 class="text-3xl font-bold">Emma et Nidguep (Matin)</h2>
            <br />
            <h4 class="text-lg md:text-xl mb-2">
            Passionnée d'équitation et certifiée en randonnée, Emma guide les groupes le matin avec son cheval Nidguepe, un compagnon calme et réactif.
            Avec son expérience, elle assure une randonnée sereine et enrichissante à travers des paysages magnifiques.
            </h4>
          </div>
        </div>
        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-[center_28%] rounded-xl shadow-lg"
          style="background-image: url('/cheval/Emma.jpg')"></div>
      </div>
    </div>
  </section>

  <!-- 002 -->
  <section class="py-10 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
      <div class="flex flex-col md:flex-row justify-around gap-6">
        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-[center_1%] rounded-xl shadow-lg"
          style="background-image: url('/cheval/Lucas.webp')"></div>
        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center">
          <div class="absolute inset-0 flex flex-col justify-center p-4 md:p-6">
            <h2 class="text-3xl font-bold">Lucas et Papyrus (Après-midi)</h2>
            <br />
            <h4 class="text-lg md:text-xl mb-2 pb-2">
            Lucas, certifié en gestion de randonnées, prend le relais l'après-midi avec son cheval Papyrus, vif et élégant.
            Ensemble, ils offrent une expérience immersive et conviviale, où chaque randonneur profite pleinement de l'aventure.
            </h4>
           
          </div>
        </div>
      </div>
    </div>
  </section>


<!-- Section Conditions Résa cheval -->
<section class="bg-[#5852de] bg-opacity-100 py-10">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center text-center md:text-left">
      
      <!-- Texte à gauche -->
      <div class="md:w-1/2 flex flex-col justify-center">
        <h2 class="text-3xl text-white font-bold mb-6">Conditions de réservation</h2>
        <ul class="list-disc list-inside space-y-4 text-lg text-white">
          <li>Réservation obligatoire d'une chambre au gîte</li>
          <li>Activité accessible à tous, débutants comme cavaliers confirmés</li>
          <li>En cas de pluie, la randonnée sera reportée pour votre sécurité</li>
        </ul>
      </div>

      <!-- Image à droite -->
      <div class="md:w-1/2 flex justify-center">
        <img src="/cheval/horse-ride-right.png"
             alt="Sécurité" title="Gilet de sauvetage"
             class="w-[280px] md:w-[380px] h-auto object-contain">
      </div>
    </div>
  </div>
</section>

    <!------------------------- BANDEAU PRE-FOOTER ------------------------------>
 
    <section
  class="w-full h-[40vh] md:h-[30vh] bg-[center_23%] bg-cover flex items-center justify-center bg-gray-500 bg-blend-multiply"
  :style="{ backgroundImage: 'url(/cheval/herofooter2.webp)' }"
>
  <div class="text-center">
    <h1 class="mb-3 text-white text-4xl font-bold">
      Réservez votre sortie équestre !
    </h1>
    <!-- Bouton Réservez via RouterLink vers la page "Chambre" -->
    <RouterLink
      :to="{ name: 'ChevalResa' }"
      class="inline-block text-lg font-semibold py-3 px-8 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] hover:scale-105"
    >
      Je réserve !
    </RouterLink>
  </div>
</section>












</template>

<script setup>
import { RouterLink } from 'vue-router'
</script>

<style scoped>
/* Tu peux garder ce bloc si tu veux ajouter des styles perso */
</style>
