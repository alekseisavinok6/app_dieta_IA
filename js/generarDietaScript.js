campos = {
  nivelActividad: false,
  objetivo: false,
  comidasDias: false,
  preferencias: false,
};

const generarForm = document.getElementById("generar-form");
const selectsGenerar = document.querySelectorAll("#generar-form select");

const validarForm = (e) => {
  switch (e.target.name) {
    case "nivelActividad":
      if (
        e.target.value == 1.2 ||
        e.target.value == 1.4 ||
        e.target.value == 1.65 ||
        e.target.value == 2
      ) {
        campos["nivelActividad"] = true;
      } else {
        campos["nivelActividad"] = false;
      }
      break;
    case "objetivo":
      if (
        e.target.value === "mantenerPeso" ||
        e.target.value === "subirPeso" ||
        e.target.value === "bajarPeso"
      ) {
        campos["objetivo"] = true;
      } else {
        campos["objetivo"] = false;
      }
      break;
    case "comidasDias":
      if (e.target.value == 3 || e.target.value == 4 || e.target.value == 5) {
        campos["comidasDias"] = true;
      } else {
        campos["comidasDias"] = false;
      }
      break;
    case "preferencias":
      if (
        e.target.value === "" ||
        e.target.value === "vegetariana" ||
        e.target.value === "vegana" ||
        e.target.value === "sinGluten" ||
        e.target.value === "cetogenica"
      ) {
        campos["preferencias"] = true;
      } else {
        campos["preferencias"] = false;
      }
      console.log(e.target.value);
      break;
    default:
      break;
  }
};

// TODOS LOS SELECTS
selectsGenerar.forEach((select) => {
  select.addEventListener("change", validarForm);
  select.addEventListener("blur", validarForm);
});

// SELECTS
const nivelActividad = document.getElementById("nivelActividad");
const objetivo = document.getElementById("objetivo");
const comidasDias = document.getElementById("comidasDias");
const preferencias = document.getElementById("preferencias");

window.addEventListener("DOMContentLoaded", () => {
  if (
    nivelActividad.value == 1.2 ||
    nivelActividad.value == 1.4 ||
    nivelActividad.value == 1.65 ||
    nivelActividad.value == 2
  ) {
    campos["nivelActividad"] = true;
  }
  if (
    objetivo.value === "subirPeso" ||
    objetivo.value === "mantenerPeso" ||
    objetivo.value === "bajarPeso"
  ) {
    campos["objetivo"] = true;
  }
  if (
    comidasDias.value == 3 ||
    comidasDias.value == 4 ||
    comidasDias.value == 5
  ) {
    campos["comidasDias"] = true;
  }
  if (
    preferencias.value === "" ||
    preferencias.value === "vegetariana" ||
    preferencias.value === "vegana" ||
    preferencias.value === "cetogenica" ||
    preferencias.value === "sinGluten"
  ) {
    campos["preferencias"] = true;
  }
});

// COMENTARIO
const comentario = document.getElementById("comentario");
// comentario.addEventListener("");

generarForm.addEventListener("submit", (e) => {
  e.preventDefault();
  if (
    campos["nivelActividad"] &&
    campos["objetivo"] &&
    campos["comidasDias"] &&
    campos["preferencias"]
  ) {
    document.querySelector(".form-msg").classList.add("hidden");
    generarForm.submit();
    generarForm.reset();
  } else {
    document.querySelector(".form-msg").classList.remove("hidden");
  }
});
