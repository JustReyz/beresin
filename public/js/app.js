/**
 * OBJECT APP
 * Berfungsi sebagai "Controller" sederhana untuk menangani navigasi
 * dan logika bisnis dasar sebelum migrasi ke CodeIgniter 4.
 */
const app = {
  currentUser: window.BERES_CURRENT_USER || null,

  // Fungsi Navigasi (Routing Sederhana)
  navigate: function (viewId) {
    // Sembunyikan semua section
    document
      .querySelectorAll("section")
      .forEach((el) => el.classList.add("hidden"));

    // Tampilkan section yang dipilih
    const target = document.getElementById(`view-${viewId}`);
    if (target) {
      target.classList.remove("hidden");
    }

    // Update state Navbar
    this.updateNavbar();
  },

  // Fungsi Update Tampilan Navbar berdasarkan status Login
  updateNavbar: function () {
    const guestNav = document.getElementById("nav-guest");
    const userNav = document.getElementById("nav-user");

    if (this.currentUser) {
      guestNav.classList.add("hidden");
      userNav.classList.remove("hidden");
      document.getElementById("user-greeting").innerText =
        `Halo, ${this.currentUser.name}`;
    } else {
      guestNav.classList.remove("hidden");
      userNav.classList.add("hidden");
    }
  },

  // Logika Login (Simulasi)
  handleLogin: function (e) {
    e.preventDefault();
    const email = e.target.querySelector('input[type="email"]').value;

    // Simulasi loading
    const btn = e.target.querySelector('button[type="submit"]');
    const originalText = btn.innerText;
    btn.innerText = "Memproses...";
    btn.disabled = true;

    setTimeout(() => {
      // Mock Login Berhasil
      this.currentUser = {
        name: "Pengguna Demo",
        email: email,
      };
      this.showToast("Login berhasil! Selamat datang di Beres.in.", "success");
      this.navigate("dashboard");

      // Reset Form
      btn.innerText = originalText;
      btn.disabled = false;
      e.target.reset();
    }, 1000);
  },

  // Logika Register (Simulasi)
  handleRegister: function (e) {
    e.preventDefault();
    const name = e.target.querySelector('input[type="text"]').value;

    // Simulasi loading
    const btn = e.target.querySelector('button[type="submit"]');
    const originalText = btn.innerText;
    btn.innerText = "Mendaftarkan...";
    btn.disabled = true;

    setTimeout(() => {
      // Mock Register Berhasil -> Langsung Login
      this.currentUser = {
        name: name,
        email: "baru@example.com",
      };
      this.showToast("Akun berhasil dibuat!", "success");
      this.navigate("dashboard");

      // Update Dashboard Name
      document.getElementById("dashboard-name").innerText = name;

      btn.innerText = originalText;
      btn.disabled = false;
      e.target.reset();
    }, 1000);
  },

  // Logika Logout
  logout: function () {
    window.location.href = "/logout";
  },

  // Komponen Toast Notification (Pengganti Alert)
  showToast: function (message, type = "success") {
    const container = document.getElementById("toast-container");
    const toast = document.createElement("div");
    toast.className = `toast ${type === "error" ? "error" : ""}`;

    let icon =
      type === "error"
        ? '<i class="fas fa-exclamation-circle" style="color:#e74c3c"></i>'
        : '<i class="fas fa-check-circle" style="color:var(--primary-color)"></i>';

    toast.innerHTML = `${icon} <span>${message}</span>`;

    container.appendChild(toast);

    // Hapus toast setelah 3 detik
    setTimeout(() => {
      toast.style.opacity = "0";
      toast.style.transform = "translateX(100%)";
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  },
};

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

function toggleOrderModal(open) {
  const modal = document.getElementById("order-modal");
  if (!modal) {
    return;
  }

  modal.classList.toggle("show", open);
}

document.addEventListener("click", (event) => {
  const openBtn = event.target.closest(".js-open-order-modal");
  const closeBtn = event.target.closest(".js-close-order-modal");
  const isOverlay = event.target.classList.contains("order-modal-overlay");

  if (openBtn) {
    event.preventDefault();
    toggleOrderModal(true);
    return;
  }

  if (closeBtn || isOverlay) {
    event.preventDefault();
    toggleOrderModal(false);
  }
});

document.addEventListener("keydown", (event) => {
  if (event.key === "Escape") {
    toggleOrderModal(false);
  }
});

// Inisialisasi Aplikasi
document.addEventListener("DOMContentLoaded", () => {
  const allowedViews = ["login", "register", "dashboard"];
  const initialView = window.BERES_INITIAL_VIEW;

  if (allowedViews.includes(initialView)) {
    const dashboardName = document.getElementById("dashboard-name");
    if (dashboardName && app.currentUser && app.currentUser.name) {
      dashboardName.innerText = app.currentUser.name;
    }
    app.navigate(initialView);
    return;
  }

  app.navigate("login");
});
