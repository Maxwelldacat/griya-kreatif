<!-- CART SIDEBAR (OFFCANVAS) -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel" style="width: 400px; background-color: #faf8f5;">
    
    <!-- Header Sidebar -->
    <div class="offcanvas-header text-white" style="background: linear-gradient(135deg, #4a154b 0%, #8a264a 100%);">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-cart3 fs-3"></i> 
            <div>
                <h5 class="offcanvas-title fw-bold mb-0" id="cartOffcanvasLabel">Keranjang Belanja</h5>
                <small class="text-white-50" id="sidebarCartCount">
                    @auth
                        {{ auth()->user()->carts()->count() }} item
                    @else
                        0 item
                    @endauth
                </small>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <!-- Body Sidebar -->
    <div class="offcanvas-body d-flex flex-column p-0">
        @auth
            @if(auth()->user()->carts()->count() > 0)
                <div class="p-3 flex-grow-1" style="overflow-y: auto;" id="sidebarCartItems">
                    @php $totalPrice = 0; @endphp
                    @foreach(auth()->user()->carts as $cart)
                        @php $totalPrice += $cart->product->price * $cart->quantity; @endphp
                        <div class="card mb-3 border-0 shadow-sm rounded-4" id="cart-item-{{ $cart->id }}">
                            <div class="card-body p-3">
                                <div class="d-flex gap-3">
                                    <img src="{{ $cart->product->image_url }}" alt="{{ $cart->product->name }}" style="width: 70px; height: 70px; object-fit: contain; border-radius: 8px;" onerror="this.src='https://via.placeholder.com/70?text=No+Img'">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1 text-truncate" style="max-width: 180px;">{{ $cart->product->name }}</h6>
                                        <p class="text-danger fw-bold mb-2 item-price" data-price="{{ $cart->product->price }}">{{ $cart->product->formatted_price }}</p>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="input-group input-group-sm" style="width: 100px;">
                                                <button class="btn btn-light border btn-decrease" type="button" data-id="{{ $cart->id }}" data-qty="{{ $cart->quantity }}">-</button>
                                                <input type="text" class="form-control text-center border bg-white qty-input" id="qty-{{ $cart->id }}" value="{{ $cart->quantity }}" readonly>
                                                <button class="btn btn-light border btn-increase" type="button" data-id="{{ $cart->id }}" data-qty="{{ $cart->quantity }}" data-max="{{ $cart->product->stock }}">+</button>
                                            </div>
                                            <button class="btn btn-link text-danger p-0 text-decoration-none small btn-delete" data-id="{{ $cart->id }}">Hapus</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-4 bg-white border-top mt-auto">
                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <span>Subtotal</span>
                        <span id="sidebarSubtotal" data-total="{{ (int)$totalPrice }}">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>                          </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                        <span>Total</span>
                        <span id="sidebarTotal" data-total="{{ (int)$totalPrice }}">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn w-100 py-3 text-white fw-bold rounded-3" style="background-color: #4a154b;">
                        Checkout <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            @else
                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center px-4" id="sidebarEmptyState">
                    <h5 class="text-muted mb-4" style="font-family: serif;">Keranjang masih kosong</h5>
                    <svg width="150" height="150" viewBox="0 0 24 24" fill="none" stroke="#d3d3d3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-4">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <button type="button" class="btn text-white px-4 py-2 rounded-3 fw-bold" style="background-color: #8a264a;" data-bs-dismiss="offcanvas">
                        Mulai Belanja
                    </button>
                </div>
            @endif
        @else
            <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center px-4">
                <svg width="150" height="150" viewBox="0 0 24 24" fill="none" stroke="#d3d3d3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-4">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
                <h6 class="text-muted fw-bold mb-3">Silakan masuk untuk melihat keranjang</h6>
                <button type="button" class="btn text-white px-4 py-2 rounded-3 fw-bold" style="background-color: #8a264a;" data-bs-toggle="modal" data-bs-target="#authModal" onclick="document.getElementById('pills-login-tab').click()" data-bs-dismiss="offcanvas">
                    Masuk Sekarang
                </button>
            </div>
        @endauth
    </div>
</div>

