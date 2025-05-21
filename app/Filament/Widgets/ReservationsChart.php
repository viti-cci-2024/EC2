<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\ChartWidget;

class ReservationsChart extends ChartWidget
{
    protected static ?string $heading = 'Évolution des réservations';
    
    protected static ?string $pollingInterval = '60s';
    
    protected static ?string $maxHeight = '300px';
    
    protected function getData(): array
    {
        // Récupérer les 6 derniers mois de données
        $startDate = now()->subMonths(6)->startOfMonth()->format('Y-m-d');
        $endDate = now()->endOfMonth()->format('Y-m-d');
        
        // Utiliser une requête SQL directe pour obtenir le nombre de réservations par mois
        $reservationsByMonth = DB::table('reservations')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Préparer les données pour le graphique
        $labels = [];
        $data = [];
        
        // Générer tous les mois sur 6 mois
        $startMonthDate = now()->subMonths(6)->startOfMonth();
        for ($i = 0; $i < 6; $i++) {
            $monthDate = (clone $startMonthDate)->addMonths($i);
            $yearMonth = $monthDate->format('Y-n');
            $labels[] = $monthDate->format('M Y');
            
            // Chercher le nombre de réservations pour ce mois
            $found = false;
            foreach ($reservationsByMonth as $item) {
                if ("{$item->year}-{$item->month}" === $yearMonth) {
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
