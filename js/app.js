const palabras = [
  { texto: "inteligente", color: "#A8DADC" },   
  { texto: "nutritiva", color: "#F39C12" },    
  { texto: "saludable", color: "#FFE8D6" },     
  { texto: "equilibrada", color: "#E0FBFC" },  
  { texto: "personalizada", color: "#FCE4EC" }  
];

let index = 0;
const wordElement = document.getElementById("changing-word");

// Inicializar color y animación
wordElement.style.color = palabras[0].color;

setInterval(() => {
  wordElement.classList.remove("slide-in");
  wordElement.classList.add("slide-out");

  setTimeout(() => {
    index = (index + 1) % palabras.length;
    wordElement.textContent = palabras[index].texto;
    wordElement.style.color = palabras[index].color;

    wordElement.classList.remove("slide-out");
    wordElement.classList.add("slide-in");
  }, 500);
}, 3000);
