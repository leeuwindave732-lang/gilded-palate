async function addToCart(productId) {
    try {
        const res = await fetch('../api/add-to-cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${productId}`,
            credentials: 'same-origin' // <-- this is crucial
        });
        const data = await res.json();
        alert(data.message);
        if (data.success) {
            // Optionally refresh cart count or UI
        }
    } catch (err) {
        console.error(err);
    }
}
