
<!-- NAVIGASI -->
<nav class="navbar">
    <a href="#" class="brand" onclick="app.navigate(app.currentUser ? 'dashboard' : 'login')">
        <img
            src="<?= base_url('assets/images/logo2.png') ?>"
            alt="Beres.in Logo"
            class="brand-logo"
        >
        <span class="brand-text">
            <span class="brand-beres">Beres</span><span class="brand-dotin">.in</span>
        </span>
    </a>
    <div class="nav-links" id="nav-guest">
        <button onclick="app.navigate('login')">Masuk</button>
        <button
            onclick="app.navigate('register')"
            style="color: var(--primary-color)"
        >
            Daftar
        </button>
    </div>
    <div class="nav-links hidden" id="nav-user">
        <span
            id="user-greeting"
            style="
                margin-right: 15px;
                font-size: 0.9rem;
                color: var(--text-light);
            "
            >Halo, User</span
        >
        <?= view('layout/profile_menu') ?>
    </div>
</nav>
