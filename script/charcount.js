const myInput = document.getElementById("myInput");
const charCount = document.getElementById("charCount");
let prog = document.getElementById("progress");
let botox = document.getElementById("boutonique");
myInput.addEventListener("input", function() {
  const inputText = myInput.value;
  const count = inputText.length;
  charCount.textContent = count;

  prog.innerHTML="<progress value='"+count+"' max=140></progress>";

  if (count == 0){
    botox.style.cursor= 'not-allowed'
    botox.setAttribute("disabled", "");
  }
  else if(count > 120 && count <= 140){
    prog.style.accentColor= 'rgb(41, 0, 107)';
    myInput.style.color = 'white';
    botox.style.cursor= 'pointer'
    botox.disabled = false;
  }

  else if(count<=120 && count > 0)
  {

    prog.style.accentColor= '#1A8CD8';
    myInput.style.color = 'white';
    botox.removeAttribute("disabled");



  }
  else if (count>140){
    prog.style.accentColor= 'red';
    myInput.style.color = 'red';
    botox.style.cursor= 'not-allowed'
    botox.disabled = true;
  }
  
  // else if (count > 0){
  //   prog.style.visibility="visible";
  // }
});
