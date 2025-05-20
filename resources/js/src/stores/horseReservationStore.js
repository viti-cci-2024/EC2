import { defineStore } from 'pinia';

// Liste de tous les chevaux disponibles (à adapter selon vos assets)
const ALL_HORSES = [
  'Apache', 'Mustang', 'Sahara', 'Comète', 'Eclair', 'Paillettes', 'Koné',
  'Confiture', 'Foster', 'Inanouï', 'Prince', 'Buster', 'Charly', 'Sao', 
  'Tim', 'Tam', 'Nidguep', 'Papirus'
];

export const useHorseReservationStore = defineStore('horseReservation', {
  state: () => ({
    reservations: [],       // liste de toutes les réservations de randonnées à cheval
    horses: ALL_HORSES,     // répertoire de tous les chevaux (noms, images, etc.)
  }),
  actions: {
    init() {
      const stored = localStorage.getItem('horseReservations');
      if (stored) {
        this.reservations = JSON.parse(stored);
      }
    },
    updateLocalStorage() {
      localStorage.setItem('horseReservations', JSON.stringify(this.reservations));
    },
    addReservation(resData) {
      // Génération du numéro unique RAAAMM000x
      const now = new Date();
      const year = now.getFullYear().toString().slice(-2);
      const month = ('0' + (now.getMonth() + 1)).slice(-2);
      const count = this.reservations.length + 1;
      const sequential = ('0000' + count).slice(-4);
      const numero = `RA${year}${month}${sequential}`;

      const newReservation = {
        ...resData,
        numero,
        createdAt: now.toISOString()
      };
      this.reservations.push(newReservation);
      this.updateLocalStorage();
      return newReservation;
    },
    // Récupération des chevaux déjà attribués pour un créneau (date + timeslot)
    getHorsesForTimeslot(date, timeslot) {
      // Filtrer les réservations ayant date + timeslot identiques
      const existing = this.reservations.filter(r =>
        r.date === date && r.timeslot === timeslot
      );
      // On combine tous les chevaux déjà sélectionnés dans ces réservations
      const usedHorses = existing.flatMap(r => r.selectedHorses);
      return usedHorses; // Liste des noms de chevaux déjà utilisés
    }
  }
});
