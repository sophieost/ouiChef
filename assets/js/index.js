
// FONCTION POUR AFFICHER LE NOMBRE DE PERSONNES (FORMULAIRE MENUS)

const value = document.querySelector("#value");
const input = document.querySelector("#people");
value.textContent = input.value;
input.addEventListener("input", (event) => {
    value.textContent = event.target.value;
});


// APPARITION DES OPTIONS DU MENU

const btnOptions = document.querySelector('.btnOptions');
const options = document.querySelector('.options');
btnOptions.addEventListener('click', (e) => {
    e.preventDefault();
    console.log('click');
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


