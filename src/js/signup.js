const showPasswordAndHide = () => {
  const password = document.getElementById("password");
  const eyeContainerPassword = document.getElementById("eyeContainerPassword");
  const eyePassword = document.getElementById("eyePassword");
  eyeContainerPassword.addEventListener("click", () => {
    if (password.type === "password") {
      password.type = "text";
      eyePassword.src = "../assets/Eye.svg";
    } else {
      password.type = "password";
      eyePassword.src = "../assets/Closed Eye.svg";
    }
  });
  const confirmPassword = document.getElementById("confirmPassword");
  const eyeContainerConfirmPassword = document.getElementById(
    "eyeContainerConfirmPassword",
  );
  const eyeConfirmPassword = document.getElementById("eyeConfirmPassword");
  eyeContainerConfirmPassword.addEventListener("click", () => {
    if (confirmPassword.type === "password") {
      confirmPassword.type = "text";
      eyeConfirmPassword.src = "../assets/Eye.svg";
    } else {
      confirmPassword.type = "password";
      eyeConfirmPassword.src = "../assets/Closed Eye.svg";
    }
  });
};

const hideErrorMsg = () => {
  const errors = document.querySelectorAll(".errorMsg");
  console.log(errors);
  errors.forEach((error) => {
    setTimeout(() => {
      error.style.transition = "opacity 0.5s ease-in-out";

      error.style.opacity = "0";
      error.style.display = "none";
    }, 3000);
  });
};
hideErrorMsg();
showPasswordAndHide();
