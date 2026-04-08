<?php
$currentUser = $currentUser ?? null;
$profileLabel = $currentUser['name'] ?? 'Pengguna';
$profileRole = $currentUser['role'] ?? 'user';
$profileInitial = strtoupper(substr((string) $profileLabel, 0, 2));
?>
<div class="profile-menu" data-profile-menu>
    <button type="button" class="profile-trigger js-profile-trigger" aria-haspopup="true" aria-expanded="false">
        <span class="profile-avatar"><?= esc($profileInitial) ?></span>
        <span class="profile-trigger-text">
            <strong><?= esc($profileLabel) ?></strong>
            <small><?= esc(ucfirst($profileRole)) ?></small>
        </span>
        <i class="fa-solid fa-chevron-down profile-caret"></i>
    </button>
    <div class="profile-dropdown" role="menu">
        <div class="profile-dropdown-head">
            <span class="profile-avatar large"><?= esc($profileInitial) ?></span>
            <div>
                <strong><?= esc($profileLabel) ?></strong>
                <small><?= esc($currentUser['email'] ?? '-') ?></small>
            </div>
        </div>
        <?php if ($profileRole === 'user') : ?>
            <button type="button" class="profile-item js-open-order-modal">Pesan Penjemputan</button>
        <?php endif; ?>
        <button type="button" class="profile-item js-open-password-modal">Ubah Password</button>
        <?php if ($profileRole === 'admin') : ?>
            <a href="<?= site_url('/admin/dashboard') ?>" class="profile-item link">Dashboard Admin</a>
        <?php elseif ($profileRole === 'mitra') : ?>
            <a href="<?= site_url('/mitra/dashboard') ?>" class="profile-item link">Dashboard Mitra</a>
        <?php else : ?>
            <a href="<?= site_url('/dashboard') ?>" class="profile-item link">Dashboard</a>
        <?php endif; ?>
        <a href="<?= site_url('/logout') ?>" class="profile-item danger">Logout</a>
    </div>
</div>
