window.onload = function () {
    const nightModeCheckbox = document.getElementById("night-mode-checkbox");
    const body = document.querySelector("body");
    const stick = document.getElementById("sticky")
    
    nightModeCheckbox.addEventListener("change", function() {
      if (nightModeCheckbox.checked) {
        body.classList.add("night-mode");
        stick.classList.add("night-mode");

      } else {
        body.classList.remove("night-mode");
        stick.classList.remove("night-mode");

      }
    });
};