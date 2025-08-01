@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Transaksi Baru</h2>

    {{-- Pesan error dari backend jika ada --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('transactions.store') }}" id="transactionForm">
        @csrf

        {{-- Bagian Pencarian Produk --}}
        <div class="card mb-4">
            <div class="card-header">Pilih Produk</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="product_search" class="form-label">Cari Produk</label>
                    <input type="text" class="form-control" id="product_search" placeholder="Ketik nama produk...">
                    <div id="product_suggestions" class="list-group position-absolute z-index-1000" 
                         style="width: 90%; max-height: 200px; overflow-y: auto; background-color: white; border: 1px solid #ddd; display: none;">
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Keranjang Belanja --}}
        <div class="card mb-4">
            <div class="card-header">Keranjang Belanja</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="cartTable">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Produk</th>
                                <th>Harga Satuan</th>
                                <th>Stok Tersedia</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="no-items-row">
                                <td colspan="6" class="text-center text-muted">Belum ada produk di keranjang.</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total Belanja:</strong></td>
                                <td colspan="2">
                                    <input type="text" id="total_price_display" class="form-control fw-bold" value="Rp 0" readonly>
                                    <input type="hidden" name="total_price" id="total_price_hidden">
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Detail Pembayaran --}}
        <div class="card mb-4">
            <div class="card-header">Detail Pembayaran</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="payment_method" class="form-label">Metode Pembayaran</label>
                    <select class="form-select" id="payment_method" name="payment_method" required>
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="cash">Tunai</option>
                        @foreach($payments as $payment)
                            @if(strtolower($payment->name) === 'qris')
                                <option value="qris" data-qr="{{ $payment->qr_code ? asset('storage/'.$payment->qr_code) : '' }}">
                                    QRIS
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('payment_method')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- QRIS Preview --}}
                <div class="mb-3" id="qr-container" style="display:none;">
                    <label class="form-label">Scan QR Code</label>
                    <div>
                        <img id="qr-image" src="" alt="QRIS" style="max-height:200px;">
                    </div>
                </div>

                {{-- Jumlah Bayar --}}
                <div class="mb-3" id="paid-section">
                    <label for="paid" class="form-label">Jumlah Bayar</label>
                    <input type="number" name="paid" id="paid" class="form-control" step="any" min="0" required>
                    <div class="invalid-feedback" id="paid-feedback"></div>
                    @error('paid')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Kembalian --}}
                <div class="mb-3" id="change-section">
                    <label for="change_display" class="form-label">Kembalian</label>
                    <input type="text" id="change_display" class="form-control fw-bold" value="Rp 0" readonly>
                    <input type="hidden" name="change" id="change_hidden">
                </div>

                <button type="submit" class="btn btn-success" id="processPaymentBtn" disabled>Proses Pembayaran</button>
            </div>
        </div>
    </form>
</div>

