<?php
require_once '../includes/db.php';
$db = new DB();
$menu = $db->getMenu();

// Mock Stats
$dailySales = 1500000;
$orderCount = 45;
$topItem = $menu[0]['name'] ?? 'N/A';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Nasi Padang AI</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-layout {
            display: flex;
            min-height: 100vh;
            background: #f5f5f5;
        }

        .sidebar {
            wIdth: 250px;
            background: var(--primary-black);
            color: white;
            padding: 20px;
            flex-shrink: 0;
        }

        .sidebar h2 {
            color: var(--primary-gold);
            margin-bottom: 30px;
        }

        .nav-item {
            display: block;
            padding: 10px;
            color: #ccc;
            margin-bottom: 5px;
            border-radius: 8px;
            cursor: pointer;
        }

        .nav-item:hover,
        .nav-item.active {
            background: var(--primary-red);
            color: white;
        }

        .content {
            flex: 1;
            padding: 40px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <div class="nav-item active">Dashboard</div>
            <div class="nav-item">Kelola Menu</div>
            <a href="../index.php" class="nav-item" style="margin-top: 20px; border-top: 1px solid #444;">&larr; Ke
                Aplikasi</a>
        </aside>

        <main class="content">
            <h1>Ringkasan Penjualan</h1>

            <div class="grid" style="margin-top: 20px;">
                <div class="stat-card">
                    <h3>Penjualan Hari Ini</h3>
                    <p style="font-size: 2rem; font-weight: bold;">Rp
                        <?= number_format($dailySales, 0, ',', '.') ?>
                    </p>
                </div>
                <div class="stat-card">
                    <h3>Total Pesanan</h3>
                    <p style="font-size: 2rem; font-weight: bold;">
                        <?= $orderCount ?>
                    </p>
                </div>
                <div class="stat-card">
                    <h3>Menu Terlaris (AI)</h3>
                    <p style="font-size: 1.5rem; color: var(--primary-red); font-weight: bold;">
                        <?= $topItem ?>
                    </p>
                </div>
            </div>

            <h2 style="margin-top: 40px;">Daftar Menu</h2>
            <div style="background: white; border-radius: 12px; overflow: hidden; margin-top: 20px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #eee;">
                        <tr>
                            <th style="padding: 15px; text-align: left;">Nama</th>
                            <th style="padding: 15px; text-align: left;">Kategori</th>
                            <th style="padding: 15px; text-align: left;">Harga</th>
                            <th style="padding: 15px; text-align: left;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($menu as $item): ?>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 15px;">
                                    <?= $item['name'] ?>
                                </td>
                                <td style="padding: 15px;">
                                    <?= $item['category'] ?>
                                </td>
                                <td style="padding: 15px;">Rp
                                    <?= number_format($item['price']) ?>
                                </td>
                                <td style="padding: 15px;"><button class="btn-primary"
                                        style="padding: 5px 10px; font-size: 0.8rem;">Edit</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>