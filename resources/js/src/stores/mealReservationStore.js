// src/stores/mealReservationStore.js
import { defineStore } from 'pinia';

export const useMealReservationStore = defineStore('mealReservation', {
  state: () => ({
    reservations: []
  }),
  actions: {
    init() {
      const stored = localStorage.getItem('mealReservations');
      if (stored) {
        this.reservations = JSON.parse(stored);
      }
    },
    updateLocalStorage() {
      localStorage.setItem('mealReservations', JSON.stringify(this.reservations));
    },
    addReservation(mealData) {
      // Génération d'un numéro unique pour le repas avec le format : REAAMM000x
      const now = new Date();
      const year = now.getFullYear().toString().slice(-2);
      const month = ('0' + (now.getMonth() + 1)).slice(-2);
      const count = this.reservations.length + 1;
      const sequential = ('0000' + count).slice(-4);
      const numero = `RE${year}${month}${sequential}`;
      const newReservation = { ...mealData, numero, createdAt: now.toISOString() };
      this.reservations.push(newReservation);
      this.updateLocalStorage();
      return newReservation;
    }
  }
});
