<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class ApiThrottleMiddleware
{
    /**
     * Le nombre maximum de requêtes autorisées par minute.
     *
     * @var int
     */
    protected $maxAttempts = 60;

    /**
     * Le limiteur de taux.
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
        // Générer une clé unique pour cette adresse IP
        $key = $this->resolveRequestSignature($request);

        // Vérifier si la limite a été atteinte
        if ($this->limiter->tooManyAttempts($key, $this->maxAttempts)) {
            return $this->buildResponse($key);
        }

        // Incrémenter le compteur de tentatives (valide pour 1 minute)
        $this->limiter->hit($key, 60);

        // Ajouter des en-têtes de rate limiting à la réponse
        $response = $next($request);
        return $this->addHeaders(
            $response,
            $this->maxAttempts,
            $this->limiter->attempts($key),
            $this->limiter->availableIn($key)
        );
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
            $request->getRequestUri() .
            $request->getMethod()
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

        $headers = [
            'X-RateLimit-Limit' => $this->maxAttempts,
            'X-RateLimit-Remaining' => 0,
            'X-RateLimit-Reset' => $this->availableAt($retryAfter),
            'Retry-After' => $retryAfter,
        ];

        return response()->json([
            'message' => 'Trop de requêtes. Veuillez réessayer après ' . $retryAfter . ' secondes.',
            'retry_after' => $retryAfter
        ], 429, $headers);
    }

    /**
     * Ajouter les en-têtes de limitation de taux à la réponse.
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @param  int  $maxAttempts
     * @param  int  $remainingAttempts
     * @param  int|null  $retryAfter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function addHeaders(Response $response, int $maxAttempts, int $remainingAttempts, ?int $retryAfter = null): Response
    {
        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', $remainingAttempts);

        if (! is_null($retryAfter)) {
            $response->headers->set('X-RateLimit-Reset', $this->availableAt($retryAfter));
            $response->headers->set('Retry-After', $retryAfter);
        }

        return $response;
    }

    /**
     * Calculer le timestamp auquel les requêtes seront à nouveau disponibles.
     *
     * @param  int  $seconds
     * @return int
     */
    protected function availableAt(int $seconds): int
    {
        return time() + $seconds;
    }
}
