<!-- VIEW: REGISTER -->
<section id="view-register" class="auth-container hidden">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Buat Akun</h2>
            <p>Bergabunglah dengan Gerakan Sampah Bersih.</p>
        </div>

        <?php if (! empty($errors['register'])) : ?>
            <div class="alert-error"><?= esc($errors['register']) ?></div>
        <?php endif; ?>

        <form id="form-register" method="post" action="<?= site_url('/register') ?>">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input
                        name="full_name"
                        type="text"
                        class="form-control"
                        placeholder="Nama Anda"
                        value="<?= esc(old('full_name')) ?>"
                        required
                    />
                </div>
                <?php if (! empty($errors['full_name'])) : ?>
                    <small class="field-error"><?= esc($errors['full_name']) ?></small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input
                        name="email"
                        type="email"
                        class="form-control"
                        placeholder="contoh@email.com"
                        value="<?= esc(old('email')) ?>"
                        required
                    />
                </div>
                <?php if (! empty($errors['email'])) : ?>
                    <small class="field-error"><?= esc($errors['email']) ?></small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>No. Handphone</label>
                <div class="input-wrapper">
                    <i class="fas fa-phone"></i>
                    <input
                        name="phone"
                        type="tel"
                        class="form-control"
                        placeholder="0812xxxx"
                        value="<?= esc(old('phone')) ?>"
                        required
                    />
                </div>
                <?php if (! empty($errors['phone'])) : ?>
                    <small class="field-error"><?= esc($errors['phone']) ?></small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input
                        name="password"
                        type="password"
                        class="form-control"
                        placeholder="Buat password kuat"
                        required
                    />
                </div>
                <?php if (! empty($errors['password'])) : ?>
                    <small class="field-error"><?= esc($errors['password']) ?></small>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">
                Daftar Akun
            </button>
        </form>

        <div class="auth-footer">
            Sudah punya akun?
            <a href="#" onclick="app.navigate('login')">Masuk disini</a>
        </div>
    </div>
</section>
