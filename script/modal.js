// Récupération des éléments HTML
var emailGroup = document.getElementById("email-group");
var passwordGroup = document.getElementById("password-group");
var emailInput = document.getElementById("email");
var passwordInput = document.getElementById("password");
var nextBtn = document.getElementById("nextBtn");
var modal = document.getElementById("myModal");

// Fonction pour afficher l'input pour le mot de passe
function showPasswordInput() {
  emailGroup.style.display = "none";
  passwordGroup.style.display = "block";
  passwordInput.focus();
}

// Événement pour le clic sur le bouton suivant
nextBtn.addEventListener("click", function() {
  if (emailGroup.style.display !== "none" && emailInput.value !== "") {
    showPasswordInput();
  }
});

// Événement pour la fermeture du modal
// $('#myModal').on('hidden.bs.modal', function () {
//   emailGroup.style.display = "block";
//   passwordGroup.style.display = "none";
//   emailInput.value = "";
//   passwordInput.value = "";
// });

$('#myModal').on('hidden.bs.modal', function () {
    console.log("Modal fermé");
    emailGroup.style.display = "block";
    passwordGroup.style.display = "none";
    emailInput.value = "";
    passwordInput.value = "";
});

// Affichage du modal
$('#myModal').modal('show');