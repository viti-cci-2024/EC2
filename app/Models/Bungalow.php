<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bungalow extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'type',
        'capacite',
        'disponible',
    ];

    protected $casts = [
        'disponible' => 'boolean',
    ];

    /**
     * Les réservations pour ce bungalow
     */
    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'reservation_bungalow')
            ->withPivot('nb_personnes')
            ->withTimestamps();
    }
}
