# Modifications des Formulaires HTML - Migration vers WebPrime Webhook

## Date de modification
2025-12-21

## Fichiers modifies (11 fichiers)

1. tarif-chauffeur-prive.html
2. transfert-aeroport.html
3. mise-a-disposition-vtc.html
4. location-chauffeur-sans-voiture.html
5. garde-du-corps.html
6. deplacements-professionnels.html
7. chauffeur-prive-paris-75.html
8. chauffeur-prive-95.html
9. chauffeur-prive-93.html
10. chauffeur-prive-78.html
11. chauffeur-mariage.html

## Modifications effectuees

### 1. Balise de formulaire
**AVANT:**
```html
<form action="https://formsubmit.co/formulaire@webprime.fr" method="POST" onsubmit="return validateForm()">
```

**APRES:**
```html
<form id="contactForm" onsubmit="return submitToWebPrime(event)">
```

### 2. Champ honeypot anti-spam
**AVANT:**
```html
<input type="text" id="website" name="website" autocomplete="off">
```

**APRES:**
```html
<input type="text" id="website" name="_gotcha" autocomplete="off">
```

### 3. Champs hidden supprimes
Les lignes suivantes ont ete supprimees:
- `<input type="hidden" name="_next" value="https://chauffeur-prive-paris-idf.fr/">`
- `<input type="hidden" name="_cc" value="contact.mdvtc.privatedriver@gmail.com">`
- `<input type="hidden" name="_blacklist" value="...">`
- `<input type="hidden" name="_from" value="MD VTC">`
- `<input type="hidden" name="_subject" value="Formulaire">`
- `<input type="hidden" name="_template" value="table">`

**CONSERVE:**
- `<input type="hidden" name="_captcha" value="true">`

### 4. Suppression des accents dans les labels
**AVANT:**
- `<label for="name">Nom / Prénom</label>`
- `<label for="tel">Téléphone :</label>`
- `<label for="departure1">Départ</label>`
- `<label for="arrival1">Arrivée</label>`
- `<label for="numero">Numéro de vol ou train</label>`
- `<label for="car-seats">Sièges auto</label>`
- `<label for="rehausseur">Réhausseur</label>`

**APRES:**
- `<label for="name">Nom / Prenom</label>`
- `<label for="tel">Telephone :</label>`
- `<label for="departure1">Depart</label>`
- `<label for="arrival1">Arrivee</label>`
- `<label for="numero">Numero de vol ou train</label>`
- `<label for="car-seats">Sieges auto</label>`
- `<label for="rehausseur">Rehausseur</label>`

### 5. Suppression des accents dans les options
**AVANT:**
```html
<option value="Transfert aéroport">Transfert aéroport</option>
<option value="MAD">Mise à disposition</option>
<option value="Déplacement pro">Déplacement professionnel</option>
<option value="Trajet Privé">Trajet Privé</option>
```

**APRES:**
```html
<option value="Transfert aeroport">Transfert aeroport</option>
<option value="MAD">Mise a disposition</option>
<option value="Deplacement pro">Deplacement professionnel</option>
<option value="Trajet Prive">Trajet Prive</option>
```

### 6. Fonction JavaScript - Remplacement complet
**AVANT:** Fonction `validateForm()`

**APRES:** Nouvelle fonction `submitToWebPrime()`
```javascript
async function submitToWebPrime(e) {
    e.preventDefault();
    var form = e.target;
    var btn = form.querySelector('button[type="submit"]');
    var originalText = btn.textContent;

    // Anti-spam honeypot check
    var honeypot = form.querySelector('input[name="_gotcha"]');
    if (honeypot && honeypot.value) return false;

    btn.textContent = 'Envoi en cours...';
    btn.disabled = true;

    try {
        var formData = new FormData(form);
        var response = await fetch('https://webprime.app/webhook/contact/272e326a45cd4bac546192db1418b56149a9d7c18271dc7a4506eafcfe8ab524', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            window.location.href = 'https://chauffeur-prive-paris-idf.fr/';
        } else {
            alert('Erreur lors de l\'envoi. Veuillez reessayer.');
            btn.textContent = originalText;
            btn.disabled = false;
        }
    } catch (error) {
        alert('Erreur de connexion. Veuillez reessayer.');
        btn.textContent = originalText;
        btn.disabled = false;
    }
    return false;
}
```

## Caracteristiques de la nouvelle solution

### Webhook WebPrime
- **URL:** `https://webprime.app/webhook/contact/272e326a45cd4bac546192db1418b56149a9d7c18271dc7a4506eafcfe8ab524`
- **Methode:** POST
- **Format:** FormData

### Protection anti-spam
- Champ honeypot `_gotcha` (invisible pour les utilisateurs)
- Validation cote serveur via le webhook

### Experience utilisateur
- Message "Envoi en cours..." pendant l'envoi
- Desactivation du bouton pour eviter les doubles soumissions
- Redirection automatique vers la page d'accueil en cas de succes
- Messages d'erreur clairs en cas de probleme
- Gestion des erreurs de connexion

### Caracteres ASCII uniquement
- Tous les accents ont ete supprimes des labels et options
- Compatible avec tous les systemes et encodages
- Evite les problemes d'affichage

## Notes importantes

1. **Redirection:** Apres un envoi reussi, l'utilisateur est redirige vers `https://chauffeur-prive-paris-idf.fr/`

2. **Compatibilite:** La nouvelle fonction utilise `async/await` et `fetch`, compatible avec tous les navigateurs modernes (IE11+ non supporte)

3. **Encodage:** Les fichiers sont sauvegardes en UTF-8

4. **Backup:** Il est recommande de conserver une sauvegarde des fichiers originaux avant cette mise a jour

## Tests recommandes

1. Tester l'envoi d'un formulaire sur chaque page modifiee
2. Verifier que les emails arrivent correctement
3. Tester avec et sans JavaScript active
4. Verifier la redirection apres soumission
5. Tester les messages d'erreur en cas de probleme de connexion

## Contact
Pour toute question ou probleme, contactez WebPrime.
