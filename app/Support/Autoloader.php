<?php

namespace App\Support;

/**
 * Autoloader personnalisé pour charger notre version de NumberFormatter
 * lorsque l'extension intl n'est pas disponible
 */
class Autoloader
{
    public static function register()
    {
        // Vérifie si la classe NumberFormatter existe déjà (extension intl chargée)
        if (!class_exists('NumberFormatter', false)) {
            // Si non, définit la classe dans l'espace de noms global
            class_alias(NumberFormatter::class, 'NumberFormatter');
        }
    }
}
