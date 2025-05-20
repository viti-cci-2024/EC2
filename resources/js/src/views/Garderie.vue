<template>
  <!-- Section hero pleine largeur avec background et hauteur 60vh -->
  <section class="w-full h-[60vh] bg-cover bg-center" :style="{ backgroundImage: 'url(/garderie/garderie-hero.webp)' }">
    <!-- Conteneur pour centrer verticalement le contenu avec padding -->
    <div class="max-w-7xl mx-auto flex flex-col justify-center items-start h-full p-5">
      <!-- Contenu texte en blanc aligné à gauche -->
      <div class="text-white text-left">
        <h1 class="mb-3 text-shadow text-4xl font-bold">Profitez, on garde vos petits !</h1>
        <h4 class="mb-3 text-shadow text-2xl">
          Notre équipe sera heureuse de prendre en charge vos enfants !
        </h4>
        <!-- Bouton Réservez via RouterLink vers la page "Chambre" -->
        <RouterLink :to="{ name: 'GarderieResa' }"
          class="inline-block text-lg font-semibold py-3 px-8 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] hover:scale-105">
          Réserver
        </RouterLink>
      </div>
    </div>
  </section>





  <!------------------------- LES 2 CARDS ------------------------------>




  <section class="w-full bg-cover bg-center bg-[#605bf1]/10 pt-25 pb-20 mb-10">
    <!-- Conteneur principal responsive -->
    <div class="flex flex-col md:flex-row justify-around gap-6">
      
      <!-- Bloc texte (affiché en premier en mobile, à droite sur desktop) -->
      <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center order-1 md:order-2">
        <!-- Contenu texte au-dessus de l'image -->
        <div class="absolute inset-0 flex flex-col justify-center p-4 md:p-6">
          <h2 class="text-3xl font-bold mb-10">
            La garderie propose de nombreuses activités
          </h2>
          <ul class="space-y-4">
            <li class="font-bold text-lg pb-2 border-b border-gray-300 last:border-0">
              🏁   Course de sac
            </li>
            <li class="font-bold text-lg pb-2 border-b border-gray-300 last:border-0">
              🏛️   Visite des lieux
            </li>
            <li class="font-bold text-lg pb-2 border-b border-gray-300 last:border-0">
              🎨   Activités artistiques
            </li>
            <li class="font-bold text-lg pb-2 border-b border-gray-300 last:border-0">
              😴   Siestes
            </li>
            <li class="font-bold text-lg pb-2 border-b border-gray-300 last:border-0">
              📘   Cahiers de vacances
            </li>
          </ul>
        </div>
      </div>

      <!-- Bloc image (affiché en deuxième sur mobile, à gauche sur desktop) -->
      <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center rounded-tr-lg rounded-br-lg shadow-lg order-2 md:order-1"
        style="background-image: url('/garderie/kidz-webp.webp')">
      </div>

    </div>
  </section>







  <!-- GARDERIE EQUIPES PHOTO + TEXTE 1 et 2 -->


  <section class="py-10 px-4 bg-white mt-10 pb-25">
    <div class="max-w-7xl mx-auto">
      <!-- Titre de la section -->
      <h2 class="text-3xl font-bold mb-2">Notre équipe de passionnés saura encadrer vos enfants</h2>

      <!-- 001 -->
      <div class="flex flex-col md:flex-row justify-around gap-6 mt-10">
        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center">
          <div class="absolute inset-0 flex flex-col justify-center md:p-0">
            <h2 class="text-3xl font-bold">Julie</h2>
            <br />
            <h4 class="text-lg md:text-xl mb-2">
              Bonjour, je travaille aujourd'hui dans la garderie de ce gîte. Ancienne puéricultrice, j'ai passé
              plusieurs années à accompagner les tout-petits dans leurs premiers pas. J'adore organiser des activités
              ludiques en plein air dans notre magnifique gite. Au plaisir de vous rencontrer et de partager ces moments
              uniques avec vos petits trésors.
            </h4>
          </div>
        </div>
        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center rounded-xl shadow-lg"
          style="background-image: url('/garderie/garderie01.webp')"></div>
      </div>
    </div>
  </section>

  <!-- 002 -->
  <section class="py-10 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
      <div class="flex flex-col md:flex-row justify-around gap-6">
        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center rounded-xl shadow-lg"
          style="background-image: url('/garderie/garderie02.webp')"></div>
        <div class="relative w-full md:w-1/2 h-[400px] bg-cover bg-center">
          <div class="absolute inset-0 flex flex-col justify-center md:p-0">
            <h2 class="text-3xl font-bold">Emilie</h2>
            <br />
            <h4 class="text-lg md:text-xl mb-2 pb-2">
              Ancienne professeure de collège, j'ai passé plusieurs années à transmettre des connaissances et soutenir
              les enfants pendant leurs apprentissages. Je propose des activités ludiques et éducatives pour rendre
              l’apprentissage et amusant. Au plaisir de vous accueillir et de partager ces instants précieux avec vos
              jeunes explorateurs !
            </h4>

          </div>
        </div>
      </div>
    </div>
  </section>



  <!------------------------- BANDEAU SEPARATION ------------------------------>



 

<!-- Section Conditions Résa GARDERIE -->
<section class="bg-[#5852de] bg-opacity-100 py-10 mt-10">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center text-center md:text-left">
      
      <!-- Texte à gauche -->
      <div class="md:w-1/2 flex flex-col justify-center md:ms-25">
        <h2 class="text-3xl text-white font-bold mb-6">Pour s'inscrire à la garderie ouverte 24h/24 :</h2>
        <ul class="list-disc list-inside space-y-4 text-lg md:text-xl text-white">
          <li>Votre numéro de chambre</li>
          <li>Heure de début et de fin</li>
          <li>Pas plus de 4h d'affilée</li>
          <li>15 places disponibles au maximum</li>
        </ul>
      </div>

      <!-- Image à droite -->
      <div class="md:w-1/2 flex justify-center md:me-25">
        <img src="/garderie/kid-det.png"
             alt="Sécurité" title="Gilet de sauvetage"
             class="w-[280px] md:w-[380px] h-auto object-contain">
      </div>
    </div>
  </div>
</section>


  <!------------------------- BANDEAU PRE-FOOTER ------------------------------>

  <section
    class="w-full h-[40vh] md:h-[30vh] bg-cover bg-[center_90%] flex items-center justify-center bg-gray-400 bg-blend-multiply"
    :style="{ backgroundImage: 'url(/garderie/kidz-webp.webp)' }">
    <div class="text-center">
      <h1 class="mb-3 text-white text-4xl font-bold">
        Nous sommes prêts pour vos enfants !
      </h1>
      <!-- Bouton Réservez via RouterLink vers la page "Chambre" -->
      <RouterLink :to="{ name: 'GarderieResa' }"
        class="inline-block text-lg font-semibold py-3 px-8 rounded-full bg-[#FE8A24] text-white transition-all hover:bg-[#09012B] hover:scale-105">
        Je réserve !
      </RouterLink>
    </div>
  </section>






</template>
<script>
export default {
  name: 'GarderieSection'
}
</script>
<style scoped>
/* Tu peux garder ce bloc si tu veux ajouter des styles perso */
</style>
