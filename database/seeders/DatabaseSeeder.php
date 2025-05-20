<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Création d'un utilisateur administrateur
        \App\Models\Utilisateur::create([
            'nom' => 'Admin',
            'prenom' => 'System',
            'email' => 'admin@gitepim.fr',
            'mot_de_passe' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Création d'un utilisateur employé
        \App\Models\Utilisateur::create([
            'nom' => 'Employe',
            'prenom' => 'Test',
            'email' => 'employe@gitepim.fr',
            'mot_de_passe' => bcrypt('password'),
            'role' => 'employe',
        ]);

        // Création de quelques clients
        \App\Models\Client::create([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean.dupont@example.com',
            'telephone' => '0612345678',
        ]);

        \App\Models\Client::create([
            'nom' => 'Martin',
            'prenom' => 'Sophie',
            'email' => 'sophie.martin@example.com',
            'telephone' => '0687654321',
        ]);

        // Création de bungalows
        \App\Models\Bungalow::create([
            'nom' => 'Bungalow Mer 1',
            'type' => 'mer',
            'capacite' => 4,
            'disponible' => true,
        ]);

        \App\Models\Bungalow::create([
            'nom' => 'Bungalow Mer 2',
            'type' => 'mer',
            'capacite' => 6,
            'disponible' => true,
        ]);

        \App\Models\Bungalow::create([
            'nom' => 'Bungalow Jardin 1',
            'type' => 'jardin',
            'capacite' => 4,
            'disponible' => true,
        ]);

        // Création de tables de repas
        \App\Models\TableRepas::create([
            'nom' => 'Table 1',
            'capacite' => 4,
            'disponible' => true,
        ]);

        \App\Models\TableRepas::create([
            'nom' => 'Table 2',
            'capacite' => 6,
            'disponible' => true,
        ]);

        // Création de kayaks
        \App\Models\Kayak::create([
            'type' => 'simple',
            'disponible' => true,
        ]);

        \App\Models\Kayak::create([
            'type' => 'double',
            'disponible' => true,
        ]);

        // Création d'activités
        \App\Models\Activite::create([
            'nom' => 'Visite du bagne',
            'description' => 'Visite guidée du bagne historique',
        ]);

        \App\Models\Activite::create([
            'nom' => 'Randonnée équestre',
            'description' => 'Randonnée à cheval sur la plage',
        ]);

        \App\Models\Activite::create([
            'nom' => 'Garderie',
            'description' => 'Service de garderie pour enfants',
        ]);
    }
}
