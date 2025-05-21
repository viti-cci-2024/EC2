<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'bungalow_id',
        'bungalow_type',
        'last_name',
        'start_date',
        'end_date',
        'person_count',
        'numero',
        // Laisse aussi les anciens si tu veux gérer d'autres types de réservation
        'client_id',
        'date_debut',
        'date_fin',
        'cree_par',
    ];
    
    /**
     * Cast des attributs
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'person_count' => 'integer',
    ];

    /**
     * Le client qui a fait cette réservation
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * L'utilisateur qui a créé cette réservation
     */
    public function createur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'cree_par');
    }

    /**
     * Le bungalow réservé (relation directe)
     */
    public function bungalow(): BelongsTo
    {
        return $this->belongsTo(Bungalow::class);
    }
    
    /**
     * Les bungalows réservés (relation many-to-many via table pivot)
     */
    public function bungalows(): BelongsToMany
    {
        return $this->belongsToMany(Bungalow::class, 'reservation_bungalow')
            ->withPivot('nb_personnes')
            ->withTimestamps();
    }

    /**
     * Les tables de repas réservées
     */
    public function tablesRepas(): BelongsToMany
    {
        return $this->belongsToMany(TableRepas::class, 'reservation_table_repas')
            ->withPivot('nb_personnes')
            ->withTimestamps();
    }

    /**
     * Les kayaks réservés
     */
    public function kayaks(): BelongsToMany
    {
        return $this->belongsToMany(Kayak::class, 'reservation_kayak')
            ->withPivot('nb_personnes')
            ->withTimestamps();
    }

    /**
     * Les activités réservées
     */
    public function activites(): BelongsToMany
    {
        return $this->belongsToMany(Activite::class, 'reservation_activite')
            ->withPivot('date_activite', 'nb_personnes', 'nb_enfants', 'commentaire')
            ->withTimestamps();
    }
}
