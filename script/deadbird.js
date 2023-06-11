const audio = new Audio("assets/FUSIL.mp3");
let dead = document.getElementById("centerererer")
let input = document.getElementById("myInput")
let prog = document.getElementById("progress")
const saw = new Audio("assets/Saw.mp3");

function simulateClick() {
  var body = document.getElementsByTagName('body')[0];
  body.click();
  // saw.play();
}

window.addEventListener("DOMContentLoaded", event => {
  // audio.volume = 0.2;
  saw.play();
  console.log(saw)
});

document.body.addEventListener("mouseover",() =>{ 
  saw.play();

})
// appel de la fonction une fois après que la page a fini de charger
window.addEventListener('load', function() {
  simulateClick();
});
document.body.dispatchEvent(new MouseEvent('click'));

document.body.addEventListener("click", () => {
  console.log("AAAAAAAAAAH")
  // saw.play();
})

dead.addEventListener("click", () => {
  audio.play();
  window.setTimeout(dedbird, 1400);
})

function dedbird() {
  dead.innerHTML = ' <img src="mytwittlogo.png" alt="">';
  // document.body.style.backgroundImage='url("https://img1.picmix.com/output/stamp/normal/7/9/6/1/321697_e1d12.gif")';
  // document.body.style.backgroundRepeat="no-repeat";
  // document.body.style.backgroundSize="cover";
  document.body.style.backgroundImage = 'url("https://cdn.wallpapersafari.com/73/78/Xp6OTU.jpg")';

  window.setTimeout(move, 1000);

}
function move() {
  document.location.href = "accueil.php?page=8";
}

const phrase = "K I L L";
const mots = phrase.split(" ");
const texte = document.getElementById("texte");
texte.innerHTML = "";

let i = 0;
const speed = 2000;
setTimeout(afficherMot, 3000);

function afficherMot() {
  if (i < mots.length) {
    texte.innerHTML += mots[i] + " ";
    i++;
    setTimeout(afficherMot, speed);
  }
}

