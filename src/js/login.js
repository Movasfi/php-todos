const showAndHidePassword = () => {
  const eyeContainer = document.getElementById("eyeContainer");
  const passwordInput = document.getElementById("password");
  const eye = document.getElementById("eye");

  eyeContainer.addEventListener("click", () => {
    if (passwordInput.type === "password") {
      eye.src = "../assets/Eye.svg";
      passwordInput.type = "text";
    } else {
      eye.src = "../assets/Closed Eye.svg";
      passwordInput.type = "password";
    }
  });
};

const hideErrorMsg = () => {
  const error = document.querySelector(".errorMsg");

  if (error) {
    setTimeout(() => {
      error.style.transition = "opacity 0.5s ease-in-out";

      error.style.opacity = "0";
      error.style.display = "none";
    }, 2000);
  }
};
hideErrorMsg();
showAndHidePassword();
