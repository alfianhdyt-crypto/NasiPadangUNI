// State
let cart = JSON.parse(localStorage.getItem('padang_cart')) || [];
const timeBadge = document.getElementById('time-badge');

// Init
document.addEventListener('DOMContentLoaded', () => {
    updateCartUI();
    fetchRecommendations();
    determineTime();
});

function determineTime() {
    const hour = new Date().getHours();
    let time = 'day';
    let label = 'Siang';

    if (hour >= 6 && hour < 11) { time = 'morning'; label = 'Pagi'; }
    else if (hour >= 15) { time = 'night'; label = 'Malam'; }

    timeBadge.innerText = 'Waktu: ' + label;
    return time;
}

// AI Logic (Client/Server bridge)
async function fetchRecommendations() {
    const time = determineTime();
    const history = cart.map(i => i.id);

    const container = document.getElementById('rec-grid');

    try {
        const res = await fetch('api/recommendation.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ time, history })
        });
        const data = await res.json();

        container.innerHTML = data.map(item => `
            <div class="menu-card" style="border: 2px solid var(--primary-gold)">
                <div class="image-wrapper">
                    <img src="${item.image}" alt="${item.name}">
                    <span class="tag">Rekomendasi AI</span>
                </div>
                <div class="card-content">
                    <div class="card-header">
                        <h3>${item.name}</h3>
                        <span class="price">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</span>
                    </div>
                    <button class="add-btn" onclick='addToCart(${JSON.stringify(item)})'>+ Tambah</button>
                </div>
            </div>
        `).join('');

    } catch (e) {
        container.innerHTML = '<p>Gagal memuat rekomendasi.</p>';
        console.error(e);
    }
}

// Cart Logic
function toggleCart() {
    document.getElementById('cart-overlay').classList.toggle('open');
}

function addToCart(item) {
    const existing = cart.find(i => i.id === item.id);
    if (existing) {
        existing.quantity++;
    } else {
        item.quantity = 1;
        cart.push(item);
    }
    saveCart();
    toggleCart();
    if (!document.getElementById('cart-overlay').classList.contains('open')) toggleCart();
}

function updateQty(id, delta) {
    cart = cart.map(item => {
        if (item.id === id) return { ...item, quantity: Math.max(0, item.quantity + delta) };
        return item;
    }).filter(i => i.quantity > 0);
    saveCart();
}

function saveCart() {
    localStorage.setItem('padang_cart', JSON.stringify(cart));
    updateCartUI();
}

function updateCartUI() {
    const count = cart.reduce((sum, i) => sum + i.quantity, 0);
    document.getElementById('cart-count').innerText = count;

    const itemsContainer = document.getElementById('cart-items');
    if (cart.length === 0) {
        itemsContainer.innerHTML = '<p style="text-align:center; color:#888; margin-top:20px;">Keranjang kosong.</p>';
        document.getElementById('cart-total').innerText = 'Rp 0';
        return;
    }

    let total = 0;
    itemsContainer.innerHTML = cart.map(item => {
        total += item.price * item.quantity;
        return `
        <div style="display:flex; justify-content:space-between; margin-bottom:15px; border-bottom:1px dashed #eee; padding-bottom:10px;">
            <div>
                <h4 style="margin:0">${item.name}</h4>
                <small>Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</small>
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
                <button onclick="updateQty(${item.id}, -1)" style="width:25px; height:25px; border-radius:50%; border:1px solid #ccc; background:white">-</button>
                <span>${item.quantity}</span>
                <button onclick="updateQty(${item.id}, 1)" style="width:25px; height:25px; border-radius:50%; border:1px solid #ccc; background:white">+</button>
            </div>
        </div>
        `;
    }).join('');

    document.getElementById('cart-total').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
}

function checkout() {
    if (cart.length === 0) return;
    alert('Terima kasih! Pesanan Anda sedang diproses.\nTotal: ' + document.getElementById('cart-total').innerText);
    cart = [];
    saveCart();
    toggleCart();
}

// Chatbot Logic
let chatHistory = [];

function toggleChat() {
    const window = document.getElementById('chat-window');
    window.classList.toggle('active');
    if (window.classList.contains('active')) {
        document.getElementById('chat-input').focus();
    }
}

function handleChatInput(e) {
    if (e.key === 'Enter') sendChat();
}

async function sendChat() {
    const input = document.getElementById('chat-input');
    const msg = input.value.trim();

    if (!msg) return;

    // Add user message
    appendMessage('user', msg);
    input.value = '';
    input.disabled = true;

    // Add to history
    chatHistory.push({ role: 'user', content: msg });

    try {
        const res = await fetch('api/chat.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                message: msg,
                history: chatHistory
            })
        });

        const data = await res.json();

        if (data.reply) {
            appendMessage('bot', data.reply);
            chatHistory.push({ role: 'assistant', content: data.reply });
        } else {
            appendMessage('bot', 'Maaf, Uni lagi gangguan sinyal.. 😵');
        }

    } catch (e) {
        console.error(e);
        appendMessage('bot', 'Aduh, error nih sistemnya..');
    }

    input.disabled = false;
    input.focus();
}

function appendMessage(role, text) {
    const container = document.getElementById('chat-messages');
    const div = document.createElement('div');
    div.className = `message ${role}`;
    div.innerText = text;
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
}
