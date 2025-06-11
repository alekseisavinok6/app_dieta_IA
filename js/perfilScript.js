const campos = {
  altura: false,
  peso: false,
  pesoDeseado: false,
  alergia: false,
  intolerancia: false,
};

//ERROR
const error = document.querySelector(".input-registro-error");

//FORMS
const alturaForm = document.getElementById("altura-form");
const pesoForm = document.getElementById("peso-form");
const pesoDeseadoForm = document.getElementById("peso-deseado-form");

//INPUTS
const inputAltura = document.getElementById("altura");
const inputPeso = document.getElementById("peso");
const inputPesoDeseado = document.getElementById("pesoDeseado");

//VALIDACIONES
inputAltura.addEventListener("keyup", () => {
  const altura = parseInt(inputAltura.value);
  if (altura >= 50 && altura <= 250) {
    campos["altura"] = true;
    inputAltura.classList.remove("invalid");
    error.classList.add("no-display");
  } else {
    inputAltura.classList.add("invalid");
    campos["altura"] = false;
    error.classList.remove("no-display");
    error.innerHTML = "La altura introducida no es válida";
  }
});
inputPeso.addEventListener("keyup", () => {
  const peso = parseInt(inputPeso.value);
  if (peso >= 30 && peso <= 300) {
    campos["peso"] = true;
    inputPeso.classList.remove("invalid");
    error.classList.add("no-display");
  } else {
    inputPeso.classList.add("invalid");
    campos["peso"] = false;
    error.classList.remove("no-display");
    error.innerHTML = "El peso introducido no es válido";
  }
});
inputPesoDeseado.addEventListener("keyup", () => {
  const pesoDeseado = parseInt(inputPesoDeseado.value);
  if (pesoDeseado >= 30 && pesoDeseado <= 200) {
    campos["pesoDeseado"] = true;
    inputPesoDeseado.classList.remove("invalid");
    error.classList.add("no-display");
  } else {
    inputPesoDeseado.classList.add("invalid");
    campos["pesoDeseado"] = false;
    error.classList.remove("no-display");
    error.innerHTML = "El peso deseado introducido no es válido";
  }
});

//ALTURA
alturaForm.addEventListener("submit", (e) => {
  e.preventDefault();
  if (campos["altura"]) {
    alturaForm.submit();
    alturaForm.reset();
  }
});

//PESO
pesoForm.addEventListener("submit", (e) => {
  e.preventDefault();
  if (campos["peso"]) {
    pesoForm.submit();
    pesoForm.reset();
  }
});

//PESO DESEADO
pesoDeseadoForm.addEventListener("submit", (e) => {
  e.preventDefault();
  if (campos["pesoDeseado"]) {
    pesoDeseadoForm.submit();
    pesoDeseadoForm.reset();
  }
});
