<template>
  <!-- Section hero pleine largeur avec background et hauteur 60vh -->
  <section class="w-full h-[60vh] bg-cover bg-center  bg-gray-400 bg-blend-multiply" :style="{ backgroundImage: 'url(/repas/repas-hero.webp)' }">
    <!-- Conteneur pour centrer verticalement le contenu avec padding -->
    <div class="max-w-7xl mx-auto flex flex-col justify-center items-start h-full p-5">
      <!-- Contenu texte en blanc aligné à gauche -->
      <div class="text-white text-left">
        <h1 class="mb-3 text-shadow text-4xl font-bold">Bienvenue au restaurant Pim</h1>
        <h4 class="mb-3 text-shadow text-2xl">
          Venez émerveiller vos papilles qui n’en reviendront pas.
        </h4>
        <!-- Bouton Réservez via RouterLink vers la page "Chambre" -->
        <RouterLink :to="{ name: 'RepasResa' }"
          class="inline-block text-lg font-semibold py-3 px-8 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] hover:scale-105">
          Réserver
        </RouterLink>
      </div>
    </div>
  </section>

<!--------------------------------       SECTION BAR DU RESTO        ----------------------------------------->

<section class="py-10 px-4 bg-white mt-10">
  <div class="max-w-7xl mx-auto">
    <!-- Titre de la section -->
    <h2 class="text-3xl font-bold mb-2">Nos cocktails</h2>
    <h4 class="font-bold text-lg mb-2">
      Avant le repas, venez profiter de notre bar, de sa large sélection de boissons avec ou sans alcool.
    </h4>

    <div class="relative w-full">
      <!-- Image du bar (pleine largeur) -->
      <img
        src="/repas/bar.webp"
        alt="bar du restaurant pim"
        class="rounded-xl shadow-lg w-full h-[50vh] object-cover"
      />

      <!-- Image cocktail : affichée uniquement sur ≥1024px, au-dessus de l’image du bar -->
      <img
        src="/repas/cocktail.webp"
        alt="verre à cocktail"
        class="hidden lg:block absolute top-1/2 right-[5%] transform -translate-y-1/2 w-[400px] object-contain h-auto z-10"
      />
    </div>
  </div>
</section>


<!------------------------- LES 2 CARDS ------------------------------>


 <!-- SPECIALITES -->
 <section class="py-10 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
      <h2 class="text-3xl font-bold">Nos spécialités</h2><br>
      <div class="flex flex-col md:flex-row justify-around gap-6">
        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center rounded-xl shadow-lg  bg-gray-300 bg-blend-multiply"
          style="background-image: url('/repas/meat.jpg')">
          <div class="absolute inset-0 flex flex-col justify-start p-6 md:p-6">
            <h2 class="text-2xl md:text-4xl font-bold text-white text-shadow mb-2">Côté Terre</h2>
            <p class="text-white text-xl md:text-xl text-shadow mb-4">Notre sélection de délicieuses viandes va ravir le carnivore qui est en vous !
            </p>
            <RouterLink :to="{ name: 'RepasResa' }"
              class="self-start inline-block text-lg font-semibold py-3 px-8 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] hover:scale-105">
              Réserver
            </RouterLink>
          </div>
        </div>

        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center rounded-xl shadow-lg  bg-gray-300 bg-blend-multiply"
          style="background-image: url('/repas/fruits-de-mer-pim.jpeg')">
          <div class="absolute inset-0 flex flex-col justify-start p-6 md:p-6">
            <h2 class="text-2xl md:text-4xl font-bold text-white text-shadow mb-2">Côté Mer</h2>
            <p class="text-white text-xl md:text-xl text-shadow mb-4">Vous aimez les embruns et le goût de l’iode ? Nos fruits de mer seront un délice !</p>
            <RouterLink :to="{ name: 'RepasResa' }"
              class="self-start inline-block text-lg font-semibold py-3 px-8 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] hover:scale-105">
              Réserver
            </RouterLink>
          </div>
        </div>
      </div>
    </div>
  </section>

 <!------------------------- TEMOIGNAGES ------------------------------>

  <!-- Témoignages -->
  <section class="py-10 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
      <h2 class="text-3xl font-bold">Nos clients ont adoré</h2><br>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div v-for="(testimonial, index) in testimonials" :key="index"
          class="bg-white rounded-lg shadow p-6 text-center border border-gray-200">
          <img :src="testimonial.avatar" :alt="`Avatar de ${testimonial.name}`"
            class="w-20 h-20 rounded-full mx-auto mb-4 object-cover" />
          <h5 class="text-xl font-semibold">{{ testimonial.name }}</h5>
          <p class="text-gray-500 mb-2">{{ testimonial.location }}</p>
          <p class="text-gray-700 mb-4">"{{ testimonial.text }}"</p>
          <div class="flex justify-center text-yellow-500 text-xl">
            <template v-for="n in 5" :key="n">
              <span>{{ n <= Math.floor(testimonial.rating) ? '★' : '☆' }}</span>
            </template>
          </div>
        </div>
      </div>
    </div>
  </section>


    <!------------------------- BANDEAU PRE-FOOTER ------------------------------>
 
    <section
  class="w-full h-[40vh] md:h-[30vh] bg-bottom bg-cover flex items-center justify-center bg-gray-500 bg-blend-multiply"
  :style="{ backgroundImage: 'url(/repas/dinner.jpg)' }"
>
  <div class="text-center">
    <h1 class="mb-3 text-white text-4xl font-bold">
      Réservez votre table !
    </h1>
    <!-- Bouton Réservez via RouterLink vers la page "Chambre" -->
    <RouterLink
      :to="{ name: 'RepasResa' }"
      class="inline-block text-lg font-semibold py-3 px-8 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] hover:scale-105"
    >
      Je réserve !
    </RouterLink>
  </div>
</section>


</template>

  <!------------------------- SCRIPTS ------------------------------>
  <script setup>
  import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue'
  import { RouterLink } from 'vue-router'

  const testimonials = [
  { name: 'Billy Gaytes', location: 'USA, Chicago', text: "Une très bonne sélection de grands vins pour accompagner une viande savoureuse !", avatar: '/avatar1.png', rating: 4.5 },
  { name: 'Elsa Gorithme', location: 'France, Nice', text: 'Le personnel était au top et la qualité des produits de la mer est exceptionnelle !', avatar: '/avatar2.png', rating: 5 },
  { name: 'Olivia Jax', location: 'Vanuatu, Port-Vila', text: 'Incroyable site et le restaurant était merveilleux. Quels plats délicieux !', avatar: '/avatar3.png', rating: 4 },
]
</script>



  <!------------------------- STYLES ------------------------------>

<style scoped>
/* Tu peux garder ce bloc si tu veux ajouter des styles perso */
</style>
