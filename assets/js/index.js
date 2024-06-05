
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


