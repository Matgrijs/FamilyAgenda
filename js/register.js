// function to change title and form in register/login page
document.addEventListener("DOMContentLoaded", function() {
  const loginForm = document.getElementById("loginForm");
  const registerForm = document.getElementById("registerForm");
  const toggleAction = document.getElementById("toggleAction");

  toggleAction.addEventListener("click", function() {
    const isLoginFormVisible = loginForm.style.display !== "none";

    loginForm.style.display = isLoginFormVisible ? "none" : "flex";
    registerForm.style.display = isLoginFormVisible ? "flex" : "none";
    toggleAction.textContent = isLoginFormVisible ? "Inloggen" : "Registreren";
    document.querySelector('.container h1').textContent = isLoginFormVisible ? "Registreren" : "Inloggen";
  });
});