import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import Bagne from '../views/Bagne.vue'
import Chambre from '../views/Chambre.vue'
import Cheval from '../views/Cheval.vue'
import Garderie from '../views/Garderie.vue'
import Kayak from '../views/Kayak.vue'
import Repas from '../views/Repas.vue'
import BagneResa from '../views/BagneResa.vue'
import ChambreResa from '../views/ChambreResa.vue'
import ChevalResa from '../views/ChevalResa.vue'
import GarderieResa from '../views/GarderieResa.vue'
import KayakResa from '../views/KayakResa.vue'
import RepasResa from '../views/RepasResa.vue'
import NotFound from '../views/Error404View.vue' // pour 404
import KioskLayout  from '../kiosk/KioskLayout.vue';
import HomeKiosk    from '../kiosk/HomeKiosk.vue';

const routes = [
  { path: '/', name: 'Home', component: HomeView },
  { path: '/bagne', name: 'Bagne', component: Bagne },
  { path: '/chambre', name: 'Chambre', component: Chambre },
  { path: '/cheval', name: 'Cheval', component: Cheval },
  { path: '/garderie', name: 'Garderie', component: Garderie },
  { path: '/kayak', name: 'Kayak', component: Kayak },
  { path: '/repas', name: 'Repas', component: Repas },
  { path: '/bagneresa', name: 'BagneResa', component: BagneResa },
  { path: '/chambreresa', name: 'ChambreResa', component: ChambreResa },
  { path: '/chevalresa', name: 'ChevalResa', component: ChevalResa },
  { path: '/garderieresa', name: 'GarderieResa', component: GarderieResa },
  { path: '/kayakresa', name: 'KayakResa', component: KayakResa },
  { path: '/repasresa', name: 'RepasResa', component: RepasResa },
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound }, // ← 404
  { path: '/kiosk',
    component: KioskLayout,
    children: [
      { path: '',  name:'KioskHome', component: HomeKiosk },
      // éventuellement d'autres écrans kiosk plus tard
    ]
  },
  { path: '/:pathMatch(.*)*', name:'NotFound', component: NotFound }
]

const router = createRouter({
  history: createWebHistory('/'),
  routes,
  // Configuration du scroll pour chaque navigation
  scrollBehavior(to, from, savedPosition) {
    // Si une position sauvegardée existe (navigation arrière/avant), on la récupère
    // Sinon, on retourne toujours { top: 0 } pour afficher la page en haut
    return savedPosition || { top: 0 }
  }
})

export default router
