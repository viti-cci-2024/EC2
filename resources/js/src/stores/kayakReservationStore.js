import { defineStore } from 'pinia';

export const useKayakReservationStore = defineStore('kayakReservation', {
  state: () => ({
    reservations: []
  }),
  actions: {
    init() {
      const stored = localStorage.getItem('kayakReservations');
      if (stored) {
        this.reservations = JSON.parse(stored);
      }
    },
    updateLocalStorage() {
      localStorage.setItem('kayakReservations', JSON.stringify(this.reservations));
    },
    addReservation(data) {
      const now = new Date();
      const year = now.getFullYear().toString().slice(-2);
      const month = ('0' + (now.getMonth() + 1)).slice(-2);
      const count = this.reservations.length + 1;
      const sequential = ('0000' + count).slice(-4);
      const numero = `KA${year}${month}${sequential}`;
      const newReservation = { ...data, numero, createdAt: now.toISOString() };
      this.reservations.push(newReservation);
      this.updateLocalStorage();
      return newReservation;
    },
    // Retourne les réservations pour une date et un créneau donnés
    getKayakReservationsForTimeslot(date, timeslot) {
      return this.reservations.filter(r => r.date === date && r.timeslot === timeslot);
    }
  }
});
