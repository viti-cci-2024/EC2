// garderieReservationStore.js
import { defineStore } from 'pinia';

// Fonctions utilitaires pour la gestion des horaires
function convertTimeToMinutes(time) {
  const [hours, minutes] = time.split(':').map(Number);
  return hours * 60 + minutes;
}

function overlaps(start1, end1, start2, end2) {
  const s1 = convertTimeToMinutes(start1);
  const e1 = convertTimeToMinutes(end1);
  const s2 = convertTimeToMinutes(start2);
  const e2 = convertTimeToMinutes(end2);
  // Les intervalles se chevauchent si s1 < e2 et s2 < e1
  return s1 < e2 && s2 < e1;
}

export const useGarderieReservationStore = defineStore('garderieReservation', {
  state: () => ({
    reservations: []
  }),
  actions: {
    init() {
      const stored = localStorage.getItem('garderieReservations');
      if (stored) {
        this.reservations = JSON.parse(stored);
      }
    },
    updateLocalStorage() {
      localStorage.setItem('garderieReservations', JSON.stringify(this.reservations));
    },
    addReservation(data) {
      const now = new Date();
      const year = now.getFullYear().toString().slice(-2);
      const month = ('0' + (now.getMonth() + 1)).slice(-2);
      const count = this.reservations.length + 1;
      const sequential = ('0000' + count).slice(-4);
      const numero = `GA${year}${month}${sequential}`;
      const newReservation = { ...data, numero, createdAt: now.toISOString() };
      this.reservations.push(newReservation);
      this.updateLocalStorage();
      return newReservation;
    },
    // Retourne le total des enfants pour les réservations se chevauchant avec le créneau donné (date + horaire)
    getGarderieChildrenForTimeRange(date, newStartTime, newEndTime) {
      return this.reservations
        .filter(r => r.date === date && overlaps(r.startTime, r.endTime, newStartTime, newEndTime))
        .reduce((acc, r) => acc + r.children, 0);
    }
  }
});
