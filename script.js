// Script pour activer le lien sur le survol
const list = document.querySelectorAll('.list');
function activeLink() {
    list.forEach((item) =>
        item.classList.remove('active'));
    this.classList.add('active');
}
list.forEach((item) =>
    item.addEventListener('mouseover', activeLink));

// Script pour fermer le menu après le clic
document.addEventListener("DOMContentLoaded", function () {
    var menuToggle = document.getElementById("btn");
    var navLinks = document.querySelectorAll(".nav-menu a");
    navLinks.forEach(function (link) {
        link.addEventListener("click", function () {
            menuToggle.checked = false;
        });
    });
});

// Script pour valider le formulaire
function validateForm() {
    var nom = document.getElementById("name").value.trim();
    var telephone = document.getElementById("phone").value.trim();
    var prestation = document.getElementById("prestation").value.trim();
    var lieu_depart = document.getElementById("departure1").value.trim();
    var lieu_arrivee = document.getElementById("arrival1").value.trim();
    var date = document.getElementById("date").value.trim();
    var heure = document.getElementById("heure").value.trim();
    var passagers = document.getElementById("passengers").value.trim();
    var enfants = document.getElementById("children").value.trim();
    var bagages = document.getElementById("luggage").value.trim();
    var sieges_auto = document.getElementById("car-seats").value.trim();
    var rehausseur = document.getElementById("rehausseur").value.trim();
    var commentaires = document.getElementById("comments").value.trim();

    if (nom === "" || telephone === "" || prestation === "" || lieu_depart === "" || lieu_arrivee === "" || date === "" || heure === "" || passagers === "") {
        alert("Champs obligatoires.");
        return false;
    }

    var telPattern = /^(06|07|\+336|\+337)\d{8}$/;
    if (!telPattern.test(telephone)) {
        alert("Veuillez entrer un numéro de téléphone valide.");
        return false;
    }

    var linkPattern = /https?:\/\/[^\s]+|<a\s+href\s*=\s*['"][^\s>]+['"]/i;
    var scriptPattern = /<script[^>]*>[\s\S]*?<\/script>/gi;
    if (linkPattern.test(commentaires) || scriptPattern.test(commentaires)) {
        alert("Pas autorisés.");
        return false;
    }

    return true;
}

// Google Maps Autocomplete pour les champs départ et arrivée
function initAutocomplete() {
    var departureInput = document.getElementById('departure1');
    var arrivalInput = document.getElementById('arrival1');
    var autocompleteDeparture = new google.maps.places.Autocomplete(departureInput, {
        types: ['geocode']
    });
    var autocompleteArrival = new google.maps.places.Autocomplete(arrivalInput, {
        types: ['geocode']
    });
    autocompleteDeparture.addListener('place_changed', function () {
        var place = autocompleteDeparture.getPlace();
    });
    autocompleteArrival.addListener('place_changed', function () {
        var place = autocompleteArrival.getPlace();
    });
}

google.maps.event.addDomListener(window, 'load', initAutocomplete);

// Import des icônes Ionicons
import { defineCustomElements } from 'https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js';
defineCustomElements(window);
