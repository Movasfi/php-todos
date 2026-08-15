const openMenu = () => {
  const menuContainer = document.getElementById("menu-container");
  const menu = document.querySelector(".menu");
  menu.addEventListener("click", () => {
    const navbarList = document.querySelector(".navbar-list");
    // navbarList.classList.toggle("hidden");

    navbarList.classList.toggle("max-h-0");
    navbarList.classList.toggle("max-h-64");

    navbarList.classList.toggle("opacity-0");
    navbarList.classList.toggle("opacity-100");

    navbarList.classList.toggle("-translate-y-2");
    navbarList.classList.toggle("translate-y-0");

    navbarList.classList.toggle("pointer-events-none");
  });
};

const renderSuccessMessage = () => {
  const successMessage = document.getElementById("successMessage");
  if (successMessage?.dataset?.message) {
    Toastify({
      text: successMessage.dataset.message,
      duration: 3000,
      gravity: "top",
      position: "center",
      stopOnFocus: true,
      style: {
        background: "#10b981",
        borderRadius: "8px",
        fontSize: "14px",
        boxShadow: "0 4px 12px rgba(0, 0, 0, 0.08)",
      },
    }).showToast();
  }
};
const renderDbError = () => {
  const dbError = document.getElementById("dbError");
  if (dbError?.dataset?.message) {
    Toastify({
      text: dbError.dataset.message,
      duration: 3000,
      gravity: "top",
      position: "center",
      stopOnFocus: true,
      style: {
        background: "#FF0000",
        borderRadius: "8px",
        fontSize: "14px",
        boxShadow: "0 4px 12px rgba(0, 0, 0, 0.08)",
      },
    }).showToast();
  }
};
openMenu();
renderSuccessMessage();
renderDbError();
