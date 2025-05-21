<?php

namespace App\Observers;

use App\Models\Reservation;

class ReservationObserver
{
    /**
     * Handle the Reservation "creating" event.
     */
    public function creating(Reservation $reservation): void
    {
        // Assurer que le nom est correctement formaté
        $this->formatLastName($reservation);
        
        // Générer un numéro de réservation si nécessaire
        $this->generateReservationNumber($reservation);
        
        // Remplir le type de bungalow si nécessaire
        $this->setBungalowType($reservation);
    }
    
    /**
     * Formate le nom pour éviter les problèmes SQL
     */
    private function formatLastName(Reservation $reservation): void
    {
        // Nettoyer et formater le nom sans ajouter d'apostrophes
        if (is_string($reservation->last_name) && !empty($reservation->last_name)) {
            // Nettoyer la chaîne
            $cleanName = trim($reservation->last_name);
            
            // Retirer les apostrophes si elles existent déjà
            if (substr($cleanName, 0, 1) === "'" && substr($cleanName, -1) === "'") {
                $cleanName = substr($cleanName, 1, -1);
            }
            
            $reservation->last_name = $cleanName;
        }
    }
    
    /**
     * Générer un numéro de réservation si non défini
     */
    private function generateReservationNumber(Reservation $reservation): void
    {
        // Générer un numéro de réservation si non défini
        if (empty($reservation->numero)) {
            $lastReservation = Reservation::orderBy('id', 'desc')
                ->where('numero', 'like', 'CH%')
                ->first();
            
            $newNumber = 1;
            $year = date('y');
            $month = date('m');
            
            if ($lastReservation && preg_match('/^CH\d{4}(\d+)$/', $lastReservation->numero, $matches)) {
                $newNumber = intval($matches[1]) + 1;
            }
            
            $reservation->numero = 'CH' . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }
    }
    
    /**
     * Définir le type de bungalow en fonction du bungalow_id
     */
    private function setBungalowType(Reservation $reservation): void
    {
        // Si le type de bungalow n'est pas déjà défini mais que l'ID est présent
        if (empty($reservation->bungalow_type) && !empty($reservation->bungalow_id)) {
            try {
                // Récupérer le bungalow associé
                $bungalow = \App\Models\Bungalow::find($reservation->bungalow_id);
                
                if ($bungalow) {
                    // Définir le type de bungalow
                    $reservation->bungalow_type = $bungalow->type;
                }
            } catch (\Exception $e) {
                // Logger l'erreur mais ne pas interrompre la création
                \Illuminate\Support\Facades\Log::error('Erreur lors de la récupération du type de bungalow: ' . $e->getMessage());
            }
        }
    }
}
