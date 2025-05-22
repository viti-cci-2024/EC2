<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLogin
{
    /**
     * Le nombre maximum de tentatives de connexion autorisées.
     *
     * @var int
     */
    protected $maxAttempts = 5;

    /**
     * Nombre de minutes pendant lesquelles les tentatives sont mémorisées.
     *
     * @var int
     */
    protected $decayMinutes = 3;

    /**
     * Le limiteur de taux qui gère les tentatives de connexion.
     *
     * @var \Illuminate\Cache\RateLimiter
     */
    protected $limiter;

    /**
     * Créer une nouvelle instance de middleware.
     *
     * @param  \Illuminate\Cache\RateLimiter  $limiter
     * @return void
     */
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Gérer une requête entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ne s'applique qu'aux requêtes POST de connexion
        if ($request->method() === 'POST') {
            // Créer une clé unique basée sur l'adresse IP et l'email
            $key = $this->resolveRequestSignature($request);

            // Vérifier si la limite a été atteinte
            if ($this->limiter->tooManyAttempts($key, $this->maxAttempts)) {
                return $this->buildResponse($key);
            }

            // Incrémenter le compteur de tentatives
            $this->limiter->hit($key, $this->decayMinutes * 60);
        }

        // Continuer le traitement de la requête
        $response = $next($request);

        // Si la réponse indique un échec de connexion, on laisse le compteur tel quel
        // Sinon, on réinitialise le compteur
        if ($this->loginSuccessful()) {
            $this->clearLoginAttempts($request);
        }

        return $response;
    }

    /**
     * Résoudre la signature de la requête pour le rate limiting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function resolveRequestSignature(Request $request): string
    {
        return sha1(
            $request->ip() .
            Str::lower($request->input('email'))
        );
    }

    /**
     * Créer une réponse HTTP lorsque la limite a été atteinte.
     *
     * @param  string  $key
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildResponse(string $key): Response
    {
        $retryAfter = $this->limiter->availableIn($key);

        $message = 'Trop de tentatives de connexion. Veuillez réessayer dans ' .
                   ceil($retryAfter / 60) . ' minutes.';

        if (request()->expectsJson()) {
            return response()->json(['message' => $message], 429);
        }

        return redirect()->back()
            ->withInput(request()->except('password'))
            ->withErrors(['email' => $message]);
    }

    /**
     * Déterminer si la connexion a réussi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return bool
     */
    protected function loginSuccessful(): bool
    {
        // Si l'utilisateur est authentifié après cette requête, la connexion a réussi
        return auth()->check();
    }

    /**
     * Effacer les tentatives de connexion pour cette demande.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function clearLoginAttempts(Request $request): void
    {
        $this->limiter->clear($this->resolveRequestSignature($request));
    }
}
