<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">

        <title>{{ config('app.name', 'EC2') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Scripts and Styles -->
        @vite(['resources/js/main.js'])
        
        <!-- Debug Script - Remove in production -->
        <script src="/debug-test.js?v={{ time() }}"></script>
        <script>
            // Script de vérification directe
            console.log('%c TEST DE CONSOLE DIRECT - ' + new Date().toISOString(), 'background: purple; color: white; padding: 5px;');
            
            // Fonction globale pour tester l'API
            window.testAPI = async function() {
                try {
                    const response = await fetch('/api/bungalow-availability', {
                        method: 'GET',
                        headers: { 'Accept': 'application/json' }
                    });
                    
                    console.log('Test API direct - Status:', response.status);
                    const data = await response.json();
                    console.log('Test API direct - Data:', data);
                    return data;
                } catch (error) {
                    console.error('Test API direct - Erreur:', error);
                    return { error: error.message };
                }
            };
            
            // Attacher au chargement de la page
            window.addEventListener('load', function() {
                console.log('Page chargée - Vérification du debug actif...');
                if (typeof window.checkIfFallbackDisabled === 'function') {
                    const result = window.checkIfFallbackDisabled();
                    console.log('Résultat de checkIfFallbackDisabled:', result);
                } else {
                    console.error('La fonction checkIfFallbackDisabled n\'est pas disponible ! Le fichier debug-test.js n\'est probablement pas chargé.');
                }
            });
        </script>
    </head>
    <body>
        <div id="app"></div>
        
        <!-- Bouton de test uniquement visible en mode développement -->
        @if(app()->environment('local'))
        <div id="debug-panel" style="position: fixed; bottom: 10px; right: 10px; background: rgba(0,0,0,0.8); color: white; padding: 10px; border-radius: 5px; z-index: 9999; font-family: monospace;">
            <div>Debug Tools</div>
            <button onclick="console.log(window.checkIfFallbackDisabled())" style="margin: 5px; padding: 3px 5px;">Vérifier Fallback</button>
            <button onclick="window.runAPITest().then(r => console.log('Résultat test API:', r))" style="margin: 5px; padding: 3px 5px;">Test API</button>
            <button onclick="console.clear()" style="margin: 5px; padding: 3px 5px;">Clear Console</button>
        </div>
        @endif
    </body>
</html>
