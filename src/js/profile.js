const renderErrors = () => {
  const errors = document.querySelectorAll(".errors");

  errors.forEach((error) => {
    const errorMessage =
      error.dataset.newPassword ||
      error.dataset.currentPassword ||
      error.dataset.incorrectPassword;

    if (errorMessage) {
      error.textContent = errorMessage;
      hideError(error);
    }
  });
};

const hideError = (er) => {
  setTimeout(() => {
    er.classList.add("hidden");
  }, 2000);
};

const renderSuccessUpdatedPassword = () => {
  const successMessage = document.querySelector(".successMessage");

  if (successMessage?.dataset?.successUpdatedPassword) {
    Toastify({
      text: successMessage.dataset.successUpdatedPassword,
      duration: 2000,
      gravity: "top",
      position: "center",
      stopOnFocus: true,
      style: {
        background: "#10b981",
        borderRadius: "8px",
        fontSize: "14px",
        boxShadow: "0 4px 12px rgba(0, 0, 0, 0.08)",
        width: "max-content",
        maxWidth: "90%",
        padding: "12px 24px",
      },
    }).showToast();
  }
};
renderErrors();
renderSuccessUpdatedPassword();
