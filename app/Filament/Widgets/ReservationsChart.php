<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\ChartWidget;

class ReservationsChart extends ChartWidget
{
    // Désactiver temporairement l'affichage du widget
    public static function canView(): bool
    {
        return false; // Widget temporairement désactivé
    }
    protected static ?string $heading = 'Évolution des réservations';
    
    protected static ?string $pollingInterval = null;
    
    // Désactiver la mise en cache pour forcer la mise à jour des données à chaque rafraîchissement
    protected function getCacheLifetime(): ?int
    {
        return null; // Désactive complètement la mise en cache
    }
    
    protected static ?string $maxHeight = '300px';
    
    // Faire en sorte que le widget occupe toute la largeur
    protected int | string | array $columnSpan = 'full';
    
    protected function getData(): array
    {
        // Année en cours pour le filtrage des données
        $currentYear = now()->year;
        
        // Utiliser une requête SQL directe pour obtenir le nombre de réservations par mois pour l'année en cours
        $reservationsByMonth = DB::table('reservations')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Préparer les données pour le graphique
        $labels = [];
        $data = [];
        
        // Générer tous les mois de l'année
        for ($month = 1; $month <= 12; $month++) {
            $monthDate = Carbon::createFromDate($currentYear, $month, 1);
            $labels[] = $monthDate->format('M Y');
            
            // Chercher le nombre de réservations pour ce mois
            $found = false;
            foreach ($reservationsByMonth as $item) {
                if ($item->month === $month) {
                    $data[] = $item->count;
                    $found = true;
                    break;
                }
            }
            
            // Si aucune réservation ce mois-ci, ajouter 0
            if (!$found) {
                $data[] = 0;
            }
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Nombre de réservations',
                    'data' => $data,
                    'backgroundColor' => '#5F5AEF',
                    'borderColor' => '#5F5AEF',
                ],
            ],
            'labels' => $labels,
        ];
    }
    
    protected function getType(): string
    {
        return 'line';
    }
    
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
