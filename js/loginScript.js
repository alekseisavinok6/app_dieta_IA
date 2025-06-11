// FORMULARIO DE LOGIN Y REGISTRO
const loginForm = document.getElementById("login-form");
const registroForm = document.getElementById("registro-form");
//INPUTS DE LOS FORMULARIOS
const inputsLogin = document.querySelectorAll("#login-form input");
const inputsRegistro = document.querySelectorAll("#registro-form input");

// EXPRESIONES REGULARES
const expresiones = {
  correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9]+\.[a-zA-Z0-9-.]+$/, // Correo con @, con punto y caracter después del punto
  password: /^.{12,}$/, // 8 caracteres o más
};

const campos = {
  correo: false,
  password: false,
};

// VALIDACIONES
const validarFormulario = (e) => {
  switch (e.target.name) {
    // VALIDACION DE CORREO
    case "correo":
      validarCampo(expresiones.correo, e.target, "correo");
      break;
    case "password":
      validarCampo(expresiones.password, e.target, "password");
      break;
    default:
      break;
  }
};

const validarCampo = (expresion, input, campo) => {
  const inputError = document.querySelector(`.input-${campo}-error`);
  //VALIDAR (CORREO || PASSWORD)
  // EJEMPLO: validarCampo = (expresiones.correo, e.target, correo)...
  if (expresion.test(input.value)) {
    input.classList.remove("invalid");
    if (inputError) {
      inputError.classList.add("no-display");
    }
    campos[campo] = true;
  } else if (input.value.length < 1) {
    input.classList.remove("invalid");
    if (inputError) {
      inputError.classList.add("no-display");
    }
    campos[campo] = false;
  } else {
    input.classList.add("invalid");
    if (inputError) {
      inputError.classList.remove("no-display");
    }
    campos[campo] = false;
  }
};

// INPUTS DEL LOGIN
inputsLogin.forEach((input) => {
  input.addEventListener("keyup", validarFormulario);
  input.addEventListener("blur", validarFormulario);
});

// FORMULARIO LOGIN
if (loginForm) {
  loginForm.addEventListener("submit", (e) => {
    e.preventDefault();
    if (campos["correo"] && campos["password"]) {
      document.querySelector(".form-msg").classList.add("hidden");
      loginForm.submit();
      // loginForm.reset();
    } else {
      document.querySelector(".form-msg").classList.remove("hidden");
    }
  });
}