{{-- Script JavaScript --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productSearchInput = document.getElementById('product_search');
        const productSuggestions = document.getElementById('product_suggestions');
        const cartTableBody = document.querySelector('#cartTable tbody');
        const noItemsRow = document.getElementById('no-items-row');
        const totalInputDisplay = document.getElementById('total_price_display');
        const totalInputHidden = document.getElementById('total_price_hidden');
        const paidInput = document.getElementById('paid');
        const changeInputDisplay = document.getElementById('change_display');
        const changeInputHidden = document.getElementById('change_hidden');
        const processPaymentBtn = document.getElementById('processPaymentBtn');
        const paymentMethodSelect = document.getElementById('payment_method');
        const qrContainer = document.getElementById('qr-container');
        const qrImage = document.getElementById('qr-image');
        const paidSection = document.getElementById('paid-section');
        const changeSection = document.getElementById('change-section');

        let cartItems = [];

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        function updateTotals() {
            let total = 0;
            cartItems.forEach(item => total += item.quantity * item.price);

            totalInputHidden.value = total;
            totalInputDisplay.value = formatRupiah(total);

            if (paymentMethodSelect.value === 'qris') {
                paidInput.value = total;
                changeInputHidden.value = 0;
                changeInputDisplay.value = formatRupiah(0);
                processPaymentBtn.disabled = cartItems.length === 0 || paymentMethodSelect.value === '';
            } else {
                const paid = parseFloat(paidInput.value) || 0;
                const change = paid - total;
                changeInputHidden.value = change;
                changeInputDisplay.value = formatRupiah(change);

                const isCartEmpty = cartItems.length === 0;
                const isPaidEnough = paid >= total;
                const isPaymentMethodSelected = paymentMethodSelect.value !== '';

                if (paid < total && !isCartEmpty) {
                    paidInput.classList.add('is-invalid');
                    document.getElementById('paid-feedback').textContent = 'Jumlah bayar kurang dari total belanja.';
                } else {
                    paidInput.classList.remove('is-invalid');
                    document.getElementById('paid-feedback').textContent = '';
                }

                processPaymentBtn.disabled = isCartEmpty || !isPaidEnough || !isPaymentMethodSelected;
            }
        }

        function addProductToCart(product) {
            const existingItemIndex = cartItems.findIndex(item => item.product_id === product.id);

            if (existingItemIndex > -1) {
                if (cartItems[existingItemIndex].quantity < product.stock) {
                    cartItems[existingItemIndex].quantity++;
                } else {
                    alert('Stok produk "' + product.name + '" tidak mencukupi!');
                    return;
                }
            } else {
                if (product.stock > 0) {
                    cartItems.push({
                        product_id: product.id,
                        name: product.name,
                        price: product.price,
                        stock: product.stock,
                        quantity: 1
                    });
                } else {
                    alert('Stok produk "' + product.name + '" kosong!');
                    return;
                }
            }
            renderCart();
            updateTotals();
            productSearchInput.value = '';
            productSuggestions.innerHTML = '';
            productSuggestions.style.display = 'none';
        }

        function renderCart() {
            cartTableBody.innerHTML = '';
            if (cartItems.length === 0) {
                noItemsRow.style.display = 'table-row';
                cartTableBody.appendChild(noItemsRow);
            } else {
                noItemsRow.style.display = 'none';
                cartItems.forEach((item, index) => {
                    const subtotal = item.quantity * item.price;
                    const row = cartTableBody.insertRow();
                    row.innerHTML = `
                        <td>${item.name}</td>
                        <td>${formatRupiah(item.price)}</td>
                        <td>${item.stock}</td>
                        <td>
                            <input type="number" name="items[${index}][quantity]"
                                class="form-control cart-qty" value="${item.quantity}" min="1" max="${item.stock}"
                                data-index="${index}">
                            <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                            <input type="hidden" name="items[${index}][price]" value="${item.price}">
                            <input type="hidden" name="items[${index}][subtotal]" value="${subtotal}">
                        </td>
                        <td><input type="text" class="form-control subtotal-display" value="${formatRupiah(subtotal)}" readonly></td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-item" data-index="${index}">Hapus</button></td>
                    `;
                });
                attachCartEventListeners();
            }
        }

        function attachCartEventListeners() {
            document.querySelectorAll('.cart-qty').forEach(input => {
                input.addEventListener('input', function() {
                    const index = parseInt(this.dataset.index);
                    let newQty = parseInt(this.value);
                    const maxStock = parseInt(this.max);

                    if (isNaN(newQty) || newQty < 1) newQty = 1;
                    if (newQty > maxStock) {
                        alert(`Kuantitas tidak boleh melebihi stok tersedia (${maxStock}).`);
                        newQty = maxStock;
                    }

                    this.value = newQty;
                    cartItems[index].quantity = newQty;

                    const subtotal = newQty * cartItems[index].price;
                    this.closest('tr').querySelector('.subtotal-display').value = formatRupiah(subtotal);
                    this.closest('tr').querySelector(`input[name="items[${index}][subtotal]"]`).value = subtotal;

                    updateTotals();
                });
            });

            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.dataset.index);
                    cartItems.splice(index, 1);
                    renderCart();
                    updateTotals();
                });
            });
        }

        paymentMethodSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const qrCode = selected.getAttribute('data-qr');

            if (this.value === 'qris') {
                qrImage.src = qrCode;
                qrContainer.style.display = 'block';
                paidSection.style.display = 'none';
                changeSection.style.display = 'none';
            } else {
                qrContainer.style.display = 'none';
                qrImage.src = '';
                paidSection.style.display = 'block';
                changeSection.style.display = 'block';
            }
            updateTotals();
        });

        let searchTimeout;
        productSearchInput.addEventListener('keyup', function () {
            clearTimeout(searchTimeout);
            const query = this.value;
            productSuggestions.innerHTML = '';

            if (query.length < 2) {
                productSuggestions.style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`/api/products/search?query=${query}`)
                    .then(response => response.json())
                    .then(products => {
                        productSuggestions.innerHTML = '';
                        if (products.length > 0) {
                            products.forEach(product => {
                                const div = document.createElement('a');
                                div.href = '#';
                                div.classList.add('list-group-item', 'list-group-item-action');
                                div.innerHTML = `<strong>${product.name}</strong> (${formatRupiah(product.price)}) - Stok: ${product.stock}`;
                                div.addEventListener('click', (e) => {
                                    e.preventDefault();
                                    addProductToCart(product);
                                });
                                productSuggestions.appendChild(div);
                            });
                            productSuggestions.style.display = 'block';
                        } else {
                            productSuggestions.innerHTML = '<div class="list-group-item text-muted">Produk tidak ditemukan.</div>';
                            productSuggestions.style.display = 'block';
                        }
                    })
                    .catch(() => {
                        productSuggestions.innerHTML = '<div class="list-group-item text-danger">Gagal memuat produk.</div>';
                        productSuggestions.style.display = 'block';
                    });
            }, 300);
        });

        document.addEventListener('click', function(event) {
            if (!productSuggestions.contains(event.target) && event.target !== productSearchInput) {
                productSuggestions.style.display = 'none';
            }
        });

        paidInput.addEventListener('input', updateTotals);

        renderCart();
        updateTotals();
    });
</script>
@endsection
