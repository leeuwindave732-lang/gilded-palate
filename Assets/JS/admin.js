console.log("Admin JS loaded");

// ---------------------- ORDERS ----------------------
async function loadOrders() {
    try {
        const res = await fetch('../admin-pages/admin-orders.php'); 
        const orders = await res.json();
        const tbody = document.getElementById('ordersBody');
        tbody.innerHTML = '';

        orders.forEach(order => {
    let itemsHtml = '';
    order.items.forEach(item => {
        itemsHtml += `${item.product_name} x ${item.quantity} <br>`;
    });

    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${order.order_id}</td>
        <td>${order.user_name}</td>
        <td>₱${order.total}</td>
        <td>
            <select class="status-select" onchange="updateOrderStatus(${order.order_id}, this.value)">
                <option value="pending" ${order.status==='pending'?'selected':''}>Pending</option>
                <option value="processing" ${order.status==='processing'?'selected':''}>Processing</option>
                <option value="shipped" ${order.status==='shipped'?'selected':''}>Shipped</option>
                <option value="delivered" ${order.status==='delivered'?'selected':''}>Delivered</option>
            </select>
        </td>
        <td>${order.delivery_type}</td>
        <td>${order.payment_method}</td>
        <td>${itemsHtml}</td>
    `;
    tbody.appendChild(row);
});

    } catch (err) {
        console.error("Failed to load orders:", err);
    }
}

async function updateOrderStatus(orderId, status) {
    console.log("Updating:", orderId, status); // Debug
    try {
        const res = await fetch('../admin-pages/admin-update-orders.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `order_id=${orderId}&status=${status}`
        });
        const data = await res.json();
        alert(data.message);
        loadOrders(); // Refresh orders
    } catch (err) {
        console.error("Error updating order:", err);
    }
}


// ---------------------- PRODUCTS ----------------------
async function loadProducts() {
    try {
        const res = await fetch('../admin-pages/admin-products.php');
        const products = await res.json();
        const tbody = document.getElementById('productsBody');
        tbody.innerHTML = '';

        products.forEach(product => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${product.id}</td>
                <td>${product.name}</td>
                <td>${product.category}</td>
                <td>₱${product.price}</td>
                <td><input type="number" value="${product.stock}" min="0" onchange="updateStock(${product.id}, this.value)"></td>
            `;
            tbody.appendChild(row);
        });
    } catch (err) {
        console.error("Failed to load products:", err);
    }
}

async function updateStock(productId, stock) {
    try {
        const res = await fetch('../admin-pages/admin-update-products.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${productId}&stock=${stock}`
        });
        const data = await res.json();
        if(data.success) loadProducts();
        else alert(data.message);
    } catch (err) {
        console.error("Error updating stock:", err);
    }
}

// ---------------------- HAMBURGER MENU ----------------------
function toggleMenu() {
    document.querySelector('.nav-links').classList.toggle('active');
}

// ---------------------- TABS ----------------------
function switchTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
    document.getElementById(tabId).style.display = 'block';
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`[data-tab="${tabId}"]`).classList.add('active');
}

// ---------------------- INITIAL LOAD ----------------------
window.onload = () => {
    loadOrders();
    loadProducts();
    document.querySelector('.hamburger').addEventListener('click', toggleMenu);
    document.querySelectorAll('.tab-btn').forEach(btn => btn.addEventListener('click', () => switchTab(btn.dataset.tab)));
    switchTab('ordersTab');
};
