<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Tugas - LOL</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <?php
        // LOGIKA PATH DINAMIS
        // Jika halaman yang memanggil header ini belum menentukan arah, 
        // maka gunakan default mundur 2 langkah (untuk dashboard, dll)
        if (!isset($jalur_css)) {
            $jalur_css = "../../assets/css/input.css";
        }
    ?>
    
    <link href="<?= $jalur_css ?>" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>view/assets/css/index.cs" rel="stylesheet">
</head>
<body class="bg-light">