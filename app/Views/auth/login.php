<!-- VIEW: LOGIN -->
<section id="view-login" class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Selamat Datang</h2>
            <p>Silakan masuk untuk mulai mengelola sampah.</p>
        </div>

        <?php if (! empty($message)) : ?>
            <div class="alert-success"><?= esc($message) ?></div>
        <?php endif; ?>

        <?php if (! empty($errors['login'])) : ?>
            <div class="alert-error"><?= esc($errors['login']) ?></div>
        <?php endif; ?>

        <form id="form-login" method="post" action="<?= site_url('/login') ?>">
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
                <label>Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input
                        name="password"
                        type="password"
                        class="form-control"
                        placeholder="••••••••"
                        required
                    />
                </div>
                <?php if (! empty($errors['password'])) : ?>
                    <small class="field-error"><?= esc($errors['password']) ?></small>
                <?php endif; ?>
            </div>
            <div
                class="form-group"
                style="
                    display: flex;
                    justify-content: space-between;
                    font-size: 0.85rem;
                "
            >
                <label style="display: inline; font-weight: 400">
                    <input type="checkbox" /> Ingat saya
                </label>
                <a
                    href="#"
                    style="
                        color: var(--primary-color);
                        text-decoration: none;
                    "
                    >Lupa password?</a
                >
            </div>
            <button type="submit" class="btn btn-primary">
                Masuk Sekarang
            </button>
        </form>

        <div class="divider">
            <span>atau</span>
        </div>

        <button
            class="btn"
            style="border: 1px solid #ddd; background: white"
        >
            <i
                class="fab fa-google"
                style="color: #db4437; margin-right: 8px"
            ></i>
            Masuk dengan Google
        </button>

        <div class="auth-footer">
            Belum punya akun?
            <a href="#" onclick="app.navigate('register')"
                >Daftar disini</a
            >
        </div>
    </div>
</section>
