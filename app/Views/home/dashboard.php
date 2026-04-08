<?php
$stats = $stats ?? ['total' => 0, 'menunggu' => 0, 'aktif' => 0, 'selesai' => 0];
$activeOrder = $activeOrder ?? null;
$historyOrders = $historyOrders ?? [];
$errors = $errors ?? [];

$pendingRatingOrder = null;
foreach (($orders ?? []) as $orderItem) {
    if (($orderItem['status'] ?? '') === 'selesai' && empty($orderItem['rating'])) {
        $pendingRatingOrder = $orderItem;
        break;
    }
}

$showOrderModal = ! empty($errors['pickup_address'])
    || ! empty($errors['estimated_volume'])
    || ! empty($errors['pickup_time'])
    || ! empty($errors['category'])
    || ! empty($errors['notes'])
    || ! empty($errors['order']);

$stepIndex = 0;
if ($activeOrder !== null) {
    $stepIndex = ($activeOrder['status'] ?? '') === 'aktif' ? 2 : 1;
}
?>

<section id="view-dashboard" class="dashboard-container hidden">
    <?php if (! empty($message)) : ?>
        <div class="alert-success"><?= esc($message) ?></div>
    <?php endif; ?>
    <?php if (! empty($errors['order'])) : ?>
        <div class="alert-error"><?= esc($errors['order']) ?></div>
    <?php endif; ?>
    <?php if (! empty($errors['rating'])) : ?>
        <div class="alert-error"><?= esc($errors['rating']) ?></div>
    <?php endif; ?>

    <div class="hero-section">
        <div class="hero-text">
            <h1>Halo, <span id="dashboard-name"><?= esc($currentUser['name'] ?? 'Teman') ?></span>! 👋</h1>
            <p>Sampah menumpuk? Tenang, kami siap menjemput. Jadwalkan penjemputan sekarang.</p>
        </div>
        <div class="hero-actions">
            <button type="button" class="hero-btn js-open-order-modal">
                <i class="fas fa-calendar-plus"></i> Jemput Sekarang
            </button>
            <p class="hero-actions-hint"><i class="fas fa-location-dot"></i> Buka formulir penjemputan dari sini atau lewat menu profil.</p>
        </div>
    </div>

    <h3 class="user-section-title">Ringkasan Aktivitas</h3>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-layer-group"></i></div>
            <div>
                <h4 class="user-stat-label">Total Pesanan</h4>
                <p class="user-stat-value"><?= esc((string) $stats['total']) ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
            <div>
                <h4 class="user-stat-label">Menunggu Mitra</h4>
                <p class="user-stat-value"><?= esc((string) $stats['menunggu']) ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-truck"></i></div>
            <div>
                <h4 class="user-stat-label">Sedang Diangkut</h4>
                <p class="user-stat-value"><?= esc((string) $stats['aktif']) ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div>
                <h4 class="user-stat-label">Selesai</h4>
                <p class="user-stat-value"><?= esc((string) $stats['selesai']) ?></p>
            </div>
        </div>
    </div>

    <div class="user-flow-grid">
        <section class="user-flow-panel">
            <h3>Status Pengangkutan Aktif</h3>
            <?php if ($activeOrder === null) : ?>
                <p class="user-empty">Belum ada pesanan aktif. Klik tombol Jemput Sekarang untuk membuat pesanan baru.</p>
            <?php else : ?>
                <article class="user-active-order-card">
                    <div class="user-active-order-top">
                        <h4><?= esc($activeOrder['order_code']) ?> · <?= esc($activeOrder['category']) ?></h4>
                        <span class="status-pill status-<?= esc($activeOrder['status']) ?>"><?= esc(ucfirst($activeOrder['status'])) ?></span>
                    </div>
                    <p class="user-order-meta">
                        <?= esc($activeOrder['pickup_address'] ?? '-') ?> · <?= esc($activeOrder['estimated_volume'] ?? '-') ?>
                    </p>
                    <p class="user-order-meta">
                        Mitra: <?= esc($activeOrder['mitra_name'] ?? 'Sedang dicari') ?>
                    </p>
                    <ol class="user-flow-steps">
                        <li class="<?= $stepIndex >= 0 ? 'done' : 'pending' ?>">Pemesanan valid</li>
                        <li class="<?= $stepIndex >= 1 ? 'done' : 'pending' ?>">Mencari mitra terdekat</li>
                        <li class="<?= $stepIndex >= 2 ? 'current' : 'pending' ?>">Mitra menuju lokasi dan proses angkut</li>
                        <li class="pending">Menunggu konfirmasi selesai</li>
                    </ol>
                </article>
            <?php endif; ?>
        </section>

        <aside class="user-flow-panel">
            <h3>Rating & Riwayat</h3>
            <?php if ($pendingRatingOrder !== null) : ?>
                <form method="post" action="<?= site_url('/orders/' . $pendingRatingOrder['id'] . '/rate') ?>" class="user-rating-form">
                    <p>Pesanan <?= esc($pendingRatingOrder['order_code']) ?> selesai. Beri rating mitra:</p>
                    <select name="rating" required>
                        <option value="">Pilih rating</option>
                        <option value="5">5 - Sangat puas</option>
                        <option value="4">4 - Puas</option>
                        <option value="3">3 - Cukup</option>
                        <option value="2">2 - Kurang</option>
                        <option value="1">1 - Tidak puas</option>
                    </select>
                    <button type="submit" class="small-btn primary">Kirim Ulasan</button>
                </form>
            <?php else : ?>
                <p class="user-empty">Belum ada pesanan selesai yang menunggu rating.</p>
            <?php endif; ?>

            <div class="user-history-list">
                <?php if (empty($historyOrders)) : ?>
                    <p class="user-empty">Riwayat pesanan belum tersedia.</p>
                <?php else : ?>
                    <?php foreach ($historyOrders as $history) : ?>
                        <article class="user-history-item">
                            <div>
                                <strong><?= esc($history['order_code']) ?></strong>
                                <p><?= esc($history['category']) ?> · <?= esc($history['estimated_volume'] ?? '-') ?></p>
                            </div>
                            <span class="status-pill status-<?= esc($history['status']) ?>"><?= esc(ucfirst($history['status'])) ?></span>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </aside>
    </div>

    <div class="order-modal-overlay <?= $showOrderModal ? 'show' : '' ?>" id="order-modal">
        <div class="order-modal" role="dialog" aria-modal="true" aria-labelledby="order-modal-title">
            <div class="admin-modal-header">
                <h3 id="order-modal-title">Pesan Penjemputan</h3>
                <button type="button" class="admin-modal-close js-close-order-modal" aria-label="Tutup popup">&times;</button>
            </div>
            <form method="post" action="<?= site_url('/orders/create') ?>" class="order-modal-form">
                <textarea name="pickup_address" rows="2" placeholder="Lokasi penjemputan" required><?= esc(old('pickup_address')) ?></textarea>
                <select name="estimated_volume" required>
                    <option value="">Estimasi volume</option>
                    <option value="<50kg" <?= old('estimated_volume') === '<50kg' ? 'selected' : '' ?>>< 50 kg</option>
                    <option value="50-100kg" <?= old('estimated_volume') === '50-100kg' ? 'selected' : '' ?>>50 - 100 kg</option>
                    <option value=">100kg" <?= old('estimated_volume') === '>100kg' ? 'selected' : '' ?>>> 100 kg</option>
                </select>
                <input type="datetime-local" name="pickup_time" value="<?= esc(old('pickup_time')) ?>" required>
                <select name="category" required>
                    <option value="">Kategori sampah</option>
                    <?php foreach (['Organik', 'Plastik', 'Kertas', 'Elektronik', 'B3'] as $category) : ?>
                        <option value="<?= esc($category) ?>" <?= old('category') === $category ? 'selected' : '' ?>><?= esc($category) ?></option>
                    <?php endforeach; ?>
                </select>
                <textarea name="notes" rows="2" placeholder="Catatan tambahan (opsional)"><?= esc(old('notes')) ?></textarea>
                <button type="submit" class="btn btn-primary">Konfirmasi Pesanan</button>
            </form>
            <?php foreach (['pickup_address', 'estimated_volume', 'pickup_time', 'category', 'notes'] as $field) : ?>
                <?php if (! empty($errors[$field])) : ?>
                    <small class="field-error"><?= esc($errors[$field]) ?></small>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
