<?php

namespace App\Support;

/**
 * Classe de remplacement simple pour NumberFormatter lorsque l'extension intl n'est pas disponible
 * Ne supporte que les fonctionnalités de base utilisées par Filament
 */
class NumberFormatter
{
    public const DECIMAL = 1;
    public const CURRENCY = 2;
    public const PERCENT = 3;
    
    private $locale;
    private $style;
    
    public function __construct($locale, $style)
    {
        $this->locale = $locale;
        $this->style = $style;
    }
    
    public function format($value)
    {
        if ($this->style === self::DECIMAL) {
            return number_format($value, 2, '.', ',');
        } elseif ($this->style === self::CURRENCY) {
            return '€' . number_format($value, 2, '.', ',');
        } elseif ($this->style === self::PERCENT) {
            return number_format($value * 100, 0) . '%';
        }
        
        return $value;
    }
    
    public function setAttribute($attr, $value)
    {
        // Stub - ne fait rien
        return true;
    }
}
