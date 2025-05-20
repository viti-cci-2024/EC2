<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kayak extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'disponible',
    ];

    protected $casts = [
        'disponible' => 'boolean',
    ];

    /**
     * Les réservations pour ce kayak
     */
    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'reservation_kayak')
            ->withPivot('nb_personnes')
            ->withTimestamps();
    }
}
