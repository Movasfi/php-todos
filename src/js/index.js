const menuContainer = document.getElementById("menu-container");

const openMenu = () => {
  const menu = document.querySelector(".menu");
  console.log(menu);
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

openMenu();
