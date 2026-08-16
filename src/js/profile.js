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
renderErrors();
