<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TableRepas extends Model
{
    use HasFactory;

    protected $table = 'table_repas';

    protected $fillable = [
        'nom',
        'capacite',
        'disponible',
    ];

    protected $casts = [
        'disponible' => 'boolean',
    ];

    /**
     * Les réservations pour cette table
     */
    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'reservation_table_repas')
            ->withPivot('nb_personnes')
            ->withTimestamps();
    }
}
