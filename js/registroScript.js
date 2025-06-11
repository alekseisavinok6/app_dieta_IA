const registroForm = document.getElementById("registro-form");
const inputsRegistro = document.querySelectorAll("#registro-form input");

const expresiones = {
  nombre: /^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ\s']{3,40}$/, // Nombre de por lo menos 2 caracteres, sin signos especiales (a excepción algunos)
  correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9]+\.[a-zA-Z0-9-.]+$/, // Correo con @, con punto y caracter después del punto
  password: /^.{12,}$/, // 8 caracteres o más
};

const campos = {
  nombre: false,
  apellido: false,
  correo: false,
  peso: false,
  altura: false,
  fNacimiento: false,
  sexo: false, // Este no es un input es un select
  alergenos: false,
  intolerancias: false,
  enfermedades: false,
  password: false,
  password2: false,
};

const validarFormulario = (e) => {
  switch (e.target.name) {
    case "nombre":
      validarCampo(expresiones.nombre, e.target, "nombre");
      break;
    case "apellido":
      validarCampo(expresiones.nombre, e.target, "apellido");
      break;
    case "correo":
      validarCampo(expresiones.correo, e.target, "correo");
      break;
    case "peso":
      validarCampo(expresiones.peso, e.target, "peso");
      break;
    case "altura":
      validarCampo(expresiones.altura, e.target, "altura");
      break;
    case "f_nacimiento":
      validarCampo(expresiones.altura, e.target, "fNacimiento");
      break;
    case "sexo":
      const inputError = document.querySelector(`.input-sexo-error`);
      if (e.target.value === "Hombre" || e.target.value === "Mujer") {
        e.target.classList.remove("invalid");
        inputError.classList.add("no-display");
        campos["sexo"] = true;
      } else {
        e.target.classList.add("invalid");
        inputError.classList.remove("no-display");
        campos["sexo"] = false;
      }
      break;
    case "alergenos[]":
      validarGrupoCheckbox("alergenos");
      break;
    case "intolerancias[]":
      validarGrupoCheckbox("intolerancias");
      break;
    case "enfermedades[]":
      validarGrupoCheckbox("enfermedades");
      break;
    case "password":
      validarCampo(expresiones.password, e.target, "password");
      break;
    case "password2":
      validarPassword2();
      break;
    default:
      break;
  }
};

// VALIDAR CAMPOS
function validarCampo(expresion, input, campo) {
  const inputError = document.querySelector(`.input-${campo}-error`);
  // VALIDAR PESO
  if (input.name === "peso") {
    const peso = parseInt(input.value);
    if (peso >= 20 && peso <= 300) {
      input.classList.remove("invalid");
      inputError.classList.add("no-display");
      campos[campo] = true;
    } else if (input.value.length < 1) {
      input.classList.remove("invalid");
      inputError.classList.add("no-display");
      campos[campo] = false;
    } else {
      input.classList.add("invalid");
      inputError.classList.remove("no-display");
      campos[campo] = false;
    }
    //VALIDAR ALTURA
  } else if (input.name === "altura") {
    const altura = parseInt(input.value);
    if (altura >= 50 && altura <= 250) {
      input.classList.remove("invalid");
      inputError.classList.add("no-display");
      campos[campo] = true;
    } else if (input.value.length < 1) {
      input.classList.remove("invalid");
      inputError.classList.add("no-display");
      campos[campo] = false;
    } else {
      input.classList.add("invalid");
      inputError.classList.remove("no-display");
      campos[campo] = false;
    }
    // VALIDAR F_NACIMIENTO
  } else if (input.name === "f_nacimiento") {
    if (input.value >= "1925-01-01" && input.value <= "2020-12-31") {
      input.classList.remove("invalid");
      inputError.classList.add("no-display");
      campos[campo] = true;
    } else {
      input.classList.add("invalid");
      inputError.classList.remove("no-display");
      campos[campo] = false;
    }
  } else {
    //VALIDAR NOMBRE || APELLIDO || CORREO || CONTRASEÑA
    if (expresion.test(input.value)) {
      input.classList.remove("invalid");
      inputError.classList.add("no-display");
      campos[campo] = true;
    } else if (input.value.length < 1) {
      input.classList.remove("invalid");
      inputError.classList.add("no-display");
      campos[campo] = false;
    } else {
      input.classList.add("invalid");
      inputError.classList.remove("no-display");
      campos[campo] = false;
    }
  }
}

