<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beres.in - Jemput Sampah Mudah</title>
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
    <div class="toast-container" id="toast-container"></div>

    <?= view('layout/navbar') ?>
    <?= view('auth/login') ?>
    <?= view('auth/register') ?>
    <?= view('home/dashboard') ?>
    <?= view('layout/password_modal') ?>

    <script>
        window.BERES_INITIAL_VIEW = <?= json_encode($initialView ?? 'login') ?>;
        window.BERES_CURRENT_USER = <?= json_encode($currentUser ?? null) ?>;
    </script>
    <script src="<?= base_url('js/profile-menu.js') ?>"></script>
    <script src="<?= base_url('js/app.js') ?>"></script>
</body>
</html>