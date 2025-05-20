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
    <!-- Script pour remplacer complètement le comportement du bouton Réserver -->
    <script>
    // Fonction qui exécute la réservation directement via fetch, comme notre bouton de débogage
    function createReservationDirectly() {
        console.log('--- FONCTION DE RÉSERVATION DIRECTE ACTIVÉE ---');
        
        // Inspecter l'interface utilisateur Vue pour récupérer les données du formulaire de façon robuste
        let lastName = '';
        
        // Essayer différentes méthodes pour récupérer le nom
        const nameInputs = [
            document.querySelector('#nom'),
            document.querySelector('input[placeholder="Votre nom"]'),
            document.querySelector('input[name="lastName"]'),
            document.querySelector('input[name="last_name"]'),
            // Chercher tous les inputs de type texte et prendre le premier
            document.querySelector('input[type="text"]')
        ];
        
        // Utiliser le premier input disponible qui a une valeur
        for (const input of nameInputs) {
            if (input && input.value) {
                lastName = input.value;
                break;
            }
        }
        
        // Obtenir le nom depuis le contenu de la page si disponible
        if (!lastName) {
            const confirmationElement = document.querySelector('.reservation-form h3, .confirmation h3');
            if (confirmationElement) {
                const confirmationText = confirmationElement.textContent;
                const nameMatch = confirmationText.match(/Nom\s*:\s*([^\n]+)/);
                if (nameMatch && nameMatch[1]) {
                    lastName = nameMatch[1].trim();
                }
            }
        }
        
        // Fallback si aucune méthode ne fonctionne
        if (!lastName) {
            lastName = 'Client_' + new Date().getTime();
            console.log('Aucun nom trouvé, utilisation du nom par défaut:', lastName);
        }
        let bungalowType = '';
        let bungalowId = 1; // Par défaut: premier bungalow mer
        
        // Déterminer le type de bungalow sélectionné
        const merSelected = document.querySelector('input[id*="mer"]:checked');
        const jardinSelected = document.querySelector('input[id*="jardin"]:checked');
        
        if (jardinSelected) {
            bungalowType = 'jardin';
            bungalowId = 6; // Premier bungalow jardin
        } else if (merSelected) {
            bungalowType = 'mer';
            bungalowId = 1; // Premier bungalow mer
        }
        
        // Récupérer les dates de la réservation - approche plus robuste
        let startDate = '';
        let endDate = '';
        
        // Méthode 1: Essayer les inputs de type date
        const dateInputs = document.querySelectorAll('input[type="date"]');
        if (dateInputs.length >= 2) {
            startDate = dateInputs[0].value;
            endDate = dateInputs[1].value;
            console.log('Dates trouvées via inputs type date:', startDate, endDate);
        }
        
        // Méthode 2: Chercher dans la configuration Vue.js
        if (!startDate || !endDate) {
            // Chercher dans le contenu des balises de texte
            const dateTexts = Array.from(document.querySelectorAll('span, p, div'))
                .map(el => el.textContent)
                .filter(text => text.match(/202[0-9]-[0-9]{2}-[0-9]{2}/));
                
            if (dateTexts.length >= 2) {
                // Extraire les dates au format YYYY-MM-DD
                const dateMatches = dateTexts.join(' ').match(/202[0-9]-[0-9]{2}-[0-9]{2}/g);
                if (dateMatches && dateMatches.length >= 2) {
                    startDate = dateMatches[0];
                    endDate = dateMatches[1];
                    console.log('Dates trouvées via le texte:', startDate, endDate);
                }
            }
        }
        
        // Méthode 3: Chercher dans la page de confirmation
        if (!startDate || !endDate) {
            const confirmationText = document.body.innerText;
            const dateMatches = confirmationText.match(/Du\s*:\s*(202[0-9]-[0-9]{2}-[0-9]{2})\s*au\s*(202[0-9]-[0-9]{2}-[0-9]{2})/i);
            if (dateMatches && dateMatches.length >= 3) {
                startDate = dateMatches[1];
                endDate = dateMatches[2];
                console.log('Dates trouvées via le texte de confirmation:', startDate, endDate);
            }
        }
        
        // Fallback: définir des dates par défaut si aucune date n'est trouvée
        if (!startDate || !endDate) {
            // Calculer des dates par défaut: aujourd'hui et dans 3 jours
            const today = new Date();
            const startDay = new Date(today.getTime() + 86400000); // Début: demain
            const endDay = new Date(today.getTime() + 4 * 86400000); // Fin: dans 4 jours
            
            startDate = startDay.toISOString().split('T')[0];
            endDate = endDay.toISOString().split('T')[0];
            console.log('Utilisation des dates par défaut:', startDate, endDate);
        }
        
        // Récupérer le nombre de personnes
        const personCountSelect = document.querySelector('select[id*="person"]');
        let personCount = 1;
        
        if (personCountSelect) {
            personCount = parseInt(personCountSelect.value) || 1;
        }
        
        console.log('Données récupérées par le script débogage:', {
            lastName,
            bungalowType,
            bungalowId,
            startDate,
            endDate,
            personCount
        });
        
        // Préparer le payload pour l'API
        const payload = {
            last_name: lastName,
            bungalow_id: bungalowId,
            start_date: startDate,
            end_date: endDate,
            person_count: personCount
        };
        
        // Envoyer la requête API
        return fetch('/api/bungalow-reservation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Debug': 'true'
            },
            body: JSON.stringify(payload)
        })
        .then(response => {
            console.log('Réponse API reçue:', response.status, response.statusText);
            return response.json().then(data => {
                // Retourner à la fois la réponse et les données pour traitement
                return { response, data };
            });
        })
        .then(({ response, data }) => {
            // Vérifier si la réponse indique une erreur (status >= 400)
            if (!response.ok) {
                // Afficher les détails des erreurs de validation dans la console
                console.error('Erreur API:', data);
                
                // Extraire et formater les messages d'erreur
                let errorMessage = 'Erreur lors de la réservation:\n';
                
                if (data.message) {
                    errorMessage += data.message + '\n';
                }
                
                if (data.errors) {
                    for (const field in data.errors) {
                        errorMessage += `${field}: ${data.errors[field].join(', ')}\n`;
                    }
                }
                
                // Afficher l'erreur à l'utilisateur
                alert(errorMessage);
                throw new Error(errorMessage);
            }
            
            // Si tout va bien, continuer avec le traitement des données
            console.log('Réservation créée avec succès!', data);
            
            // Afficher la confirmation avec le numéro de réservation
            if (data.reservation_number) {
                alert(`Réservation créée avec succès!\nNuméro: ${data.reservation_number}`);
            } else {
                alert('Réservation créée avec succès!');
            }
            
            return data;
        })
        .catch(error => {
            console.error('Erreur lors de la création de la réservation:', error);
            
            // Si l'erreur n'a pas déjà été affichée (comme dans le bloc ci-dessus)
            if (!error.message.includes('Erreur lors de la réservation:')) {
                alert('Erreur technique: ' + error.message);
            }
            
            throw error;
        });
    }
    
    // Attacher un écouteur d'événements global pour intercepter tous les clics
    document.addEventListener('click', function(event) {
        // Vérifier si le clic est sur un bouton avec le texte "Réserver"
        const target = event.target;
        
        if (target.tagName === 'BUTTON' && target.textContent.trim() === 'Réserver') {
            console.log('Bouton Réserver cliqué, intercepté par le gestionnaire global!');
            
            // Empêcher le comportement par défaut
            event.preventDefault();
            event.stopImmediatePropagation();
            
            // Exécuter notre fonction de réservation directe
            createReservationDirectly()
                .then(() => {
                    console.log('Traitement de réservation terminé');
                })
                .catch(err => {
                    console.error('Erreur finale:', err);
                });
                
            return false;
        }
    }, true); // Utiliser capture pour s'assurer que nous attrapons l'événement avant les autres gestionnaires
    </script>
</body>
</html>
