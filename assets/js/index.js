
const value = document.querySelector("#value");
const input = document.querySelector("#people");
value.textContent = input.value;
input.addEventListener("input", (event) => {
    value.textContent = event.target.value;
});

const btnOptions = document.querySelector('.btnOptions');
const options = document.querySelector('.options');
btnOptions.addEventListener('click', (e)=>{
    e.preventDefault();
    console.log('click');
    options.classList.toggle('show');
})