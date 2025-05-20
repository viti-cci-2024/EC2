// Fichier de test pour vérifier que les nouveaux fichiers sont accessibles
console.log('=== FICHIER DEBUG-TEST.JS CHARGÉ AVEC SUCCÈS ===');
console.log('Date/heure de création : 2025-05-20 19:51');

// Fonction pour tester l'accès à l'API
async function testAPIAccess() {
    try {
        console.log('Test d\'accès à l\'API...');
        const testURL = 'http://localhost:8000/api/bungalow-availability';
        console.log('URL testée:', testURL);
        
        const response = await fetch(testURL, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Test': 'true'
            }
        });
        
        console.log('Réponse API de test:', {
            status: response.status,
            ok: response.ok,
            statusText: response.statusText
        });
        
        const data = await response.text();
        console.log('Données reçues:', data.substring(0, 100) + (data.length > 100 ? '...' : ''));
        
        return { success: response.ok, data };
    } catch (error) {
        console.error('Erreur lors du test d\'accès API:', error);
        return { success: false, error: error.message };
    }
}

// Exécuter le test
window.runAPITest = function() {
    console.log('Exécution du test d\'API...');
    return testAPIAccess();
};

// Exposer une fonction pour vérifier si le mécanisme de fallback est désactivé
window.checkIfFallbackDisabled = function() {
    console.log('Vérification du mécanisme de fallback...');
    return {
        fileLoaded: true,
        timestamp: new Date().toISOString(),
        message: 'Si vous voyez ce message, les nouveaux fichiers JavaScript sont bien chargés'
    };
};

// Alerter visuellement que le fichier est chargé
console.log('%c DEBUG TEST CHARGÉ', 'background: #ff0000; color: white; padding: 5px; font-size: 16px; font-weight: bold;');
