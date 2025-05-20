<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/svg+xml" href="/vite.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Gîte Pim - Système de Réservation</title>
    
    <!-- Scripts de débogage - PAS POUR LA PRODUCTION -->
    <script>
        // Outils de test API directs (chargés avant Vue)
        window.directAPITest = async function(endpoint, method = 'GET', data = null) {
            console.log('%c Test API direct vers: ' + endpoint, 'background: #e67e22; color: white; padding: 3px; border-radius: 3px;');
            try {
                const options = {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                };
                
                if (data && method !== 'GET') {
                    options.body = JSON.stringify(data);
                }
                
                console.log('Options de requête:', options);
                
                // Test avec URL absolue
                const useAbsoluteUrl = true;
                const baseUrl = useAbsoluteUrl ? 'http://localhost:8000' : '';
                const url = baseUrl + endpoint;
                
                console.log('URL utilisée:', url);
                const response = await fetch(url, options);
                
                console.log('Statut réponse:', response.status, response.statusText);
                let responseData;
                
                try {
                    const text = await response.text();
                    console.log('Réponse texte brut:', text);
                    responseData = text ? JSON.parse(text) : null;
                } catch (e) {
                    console.error('Erreur parsing JSON:', e);
                    return { success: false, error: 'Erreur format réponse', response };
                }
                
                return { 
                    success: response.ok, 
                    status: response.status,
                    data: responseData,
                    response: response
                };
            } catch (error) {
                console.error('Erreur lors de la requête API:', error);
                return { success: false, error: error.message };
            }
        };
        
        // Fonction pour soumission directe d'une réservation
        window.submitDirectReservation = async function() {
            const reservationData = {
                last_name: 'Test_Direct_' + Date.now(),
                bungalow_id: 1,  // Utiliser un ID existant
                start_date: '2025-06-01',
                end_date: '2025-06-05',
                person_count: 2
            };
            
            console.log('Envoi direct de réservation avec données:', reservationData);
            return await window.directAPITest('/api/bungalow-reservation', 'POST', reservationData);
        };
    </script>
    
    <!-- Scripts et Styles compilés de l'application Vue originale -->
    <script type="module" crossorigin src="/assets/index-DMr_eH6K.js"></script>
    <link rel="stylesheet" crossorigin href="/assets/index-BHjqM1t9.css">
</head>
<body>
    <div id="app"></div>
    
    <!-- Panneau de débogage pour tester l'API directement -->
    @if(app()->environment('local'))
    <div id="direct-debug-panel" style="position: fixed; bottom: 10px; right: 10px; background: rgba(0,0,0,0.8); color: white; padding: 10px; border-radius: 5px; z-index: 9999; font-family: monospace;">
        <div>Débogage API Direct</div>
        <button onclick="window.directAPITest('/api/bungalow-availability').then(r => console.log('Test disponibilité:', r));" style="margin: 5px; padding: 3px 5px;">Test Disponibilité</button>
        <button onclick="window.submitDirectReservation().then(r => console.log('Réservation directe:', r));" style="margin: 5px; padding: 3px 5px;">Créer Réservation</button>
        <button onclick="console.clear()" style="margin: 5px; padding: 3px 5px;">Effacer Console</button>
    </div>
    @endif
</body>
</html>
