<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mitra Dashboard - Beres.in</title>
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
    <section class="mitra-page">
        <div class="mitra-frame">
            <header class="mitra-header">
                <div>
                    <h1><span class="brand-beres">Beres</span><span class="brand-dotin">.in</span> Mitra Dashboard</h1>
                </div>
                <div class="mitra-header-right">
                    <span><?= esc(date('l, d M Y')) ?></span>
                    <?= view('layout/profile_menu') ?>
                </div>
            </header>

            <?php if (! empty($message)) : ?>
                <div class="alert-success"><?= esc($message) ?></div>
            <?php endif; ?>
            <?php if (! empty($errors['mitra'])) : ?>
                <div class="alert-error"><?= esc($errors['mitra']) ?></div>
            <?php endif; ?>

            <div class="mitra-stats">
                <article class="admin-stat-card">
                    <p class="value warning"><?= esc((string) count($availableOrders)) ?></p>
                    <p class="label">Menunggu di area</p>
                </article>
                <article class="admin-stat-card">
                    <p class="value"><?= esc((string) count($myActiveOrders)) ?></p>
                    <p class="label">Tugas aktif saya</p>
                </article>
                <article class="admin-stat-card">
                    <p class="value success"><?= esc((string) $myCompletedTotal) ?></p>
                    <p class="label">Selesai oleh saya</p>
                </article>
            </div>

            <div class="mitra-grid">
                <section class="mitra-panel">
                    <h2>Permintaan Menunggu</h2>
                    <?php if (empty($availableOrders)) : ?>
                        <p class="mitra-empty">Belum ada pickup menunggu saat ini.</p>
                    <?php else : ?>
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Pelanggan</th>
                                    <th>Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($availableOrders as $order) : ?>
                                    <tr>
                                        <td><?= esc($order['order_code']) ?></td>
                                        <td><?= esc($order['customer_name']) ?></td>
                                        <td><?= esc($order['category']) ?></td>
                                        <td>
                                            <form method="post" action="<?= site_url('/mitra/orders/' . $order['id'] . '/accept') ?>">
                                                <button type="submit" class="small-btn primary">Terima Pengambilan</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </section>

                <aside class="mitra-panel">
                    <h2>Tugas Aktif Saya</h2>
                    <?php if (empty($myActiveOrders)) : ?>
                        <p class="mitra-empty">Anda belum mengambil pesanan.</p>
                    <?php else : ?>
                        <?php foreach ($myActiveOrders as $order) : ?>
                            <article class="courier-card">
                                <div class="courier-top">
                                    <div class="courier-name-wrap">
                                        <span class="courier-avatar">PK</span>
                                        <h3><?= esc($order['order_code']) ?></h3>
                                    </div>
                                    <span class="status-pill status-aktif">Aktif</span>
                                </div>
                                <p><?= esc($order['customer_name']) ?> · <?= esc($order['category']) ?></p>
                                <form method="post" action="<?= site_url('/mitra/orders/' . $order['id'] . '/complete') ?>" style="margin-top: 8px;">
                                    <button type="submit" class="small-btn primary">Tandai Selesai</button>
                                </form>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    </section>
    <script src="<?= base_url('js/profile-menu.js') ?>"></script>
    <?= view('layout/password_modal') ?>
</body>
</html>
