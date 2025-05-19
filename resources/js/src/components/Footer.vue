<template>
  <footer class="w-full mt-auto bg-gradient-to-r from-[#09012B] to-[#605BF1] text-white font-sans pt-10 pb-15">
    <div class="mx-auto px-4 max-w-7xl">
      <div class="flex flex-col md:flex-row md:justify-between gap-10">
        <!-- Colonne 1: Contact -->
        <div class="md:w-1/3">
          <RouterLink :to="{ name: 'Home' }" class="flex items-center">
        <img src="/logo-gite-BLANC.webp" alt="Accueil" class="w-48 mb-4 hover:scale-110 transition-transform duration-300" title="Accueil" />
      </RouterLink>
          <h6 class="uppercase font-bold mb-3">Contact</h6>
          <p class="hover:text-yellow-400 transition-colors duration-300">
            <i class="bi bi-geo-alt-fill mr-2"></i> Îlot Pam, Poum
          </p>
          <p class="hover:text-yellow-400 transition-colors duration-300">
            <i class="bi bi-telephone-fill mr-2"></i> +687 12 34 56
          </p>
          <p class="hover:text-yellow-400 transition-colors duration-300">
            <i class="bi bi-envelope-fill mr-2"></i> contact@gitepim.nc
          </p>
        </div>
  
        <!-- Colonne 2: Navigation & Activité côte à côte -->
        <div class="md:w-1/3">
          <div class="flex flex-col md:flex-row md:gap-10">
            <!-- Navigation -->
            <div class="w-full md:w-1/2">
              <h6 class="uppercase font-bold mb-3">Le Gîte</h6>
              <ul class="space-y-2">
                <li>
                  <RouterLink :to="{ name: 'Home' }" class="hover:text-yellow-400 hover:underline transition-colors">
                    Accueil
                  </RouterLink>
                </li>
                <li>
                  <RouterLink :to="{ name: 'Chambre' }" class="hover:text-yellow-400 hover:underline transition-colors">
                    Nos chambres
                  </RouterLink>
                </li>
                <li>
                  <RouterLink :to="{ name: 'Repas' }" class="hover:text-yellow-400 hover:underline transition-colors">
                    Notre restaurant
                  </RouterLink>
                </li>
                <li>
                  <RouterLink :to="{ name: 'Garderie' }" class="hover:text-yellow-400 hover:underline transition-colors">
                    La garderie
                  </RouterLink>
                </li>
              </ul>
            </div>
  
            <!-- Activité -->
            <div class="w-full md:w-1/2 mt-6 md:mt-0">
              <h6 class="uppercase font-bold mb-3">Activités</h6>
              <ul class="space-y-2">
                <li>
                  <RouterLink :to="{ name: 'Cheval' }" class="hover:text-yellow-400 hover:underline transition-colors">
                    Randonnée équestre
                  </RouterLink>
                </li>
                <li>
                  <RouterLink :to="{ name: 'Kayak' }" class="hover:text-yellow-400 hover:underline transition-colors">
                    Sortie en kayak
                  </RouterLink>
                </li>
                <li>
                  <RouterLink :to="{ name: 'Bagne' }" class="hover:text-yellow-400 hover:underline transition-colors">
                    Visite du bagne
                  </RouterLink>
                </li>
              </ul>
            </div>
          </div>
        </div>
  
        <!-- Colonne 3: Réseaux sociaux -->

        <div class="w-full md:w-1/3">
          <img src="/cartepim.png" alt="lieux Gîte Pim" class="w-full mb-4 hover:scale-110 transition-transform duration-300" />
  <h6 class="uppercase font-bold mt-6 mb-3">Suivez-nous</h6>
  <div class="flex space-x-4">
    <div class="relative group">
      <img src="/facebook.png" alt="Facebook" class="w-6 hover:scale-110 transition-transform duration-300" />
      <span class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs text-white bg-black rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap z-10">
        Bientôt disponible
      </span>
    </div>
    <div class="relative group">
      <img src="/instagram.png" alt="Instagram" class="w-6 hover:scale-110 transition-transform duration-300" />
      <span class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs text-white bg-black rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap z-10">
        Bientôt disponible
      </span>
    </div>
    <div class="relative group">
      <img src="/twitter.png" alt="Twitter" class="w-6 hover:scale-110 transition-transform duration-300" />
      <span class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs text-white bg-black rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap z-10">
        Bientôt disponible
      </span>
    </div>
  </div>
</div>


      </div>
    </div>
  </footer>
</template>

<script setup>
import { RouterLink } from 'vue-router'
</script>
