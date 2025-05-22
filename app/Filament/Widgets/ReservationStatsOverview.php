<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use App\Models\Bungalow;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReservationStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    
    // Désactiver la mise en cache pour forcer la mise à jour des données à chaque rafraîchissement
    protected function getCacheLifetime(): ?int
    {
        return null; // Désactive complètement la mise en cache
    }
    
    // Constante pour les styles communs des cartes statistiques
    private const CARD_STYLE = 'rounded-xl border border-gray-200 p-6 shadow-sm';

    protected function getStats(): array
    {
        // Calculer la durée moyenne des séjours
        $averageStay = Reservation::selectRaw('ROUND(AVG(DATEDIFF(end_date, start_date)), 1) as avg_stay')
            ->first()
            ->avg_stay ?? 0;
            
        // Calculer le nombre moyen de personnes par réservation
        $averagePersons = Reservation::avg('person_count') ?? 0;
        
        // Calculer le nombre de réservations par type de bungalow directement depuis la table reservations
        $bungalowSeaCount = Reservation::where('bungalow_type', 'mer')->count();
        $bungalowGardenCount = Reservation::where('bungalow_type', 'jardin')->count();
        
        $totalReservations = $bungalowSeaCount + $bungalowGardenCount;
        $seaPercentage = $totalReservations > 0 ? round(($bungalowSeaCount / $totalReservations) * 100) : 0;
        $gardenPercentage = $totalReservations > 0 ? round(($bungalowGardenCount / $totalReservations) * 100) : 0;

        return [
            Stat::make('Durée moyenne des séjours', $averageStay . ' jours')
                ->description('Durée moyenne de toutes les réservations')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary')
                ->chart([7, 4, 5, 6, 3, 8, 3, 7, 8, 6, 5, 4])
                ->extraAttributes([
                    'class' => self::CARD_STYLE,
                ]),
                
            Stat::make('Nombre moyen de personnes', round($averagePersons, 1) . ' personnes')
                ->description('Personnes par réservation')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([3, 2, 1, 2, 3, 4, 2, 1, 3, 2, 4, 3])
                ->extraAttributes([
                    'class' => self::CARD_STYLE,
                ]),
                
            Stat::make('Répartition des bungalows', "Mer: {$seaPercentage}% | Jardin: {$gardenPercentage}%")
                ->description("Mer: {$bungalowSeaCount} | Jardin: {$bungalowGardenCount}")
                ->descriptionIcon('heroicon-m-home')
                ->color('info')
                ->chart([$bungalowSeaCount, $bungalowGardenCount])
                ->extraAttributes([
                    'class' => self::CARD_STYLE,
                ]),
        ];
    }
}
