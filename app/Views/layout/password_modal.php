<?php
$hasPasswordError = ! empty($errors['current_password'])
    || ! empty($errors['new_password'])
    || ! empty($errors['confirm_password'])
    || ! empty($errors['password_update']);
?>
<div class="password-modal-overlay <?= $hasPasswordError ? 'show' : '' ?>" id="password-modal">
    <div class="password-modal" role="dialog" aria-modal="true" aria-labelledby="password-modal-title">
        <div class="admin-modal-header">
            <h3 id="password-modal-title">Update Password</h3>
            <button type="button" class="admin-modal-close js-close-password-modal" aria-label="Tutup popup">&times;</button>
        </div>
        <p class="admin-modal-subtitle">Gunakan password baru untuk akun yang sedang login.</p>
        <form method="post" action="<?= site_url('/profile/password') ?>" class="password-modal-form">
            <input type="password" name="current_password" placeholder="Password saat ini" required>
            <input type="password" name="new_password" placeholder="Password baru" required>
            <input type="password" name="confirm_password" placeholder="Konfirmasi password baru" required>
            <button type="submit" class="small-btn primary">Simpan Password</button>
        </form>
        <?php foreach (['current_password', 'new_password', 'confirm_password', 'password_update'] as $field) : ?>
            <?php if (! empty($errors[$field])) : ?>
                <small class="field-error"><?= esc($errors[$field]) ?></small>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
