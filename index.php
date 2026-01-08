<?php
require_once 'includes/db.php';
$db = new DB();
$menu = $db->getMenu();

// Separate by category
$lauk = array_filter($menu, fn($i) => $i['category'] === 'lauk');
$sayur = array_filter($menu, fn($i) => $i['category'] === 'sayur' || $i['category'] === 'pelengkap');
$minuman = array_filter($menu, fn($i) => $i['category'] === 'minuman');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nasi Padang AI Antigravity</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <header class="header">
        <div class="container header-content">
            <h1 class="logo">Nasi Padang <span class="ai-badge">AI</span></h1>
            <button class="cart-btn" onclick="toggleCart()">
                🛒 Keranjang <span class="badge" id="cart-count">0</span>
            </button>
        </div>
    </header>

    <main class="container">
        <!-- AI Recommendation Section -->
        <section class="recommendation-section" id="recommendation-app">
            <div class="section-header">
                <h2>Rekomendasi AI Untuk Anda</h2>
                <span class="badge-time" id="time-badge">Loading...</span>
            </div>
            <div class="grid" id="rec-grid">
                <!-- JS will populate this -->
                <p>Menganalisis preferensi...</p>
            </div>
        </section>

        <!-- Menu Lauk -->
        <section class="menu-section">
            <h2>Lauk Pauk</h2>
            <div class="grid">
                <?php foreach ($lauk as $item): ?>
                    <?php include 'includes/menu_card.php'; ?>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Menu Sayur -->
        <section class="menu-section">
            <h2>Sayur & Pelengkap</h2>
            <div class="grid">
                <?php foreach ($sayur as $item): ?>
                    <?php include 'includes/menu_card.php'; ?>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Menu Minuman -->
        <section class="menu-section">
            <h2>Minuman</h2>
            <div class="grid">
                <?php foreach ($minuman as $item): ?>
                    <?php include 'includes/menu_card.php'; ?>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <!-- Chatbot Widget -->
    <div class="chat-widget">
        <button class="chat-toggle" onclick="toggleChat()">
            💬 Tanya Uni
        </button>
        <div class="chat-window" id="chat-window">
            <div class="chat-header">
                <h3>Uni Nasi Padang</h3>
                <button onclick="toggleChat()">&times;</button>
            </div>
            <div class="chat-body" id="chat-messages">
                <div class="message bot">
                    Halo! Awak Uni, mau makan apa hari ini? 🍛
                </div>
            </div>
            <div class="chat-input-area">
                <input type="text" id="chat-input" placeholder="Tanya menu..." onkeypress="handleChatInput(event)">
                <button onclick="sendChat()">Kirim</button>
            </div>
        </div>
    </div>

    <!-- Cart Overlay -->
    <div class="cart-overlay" id="cart-overlay">
        <div class="cart-backdrop" onclick="toggleCart()"></div>
        <div class="cart-panel">
            <div style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between;">
                <h2>Pesanan Anda</h2>
                <button onclick="toggleCart()"
                    style="border:none; background:none; font-size: 1.5rem; cursor:pointer">&times;</button>
            </div>
            <div id="cart-items" style="flex: 1; overflow-y: auto; padding: 20px;">
                <!-- Items go here -->
            </div>
            <div style="padding: 20px; background: #f9f9f9; border-top: 1px solid #eee;">
                <div
                    style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2rem; margin-bottom: 15px;">
                    <span>Total:</span>
                    <span id="cart-total">Rp 0</span>
                </div>
                <button class="btn btn-primary" style="width: 100%" onclick="checkout()">Checkout</button>
            </div>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
</body>

</html>