// VALIDAR CHECKBOXES
function validarGrupoCheckbox(nombreGrupo) {
  const checkboxes = document.querySelectorAll(
    `input[name="${nombreGrupo}[]"]`
  );
  const grupoContainer = document.querySelector(`.${nombreGrupo}-container`);
  const inputError = document.querySelector(`.input-${nombreGrupo}-error`);
  //   const valores = [];
  // NO TE DEJA ESCOGER "NINGUNA" SI YA TIENE OTRA SELECCIONADA
  checkboxes.forEach((checkbox) => {
    const esNinguna = checkbox.value === "NULL"; //true o false
    if (checkbox.checked && esNinguna) {
      checkboxes.forEach((cb) => {
        if (cb.value !== "NULL") cb.checked = false;
      });
    } else if (checkbox.checked && !esNinguna) {
      checkboxes.forEach((cb) => {
        if (cb.value === "NULL") cb.checked = false;
      });
    }
  });

  if (Array.from(checkboxes).some((checkbox) => checkbox.checked)) {
    grupoContainer.classList.remove("invalid");
    inputError.classList.add("no-display");
    campos[`${nombreGrupo}`] = true;
  } else {
    grupoContainer.classList.add("invalid");
    inputError.classList.remove("no-display");
    campos[`${nombreGrupo}`] = false;
  }
}

// VALIDAR CAMPO PASSWORD2
function validarPassword2() {
  const inputPassword1 = document.getElementById("password");
  const inputPassword2 = document.getElementById("password2");
  if (inputPassword1.value === inputPassword2.value) {
    inputPassword2.classList.remove("invalid");
    document
      .querySelector(".input-password2-error")
      .classList.add("no-display");
    campos["password2"] = true;
  } else if (inputPassword2.value.length < 1) {
    inputPassword2.classList.remove("invalid");
    document
      .querySelector(".input-password2-error")
      .classList.add("no-display");
    campos["password2"] = false;
  } else {
    inputPassword2.classList.add("invalid");
    document
      .querySelector(".input-password2-error")
      .classList.remove("no-display");
    campos["password2"] = false;
  }
}

// INPUTS REGISTRO FORM
inputsRegistro.forEach((input) => {
  if (input.type !== "checkbox") {
    input.addEventListener("keyup", validarFormulario);
    input.addEventListener("blur", validarFormulario);
    // PARA LOS CHECKBOX
  } else {
    input.addEventListener("change", validarFormulario);
  }
});

// INPUT SEXO (SELECT)
const inputSexo = document.getElementById("sexo");
inputSexo.addEventListener("change", validarFormulario);
inputSexo.addEventListener("blur", validarFormulario);
// SE VALIDA AL CARGAR LA PÁGINA (HOMBRE POR DEFECTO)
window.addEventListener("DOMContentLoaded", () => {
  if (inputSexo.value === "Hombre" || inputSexo.value === "Mujer") {
    campos["sexo"] = true;
  }
});

registroForm.addEventListener("submit", (e) => {
  e.preventDefault();
  if (
    campos["nombre"] &&
    campos["apellido"] &&
    campos["correo"] &&
    campos["peso"] &&
    campos["altura"] &&
    campos["fNacimiento"] &&
    campos["sexo"] &&
    campos["alergenos"] &&
    campos["intolerancias"] &&
    campos["enfermedades"] &&
    campos["password"] &&
    campos["password2"]
  ) {
    document.querySelector(".form-msg").classList.add("hidden");
    registroForm.submit();
    registroForm.reset();
  } else {
    document.querySelector(".form-msg").classList.remove("hidden");
  }
});
