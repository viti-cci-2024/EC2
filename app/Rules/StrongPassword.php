<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    /**
     * Longueur minimale pour le mot de passe.
     *
     * @var int
     */
    protected $minLength = 8;

    /**
     * Détermine si le mot de passe doit contenir au moins une lettre majuscule.
     *
     * @var bool
     */
    protected $requireUppercase = true;

    /**
     * Détermine si le mot de passe doit contenir au moins un chiffre.
     *
     * @var bool
     */
    protected $requireNumeric = true;

    /**
     * Détermine si le mot de passe doit contenir au moins un caractère spécial.
     *
     * @var bool
     */
    protected $requireSpecialChar = true;

    /**
     * Créer une nouvelle règle de validation.
     *
     * @param  int  $minLength
     * @param  bool  $requireUppercase
     * @param  bool  $requireNumeric
     * @param  bool  $requireSpecialChar
     * @return void
     */
    public function __construct(int $minLength = 8, bool $requireUppercase = true, bool $requireNumeric = true, bool $requireSpecialChar = true)
    {
        $this->minLength = $minLength;
        $this->requireUppercase = $requireUppercase;
        $this->requireNumeric = $requireNumeric;
        $this->requireSpecialChar = $requireSpecialChar;
    }

    /**
     * Exécuter la règle de validation.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $errors = [];
        
        if (strlen($value) < $this->minLength) {
            $errors[] = "Le mot de passe doit faire au moins {$this->minLength} caractères.";
        }

        if ($this->requireUppercase && !preg_match('/[A-Z]/', $value)) {
            $errors[] = 'Le mot de passe doit contenir au moins une lettre majuscule.';
        }

        if ($this->requireNumeric && !preg_match('/\d/', $value)) {
            $errors[] = 'Le mot de passe doit contenir au moins un chiffre.';
        }

        if ($this->requireSpecialChar && !preg_match('/[^A-Za-z0-9]/', $value)) {
            $errors[] = 'Le mot de passe doit contenir au moins un caractère spécial.';
        }
        
        // Rapporter toutes les erreurs trouvées
        foreach ($errors as $error) {
            $fail($error);
        }
    }
}
