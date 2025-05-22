<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XssSanitization
{
    /**
     * Les attributs à exclure du nettoyage XSS.
     *
     * @var array
     */
    protected $except = [
        'password',
        'password_confirmation',
        '_token',
        '_method',
    ];

    /**
     * Gérer une requête entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ne pas nettoyer pour les requêtes ajax, les demandes de fichiers ou les requêtes Livewire/Filament
        $isLivewireRequest = $request->has('fingerprint') || 
                          strpos($request->path(), 'livewire') !== false ||
                          strpos($request->path(), 'admin') !== false;
                          
        if (!$request->ajax() && count($request->allFiles()) === 0 && !$isLivewireRequest) {
            $input = $request->all();
            
            array_walk_recursive($input, function (&$input, $key) {
                if (!in_array($key, $this->except) && is_string($input)) {
                    // Nettoyer les entrées texte
                    $input = $this->clean($input);
                }
            });
            
            $request->merge($input);
        }
        
        return $next($request);
    }
    
    /**
     * Nettoyer une chaîne de texte pour prévenir les attaques XSS.
     *
     * @param  string  $string
     * @return string
     */
    protected function clean(string $string): string
    {
        // Supprimer les balises de script et les événements inline
        $string = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $string);
        
        // Supprimer les événements JavaScript inline
        $string = preg_replace('/on\w+\s*=\s*"[^"]*"/i', '', $string);
        $string = preg_replace('/on\w+\s*=\s*\'[^\']*\'/i', '', $string);
        
        // Convertir les caractères spéciaux en entités HTML
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
