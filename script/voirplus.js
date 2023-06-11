let butplus = document.getElementById("voirplusid")
let butmoins = document.getElementById("voirmoinsid")

let textplus = document.getElementById("voirplustend")

butplus.addEventListener("click", () => {
textplus.style.display="block";
butplus.style.display="none";
})

butmoins.addEventListener("click", () => {
    textplus.style.display="none";
    butplus.style.display="block";
    })