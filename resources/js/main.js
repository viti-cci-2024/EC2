import { createApp } from 'vue'
import App from './src/App.vue'
import router from './src/router'
import './src/style.css'
import { createPinia } from 'pinia'

const app = createApp(App)           // Crée l'instance de l'application

const pinia = createPinia()          // Crée l'instance de Pinia

app.use(pinia)                     // Enregistre Pinia auprès de l'application
app.use(router)                    // Enregistre le router
app.mount('#app')                  // Monte l'application sur l'élément avec l'id "app"
