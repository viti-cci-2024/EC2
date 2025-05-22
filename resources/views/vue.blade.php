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
        
        <!-- Le panneau de débogage a été retiré mais les fonctions restent disponibles via la console -->
    </body>
</html>
