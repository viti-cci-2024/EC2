// src/stores/reservationStore.js
import { defineStore } from 'pinia';

export const useReservationStore = defineStore('reservation', {
  state: () => ({
    // Stocke la liste des réservations
    reservations: []
  }),
  actions: {
    // Charge les réservations depuis le localStorage
    init() {
      const storedReservations = localStorage.getItem('reservations');
      if (storedReservations) {
        this.reservations = JSON.parse(storedReservations);
      }
    },
    // Met à jour le localStorage avec l'état courant
    updateLocalStorage() {
      localStorage.setItem('reservations', JSON.stringify(this.reservations));
    },
    // Génère le numéro de réservation en fonction du type
    generateReservationNumber(type) {
      let prefix = '';
      switch (type) {
        case 'Chambre':
          prefix = 'CH';
          break;
        case 'Repas':
          prefix = 'RE';
          break;
        case 'Randonnée':
          prefix = 'RA';
          break;
        case 'Kayak':
          prefix = 'KA';
          break;
        case 'Garderie':
          prefix = 'GA';
          break;
        case 'Bagne':
          prefix = 'BA';
          break;
        default:
          prefix = 'XX';
      }
      // Récupération de l'année (deux derniers chiffres) et du mois (format deux chiffres)
      const now = new Date();
      const year = now.getFullYear().toString().slice(-2); // ex: "24" pour 2024
      const month = ('0' + (now.getMonth() + 1)).slice(-2); // ex: "12" pour décembre
      
      // Compter le nombre de réservations de ce type pour l'année et le mois en cours
      const count = this.reservations.filter(
        r => r.type === type && r.numero.startsWith(prefix + year + month)
      ).length;
      
      // Générer le numéro séquentiel sur 4 chiffres
      const sequential = ('0000' + (count + 1)).slice(-4);
      return `${prefix}${year}${month}${sequential}`;
    },
    // Ajoute une réservation et met à jour le LocalStorage
    addReservation(reservationData) {
      // Générer le numéro de réservation en fonction du type passé dans reservationData
      const numero = this.generateReservationNumber(reservationData.type);
      
      // Création de la réservation en y ajoutant le numéro et la date de création
      const newReservation = {
        ...reservationData,
        numero,
        createdAt: new Date().toISOString()
      };
      
      this.reservations.push(newReservation);
      this.updateLocalStorage();
      return newReservation;
    }
  }
});