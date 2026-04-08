function closeKebabMenus() {
  document.querySelectorAll(".kebab-dropdown.show").forEach((menu) => {
    menu.classList.remove("show");
  });

  document.querySelectorAll(".js-kebab-toggle").forEach((toggleBtn) => {
    toggleBtn.setAttribute("aria-expanded", "false");
  });
}

document.addEventListener("click", (event) => {
  const toggleBtn = event.target.closest(".js-kebab-toggle");

  if (toggleBtn) {
    event.preventDefault();
    event.stopPropagation();

    const container = toggleBtn.closest(".kebab-menu-container");
    const dropdown = container ? container.querySelector(".kebab-dropdown") : null;

    if (!dropdown) {
      return;
    }

    const isOpen = dropdown.classList.contains("show");
    closeKebabMenus();

    if (!isOpen) {
      dropdown.classList.add("show");
      toggleBtn.setAttribute("aria-expanded", "true");
    }

    return;
  }

  if (!event.target.closest(".kebab-menu-container")) {
    closeKebabMenus();
  }
});

document.addEventListener("keydown", (event) => {
  if (event.key === "Escape") {
    closeKebabMenus();
  }
});

document.addEventListener("submit", (event) => {
  if (event.target.classList.contains("kebab-form")) {
    closeKebabMenus();
  }
});

function toggleAdminModal(open) {
  const modal = document.getElementById("internal-account-modal");
  if (!modal) {
    return;
  }

  modal.classList.toggle("show", open);
}

document.addEventListener("click", (event) => {
  const openBtn = event.target.closest(".js-open-admin-modal");
  const closeBtn = event.target.closest(".js-close-admin-modal");
  const overlay = event.target.classList.contains("admin-modal-overlay");

  if (openBtn) {
    event.preventDefault();
    toggleAdminModal(true);
    return;
  }

  if (closeBtn || overlay) {
    event.preventDefault();
    toggleAdminModal(false);
  }
});

document.addEventListener("keydown", (event) => {
  if (event.key === "Escape") {
    toggleAdminModal(false);
  }
});
