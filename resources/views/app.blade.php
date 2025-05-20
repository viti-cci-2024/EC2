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
    <!-- Script de débogage pour surveiller les clics sur le bouton Réserver -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('%c === SCRIPT DE SURVEILLANCE DE DEBUGGING ACTIVÉ ===', 'background: #e74c3c; color: white; padding: 4px 10px; border-radius: 3px; font-size: 14px;');
        
        // Utiliser MutationObserver pour détecter les nouveaux boutons ajoutés par Vue.js
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                    const buttons = document.querySelectorAll('button');
                    buttons.forEach(button => {
                        if (button.textContent.trim() === 'Réserver' && !button.hasAttribute('data-debug-attached')) {
                            button.setAttribute('data-debug-attached', 'true');
                            console.log('%c Bouton "Réserver" détecté, attachement du débogage...', 'background: #f39c12; color: white; padding: 2px 5px; border-radius: 3px;');
                            
                            // Ajouter un écouteur pour logger les clics
                            button.addEventListener('click', function(event) {
                                console.log('%c === CLIC SUR BOUTON RÉSERVER DÉTECTÉ ===', 'background: #2ecc71; color: white; padding: 4px 10px; border-radius: 3px; font-size: 14px;');
                                console.log('Bouton cliqué:', button);
                                
                                // Vérifier les champs du formulaire
                                setTimeout(() => {
                                    // Vérifier les requêtes réseau
                                    console.log('%c Pour déboguer, ouvrez l\'onglet Réseau des DevTools et filtrez sur "bungalow"', 'background: #16a085; color: white; padding: 2px 5px; border-radius: 3px;');
                                }, 100);
                            });
                        }
                    });
                }
            });
        });
        
        // Observer tout le document pour détecter les changements
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
        
        // Log pour confirmer que le script est chargé
        console.log('%c Script de surveillance activé, attente de clics sur le bouton Réserver...', 'background: #27ae60; color: white; padding: 2px 5px; border-radius: 3px;');
    });
    </script>
    
    <!-- Script d'interception fonctionnel pour le bouton Réserver -->
    <script>
    // Fonction qui exécute la réservation directement via fetch API
    function createReservationDirectly(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        console.log('%c === FONCTION DE SOUMISSION DIRECTE ACTIVÉE ===', 'background: #2c3e50; color: white; padding: 4px 10px; border-radius: 3px; font-size: 14px;');
        
        // Collecter les données du formulaire
        let lastName = '';
        const nameInputs = document.querySelectorAll('input[type="text"]');
        for (const input of nameInputs) {
            if (input.value) {
                lastName = input.value;
                console.log('Nom trouvé:', lastName);
                break;
            }
        }
        
        // Type de bungalow et bungalow_id
        let bungalowType = 'mer'; // valeur par défaut
        let bungalowId = 1; // Par défaut: premier bungalow mer
        
        const jardinRadio = document.querySelector('input[id*="jardin"]:checked');
        if (jardinRadio) {
            bungalowType = 'jardin';
            bungalowId = 6; // Premier bungalow jardin
        }
        
        // Récupérer les dates
        let startDate = '';
        let endDate = '';
        const dateInputs = document.querySelectorAll('input[type="date"]');
        if (dateInputs.length >= 2) {
            startDate = dateInputs[0].value;
            endDate = dateInputs[1].value;
        }
        
        // Si les dates ne sont pas disponibles via les inputs, essayons de les extraire du texte
        if (!startDate || !endDate) {
            const texts = document.body.innerText;
            const dateMatches = texts.match(/202[0-9]-[0-9]{2}-[0-9]{2}/g);
            if (dateMatches && dateMatches.length >= 2) {
                startDate = dateMatches[0];
                endDate = dateMatches[1];
            }
        }
        
        // Nombre de personnes
        let personCount = 1;
        const personSelect = document.querySelector('select');
        if (personSelect) {
            personCount = parseInt(personSelect.value) || 1;
        }
        
        // Créer le payload
        const payload = {
            last_name: lastName,
            bungalow_id: bungalowId,
            start_date: startDate,
            end_date: endDate,
            person_count: personCount
        };
        
        console.log('%c Payload prêt pour soumission directe:', 'background: #2980b9; color: white; padding: 2px 5px; border-radius: 3px;', payload);
        
        // Envoyer la requête API
        return fetch('/api/bungalow-reservation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-Direct-Submit': 'true'
            },
            body: JSON.stringify(payload)
        })
        .then(response => {
            console.log('%c Réponse API reçue:', 'background: #16a085; color: white; padding: 2px 5px; border-radius: 3px;', response.status, response.statusText);
            return response.json().catch(e => {
                console.error('Erreur parsing JSON:', e);
                return { error: 'Erreur parsing JSON' };
            });
        })
        .then(async (data) => {
            console.log('%c Données de réponse:', 'background: #27ae60; color: white; padding: 2px 5px; border-radius: 3px;', data);
            
            // Utiliser directement le numéro de réservation reçu du backend
            let formattedNumber = '';
            if (data.reservation_number) {
                console.log('%c Réservation créée avec succès! Numéro de réservation:', 'background: #2ecc71; color: white; padding: 2px 5px; border-radius: 3px;', data.reservation_number);
                
                // Utiliser directement le numéro généré par le backend au format CH25050003
                formattedNumber = data.reservation_number;
                console.log('%c Numéro utilisé:', 'background: #2ecc71; color: white; padding: 2px 5px; border-radius: 3px;', formattedNumber);
                
                // IMPORTANT: Mettre à jour le numéro dans la base de données pour qu'il ait le format correct
                // Envoyer une demande pour mettre à jour le numéro de réservation dans la base de données
                try {
                    const updateResponse = await fetch(`/api/update-reservation-number/${data.reservation.id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({ reservation_number: formattedNumber })
                    });
                    
                    const updateResult = await updateResponse.json();
                    console.log('Mise à jour du numéro de réservation:', updateResult);
                } catch (error) {
                    console.error('Erreur lors de la mise à jour du numéro:', error);
                    // On continue même en cas d'erreur
                }
                
                // Remplir la modale de confirmation
                document.getElementById('modal-name').textContent = lastName;
                document.getElementById('modal-start-date').textContent = startDate;
                document.getElementById('modal-end-date').textContent = endDate;
                document.getElementById('modal-room-type').textContent = bungalowType === 'mer' ? 'Bungalow mer' : 'Bungalow jardin';
                document.getElementById('modal-person-count').textContent = personCount;
                document.getElementById('modal-reservation-number').textContent = formattedNumber;
                
                // Afficher la modale avec display:flex
                setTimeout(() => {
                    const modal = document.getElementById('confirmation-modal');
                    if (modal) {
                        modal.style.display = 'flex';
                        console.log('Modale affichée avec succès');
                    } else {
                        console.error('Impossible de trouver la modale');
                    }
                }, 500);
            }
            
            return {...data, formatted_number: formattedNumber};
        })
        .catch(error => {
            console.error('%c Erreur lors de la requête API:', 'background: #c0392b; color: white; padding: 2px 5px; border-radius: 3px;', error);
        });
    }
    
    // Attacher la fonction aux clics sur le bouton Réserver
    document.addEventListener('click', function(e) {
        if (e.target.tagName === 'BUTTON' && e.target.textContent.trim() === 'Réserver') {
            console.log('%c Interception du clic sur Réserver', 'background: #8e44ad; color: white; padding: 2px 5px; border-radius: 3px;');
            e.preventDefault();
            e.stopPropagation();
            
            // Exécuter notre méthode directe
            createReservationDirectly(e);
            
            // Simuler un succès pour l'interface
            setTimeout(() => {
                const step3 = document.querySelector('#confirmation, .confirmation');
                if (step3) step3.scrollIntoView({ behavior: 'smooth' });
            }, 1000);
            
            return false;
        }
    }, true); // true pour mode capture, pour intercepter avant Vue.js
    </script>
    <!-- Modale de confirmation de réservation avec style inline pour garantir l'application -->
    <div id="confirmation-modal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.7); display: none; justify-content: center; align-items: center; z-index: 10000;">
        <div style="background-color: white; padding: 25px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); width: 550px; max-width: 90%;">
            <div style="font-size: 1.35rem; font-weight: bold; margin-bottom: 1.2rem;">Votre réservation a été enregistrée !</div>
            <div style="margin-bottom: 0.7rem;">
                <span style="font-weight: 600;">Nom :</span> <span id="modal-name"></span>
            </div>
            <div style="margin-bottom: 0.7rem;">
                <span style="font-weight: 600;">Du :</span> <span id="modal-start-date"></span> <span style="font-weight: 600;">au</span> <span id="modal-end-date"></span>
            </div>
            <div style="margin-bottom: 0.7rem;">
                <span style="font-weight: 600;">Type de chambre :</span> <span id="modal-room-type"></span>
            </div>
            <div style="margin-bottom: 0.7rem;">
                <span style="font-weight: 600;">Nombre de personnes :</span> <span id="modal-person-count"></span>
            </div>
            <div style="margin-bottom: 0.7rem;">
                Votre n° de réservation est : <span style="color: #10B981; font-weight: bold;" id="modal-reservation-number"></span>
            </div>
            <div style="color: #EF4444; font-weight: bold; margin-top: 1rem;">
                Notez bien ce numéro qui vous permettra de réserver vos activités
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <button style="background-color: #3B82F6; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-size: 1rem; cursor: pointer;" onclick="document.getElementById('confirmation-modal').style.display = 'none'; window.location.reload();">Fermer</button>
            </div>
        </div>
    </div>
</body>
</html>