<!-- SCRIPT AJAX UNTUK KERANJANG SAMPING -->
@auth
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number).replace("Rp", "Rp ");
    };

    const updateUI = (newTotalPrice) => {
        document.getElementById('sidebarSubtotal').innerText = formatRupiah(newTotalPrice);
        document.getElementById('sidebarTotal').innerText = formatRupiah(newTotalPrice);
        
        // Update jumlah item di header sidebar
        const count = document.querySelectorAll('#sidebarCartItems .card').length;
        document.getElementById('sidebarCartCount').innerText = count + ' item';
        
        // Update badge di navbar
        const navbarBadge = document.querySelector('.cart-badge');
        if(navbarBadge) navbarBadge.innerText = count;
    };

    // TOMBOL PLUS (+)
    document.querySelectorAll('.btn-increase').forEach(btn => {
        btn.addEventListener('click', async function() {
            let id = this.dataset.id;
            let qtyInput = document.getElementById(`qty-${id}`);
            let currentQty = parseInt(qtyInput.value);
            let maxStock = parseInt(this.dataset.max);

            if (currentQty >= maxStock) {
                alert('Stok tidak mencukupi!');
                return;
            }

            // 1. UBAH ANGKA DI LAYAR SEKETIKA (Optimistic UI)
            let newQty = currentQty + 1;
            qtyInput.value = newQty;

            let price = parseInt(document.querySelector(`#cart-item-${id} .item-price`).dataset.price);
            let currentTotal = parseInt(document.getElementById('sidebarSubtotal').dataset.total);
            let newTotal = currentTotal + price;
            
            document.getElementById('sidebarSubtotal').dataset.total = newTotal;
            updateUI(newTotal);

            // 2. KIRIM KE SERVER DI BELAKANG LAYAR
            let formData = new FormData();
            formData.append('_method', 'PATCH');
            formData.append('quantity', newQty);

            try {
                const res = await fetch(`/cart/${id}`, {
                    method: 'POST', 
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if(!res.ok) {
                    // Jika server menolak (misal stok habis), kembalikan angka ke semula
                    qtyInput.value = currentQty;
                    document.getElementById('sidebarSubtotal').dataset.total = currentTotal;
                    updateUI(currentTotal);
                    alert('Gagal update. Mungkin stok habis.');
                }
            } catch (error) {
                qtyInput.value = currentQty;
                document.getElementById('sidebarSubtotal').dataset.total = currentTotal;
                updateUI(currentTotal);
                console.error(error);
            }
        });
    });

    // TOMBOL MINUS (-)
    document.querySelectorAll('.btn-decrease').forEach(btn => {
        btn.addEventListener('click', async function() {
            let id = this.dataset.id;
            let qtyInput = document.getElementById(`qty-${id}`);
            let currentQty = parseInt(qtyInput.value);

            if (currentQty <= 1) return;

            // 1. UBAH ANGKA DI LAYAR SEKETIKA
            let newQty = currentQty - 1;
            qtyInput.value = newQty;

            let price = parseInt(document.querySelector(`#cart-item-${id} .item-price`).dataset.price);
            let currentTotal = parseInt(document.getElementById('sidebarSubtotal').dataset.total);
            let newTotal = currentTotal - price;
            
            document.getElementById('sidebarSubtotal').dataset.total = newTotal;
            updateUI(newTotal);

            // 2. KIRIM KE SERVER
            let formData = new FormData();
            formData.append('_method', 'PATCH');
            formData.append('quantity', newQty);

            try {
                const res = await fetch(`/cart/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if(!res.ok) {
                    qtyInput.value = currentQty;
                    document.getElementById('sidebarSubtotal').dataset.total = currentTotal;
                    updateUI(currentTotal);
                }
            } catch (error) {
                qtyInput.value = currentQty;
                document.getElementById('sidebarSubtotal').dataset.total = currentTotal;
                updateUI(currentTotal);
            }
        });
    });

    // TOMBOL HAPUS
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', async function() {
            let id = this.dataset.id;
            
            if(!confirm('Hapus produk ini dari keranjang?')) return;

            let formData = new FormData();
            formData.append('_method', 'DELETE');

            try {
                const res = await fetch(`/cart/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if(res.ok) {
                    let itemCard = document.getElementById(`cart-item-${id}`);
                    let price = parseInt(itemCard.querySelector('.item-price').dataset.price);
                    let qty = parseInt(itemCard.querySelector('.qty-input').value);
                    let currentTotal = parseInt(document.getElementById('sidebarSubtotal').dataset.total);
                    
                    itemCard.remove();
                    let newTotal = currentTotal - (price * qty);
                    
                    if(newTotal <= 0) {
                        window.location.reload(); 
                    } else {
                        document.getElementById('sidebarSubtotal').dataset.total = newTotal;
                        updateUI(newTotal);
                    }
                }
            } catch (error) { console.error(error); }
        });
    });
});
</script>
@endauth