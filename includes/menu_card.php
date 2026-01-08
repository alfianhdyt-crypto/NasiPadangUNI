<div class="menu-card">
    <div class="image-wrapper">
        <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>" loading="lazy">
        <?php if (in_array('best_seller', $item['tags'])): ?>
            <span class="tag">Terlaris</span>
        <?php endif; ?>
    </div>
    <div class="card-content">
        <div class="card-header">
            <h3>
                <?= $item['name'] ?>
            </h3>
            <span class="price">Rp
                <?= number_format($item['price'], 0, ',', '.') ?>
            </span>
        </div>
        <p class="desc">
            <?= $item['description'] ?>
        </p>
        <button class="add-btn" onclick='addToCart(<?= json_encode($item) ?>)'>
            + Tambah
        </button>
    </div>
</div>