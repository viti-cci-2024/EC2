import axios from 'axios';

const API_URL = '/api';

export default {
  /**
   * Récupère toutes les réservations
   */
  getAllReservations() {
    return axios.get(`${API_URL}/reservations`);
  },

  /**
   * Récupère une réservation par son ID
   * @param {number} id - ID de la réservation
   */
  getReservation(id) {
    return axios.get(`${API_URL}/reservations/${id}`);
  },

  /**
   * Crée une nouvelle réservation
   * @param {Object} reservationData - Données de la réservation
   */
  createReservation(reservationData) {
    return axios.post(`${API_URL}/reservations`, reservationData);
  },

  /**
   * Met à jour une réservation existante
   * @param {number} id - ID de la réservation
   * @param {Object} reservationData - Nouvelles données de la réservation
   */
  updateReservation(id, reservationData) {
    return axios.put(`${API_URL}/reservations/${id}`, reservationData);
  },

  /**
   * Supprime une réservation
   * @param {number} id - ID de la réservation
   */
  deleteReservation(id) {
    return axios.delete(`${API_URL}/reservations/${id}`);
  },

  /**
   * Récupère tous les bungalows disponibles
   */
  getBungalows() {
    return axios.get(`${API_URL}/bungalows`);
  },

  /**
   * Récupère toutes les tables de repas disponibles
   */
  getTablesRepas() {
    return axios.get(`${API_URL}/tables-repas`);
  },

  /**
   * Récupère tous les kayaks disponibles
   */
  getKayaks() {
    return axios.get(`${API_URL}/kayaks`);
  },

  /**
   * Récupère toutes les activités
   */
  getActivites() {
    return axios.get(`${API_URL}/activites`);
  },

  /**
   * Récupère tous les clients
   */
  getClients() {
    return axios.get(`${API_URL}/clients`);
  }
};
