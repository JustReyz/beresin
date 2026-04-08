<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Beres.in</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
    <section class="admin-page">
        <div class="admin-frame">
            <header class="admin-header">
                <div>
                    <h1><span class="brand-beres">Beres</span><span class="brand-dotin">.in</span> Admin Dashboard</h1>
                </div>
                <div class="admin-header-right">
                    <span><?= esc(date('l, d M Y')) ?></span>
                    <?= view('layout/profile_menu') ?>
                </div>
            </header>

            <?php if (! empty($message)) : ?>
                <div class="alert-success"><?= esc($message) ?></div>
            <?php endif; ?>
            <?php if (! empty($errors['status'])) : ?>
                <div class="alert-error"><?= esc($errors['status']) ?></div>
            <?php endif; ?>
            <?php if (! empty($errors['delete'])) : ?>
                <div class="alert-error"><?= esc($errors['delete']) ?></div>
            <?php endif; ?>
            <?php if (! empty($errors['create_admin'])) : ?>
                <div class="alert-error"><?= esc($errors['create_admin']) ?></div>
            <?php endif; ?>

            <?php
            $hasInternalFormError = ! empty($errors['full_name'])
                || ! empty($errors['email'])
                || ! empty($errors['phone'])
                || ! empty($errors['password'])
                || ! empty($errors['role'])
                || ! empty($errors['create_admin']);
            ?>

            <div class="admin-toolbar">
                <button type="button" class="admin-subtle-btn js-open-admin-modal">Tambah Akun Internal</button>
            </div>

            <div class="admin-modal-overlay <?= $hasInternalFormError ? 'show' : '' ?>" id="internal-account-modal">
                <div class="admin-modal" role="dialog" aria-modal="true" aria-labelledby="admin-modal-title">
                    <div class="admin-modal-header">
                        <h2 id="admin-modal-title">Tambah Akun Internal</h2>
                        <button type="button" class="admin-modal-close js-close-admin-modal" aria-label="Tutup popup">&times;</button>
                    </div>
                    <p class="admin-modal-subtitle">Buat akun untuk role admin atau mitra.</p>
                    <form method="post" action="<?= site_url('/admin/users/create-admin') ?>" class="admin-modal-form">
                        <input type="text" name="full_name" placeholder="Nama lengkap" value="<?= esc(old('full_name')) ?>" required>
                        <input type="email" name="email" placeholder="Email" value="<?= esc(old('email')) ?>" required>
                        <input type="tel" name="phone" placeholder="No. handphone" value="<?= esc(old('phone')) ?>" required>
                        <input type="password" name="password" placeholder="Password (min 8, huruf+angka)" required>
                        <select name="role" required>
                            <option value="admin" <?= old('role') === 'admin' || old('role') === null ? 'selected' : '' ?>>Admin</option>
                            <option value="mitra" <?= old('role') === 'mitra' ? 'selected' : '' ?>>Mitra</option>
                        </select>
                        <button type="submit" class="small-btn primary">Simpan Akun</button>
                    </form>
                    <?php foreach (['full_name', 'email', 'phone', 'password', 'role'] as $field) : ?>
                        <?php if (! empty($errors[$field])) : ?>
                            <small class="field-error"><?= esc($errors[$field]) ?></small>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="admin-stats">
                <article class="admin-stat-card modern">
                    <div class="admin-stat-top">
                        <span class="admin-stat-icon blue"><i class="fa-solid fa-inbox"></i></span>
                        <span class="admin-stat-trend"><?= esc((string) $pickupProgress['taken']) ?>/<?= esc((string) max(1, $stats['total'])) ?> diambil</span>
                    </div>
                    <p class="value"><?= esc((string) $stats['total']) ?></p>
                    <p class="label">Pesanan hari ini</p>
                    <div class="admin-mini-progress">
                        <span style="width: <?= esc((string) $pickupProgress['taken_pct']) ?>%"></span>
                    </div>
                </article>
                <article class="admin-stat-card modern">
                    <div class="admin-stat-top">
                        <span class="admin-stat-icon green"><i class="fa-solid fa-circle-check"></i></span>
                        <span class="admin-stat-trend"><?= esc((string) $pickupProgress['selesai_pct']) ?>%</span>
                    </div>
                    <p class="value success"><?= esc((string) $stats['selesai']) ?></p>
                    <p class="label">Selesai</p>
                    <div class="admin-mini-progress success">
                        <span style="width: <?= esc((string) $pickupProgress['selesai_pct']) ?>%"></span>
                    </div>
                </article>
                <article class="admin-stat-card modern">
                    <div class="admin-stat-top">
                        <span class="admin-stat-icon amber"><i class="fa-solid fa-truck-fast"></i></span>
                        <span class="admin-stat-trend"><?= esc((string) $pickupProgress['aktif_pct']) ?>%</span>
                    </div>
                    <p class="value warning"><?= esc((string) $stats['aktif']) ?></p>
                    <p class="label">Sedang berjalan</p>
                    <div class="admin-mini-progress warning">
                        <span style="width: <?= esc((string) $pickupProgress['aktif_pct']) ?>%"></span>
                    </div>
                </article>
                <article class="admin-stat-card modern">
                    <div class="admin-stat-top">
                        <span class="admin-stat-icon red"><i class="fa-solid fa-circle-xmark"></i></span>
                        <span class="admin-stat-trend"><?= esc((string) $pickupProgress['batal_pct']) ?>%</span>
                    </div>
                    <p class="value danger"><?= esc((string) $stats['batal']) ?></p>
                    <p class="label">Dibatalkan</p>
                    <div class="admin-mini-progress danger">
                        <span style="width: <?= esc((string) $pickupProgress['batal_pct']) ?>%"></span>
                    </div>
                </article>
            </div>

            <section class="pickup-progress-panel">
                <div class="pickup-progress-head">
                    <h2>Progress Pengambilan</h2>
                    <span><?= esc((string) $pickupProgress['taken']) ?>/<?= esc((string) max(1, $stats['total'])) ?> diambil</span>
                </div>
                <div class="pickup-progress-track">
                    <span class="pickup-progress-taken" style="width: <?= esc((string) $pickupProgress['taken_pct']) ?>%"></span>
                </div>
                <div class="pickup-progress-legend">
                    <span>Diambil: <?= esc((string) $pickupProgress['taken_pct']) ?>%</span>
                    <span>Belum diambil: <?= esc((string) $pickupProgress['waiting_pct']) ?>%</span>
                </div>
            </section>

            <div class="admin-content-grid">
                <section class="admin-table-panel">
                    <h2>Pesanan terbaru</h2>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order) : ?>
                                <tr>
                                    <td><?= esc($order['order_code']) ?></td>
                                    <td><?= esc($order['customer_name']) ?></td>
                                    <td><?= esc($order['category']) ?></td>
                                    <td>
                                        <span class="status-pill status-<?= esc($order['status']) ?>">
                                            <?= esc(ucfirst($order['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="kebab-menu-container">
                                            <button type="button" class="kebab-btn js-kebab-toggle" aria-label="Menu aksi untuk <?= esc($order['order_code']) ?>" aria-expanded="false">
                                                ⋮
                                            </button>
                                            <div class="kebab-dropdown" role="menu">
                                                <div class="kebab-section">
                                                    <form method="post" action="<?= site_url('/admin/orders/' . $order['id'] . '/status') ?>" class="kebab-form">
                                                        <input type="hidden" name="status" value="selesai">
                                                        <button type="submit" class="kebab-action-btn save" role="menuitem">Selesai</button>
                                                    </form>
                                                    <form method="post" action="<?= site_url('/admin/orders/' . $order['id'] . '/status') ?>" class="kebab-form">
                                                        <input type="hidden" name="status" value="batal">
                                                        <button type="submit" class="kebab-action-btn cancel" role="menuitem">Batal</button>
                                                    </form>
                                                    <form method="post" action="<?= site_url('/admin/orders/' . $order['id'] . '/delete') ?>" class="kebab-form" onsubmit="return confirm('Hapus data ini dari database?');">
                                                        <button type="submit" class="kebab-action-btn delete" role="menuitem">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>

                <aside class="admin-side-panel">
                    <h2>Mitra aktif hari ini</h2>
                    <?php foreach ($couriers as $courier) : ?>
                        <article class="courier-card">
                            <div class="courier-top">
                                <div class="courier-name-wrap">
                                    <span class="courier-avatar"><?= esc(strtoupper(substr($courier['name'], 0, 2))) ?></span>
                                    <h3><?= esc($courier['name']) ?></h3>
                                </div>
                                <span class="status-pill status-<?= esc($courier['availability_status']) ?>">
                                    <?= esc(ucfirst($courier['availability_status'])) ?>
                                </span>
                            </div>
                            <p><?= esc((string) $courier['today_completed']) ?> pesanan selesai · <?= esc((string) $courier['today_active']) ?> aktif</p>
                        </article>
                    <?php endforeach; ?>
                </aside>
            </div>
        </div>
    </section>
    <script src="<?= base_url('js/profile-menu.js') ?>"></script>
    <script src="<?= base_url('js/admin-dashboard.js') ?>"></script>
    <?= view('layout/password_modal') ?>
</body>
</html>
