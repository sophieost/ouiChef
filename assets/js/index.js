const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))


//   FAIRE DISPARAITRE LA NAVBAR AU SCROLL

let prevScrollPos = window.scrollY;
const navbar = document.querySelector('.navbar');

window.addEventListener('scroll', () => {
    const currentScrollPos = window.scrollY;

    if (prevScrollPos > currentScrollPos) {
        // défilement vers le haut : réapparition de la barre de navigation
        navbar.style.top = '0';
        navbarOuiChef.style.opacity = '1';

    } else {
        // défilement vers le bas : masquage de la barre de navigation
        navbar.style.top = '-110px';
        navbarOuiChef.style.opacity = '0';
    }

    prevScrollPos = currentScrollPos;
});




// FONCTION POUR AFFICHER LE NOMBRE DE PERSONNES ET LE NOMBRE DE JOURS(FORMULAIRE MENUS)

document.addEventListener('DOMContentLoaded', (event) => {
    // Fonction pour mettre à jour l'output pour le nombre de jours
    function updateJours() {
        var slider = document.getElementById('nb_jours');
        var output = document.getElementById('valueJours');
        output.value = slider.value; // Affiche la valeur du slider dans l'output
        output.innerHTML = slider.value; // Met à jour le texte de l'output
    }

    // Fonction pour mettre à jour l'output pour le nombre de personnes
    function updatePers() {
        var slider = document.getElementById('nb_pers');
        var output = document.getElementById('valuePers');
        output.value = slider.value; // Affiche la valeur du slider dans l'output
        output.innerHTML = slider.value; // Met à jour le texte de l'output
    }

    // Ajoutez des écouteurs d'événements pour mettre à jour les outputs lors du changement des sliders
    document.getElementById('nb_jours').addEventListener('input', updateJours);
    document.getElementById('nb_pers').addEventListener('input', updatePers);

    // Appel initial des fonctions pour afficher les valeurs par défaut
    updateJours();
    updatePers();
});




// APPARITION DES OPTIONS DU MENU

const btnOptions = document.querySelector('.btnOptions');
const options = document.querySelector('.options');
btnOptions.addEventListener('click', (e) => {
    e.preventDefault();
    // console.log('click');
    options.classList.toggle('show');
})



// FONCTION POUR FAIRE APPARAITRE/DISPARAITRE LE MDP

function showPass() {
    if (mdp.type === "password") {
        mdp.type = "text";
        confirmMdp.type = "text";
    } else {
        mdp.type = "password";
        confirmMdp.type = "password";
    }
}

function showPassConnexion() {
    if (mdpConnect.type === "password") {
        mdpConnect.type = "text";

    } else {
        mdpConnect.type = "password";

    }
}



//   BOUTON FAVORI

let linkFav = document.getElementsByClassName('linkFav');
let iconFav = document.getElementsByClassName('iconFav');

linkFav.addEventListener('click', () => {

    iconFav.style.color = 'red';
})



//  IMPRIMER UNE PAGE 

function imprimerPage() {
    containerList.print();
  }