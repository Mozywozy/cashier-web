@extends('kasir.layout')

@section('title', 'Tambah Penjualan')

@section('content')
<div class="container">
    @include('sweetalert::alert')

    <div class="card">
        <div class="card-header">
            <h4>Tambah Penjualan</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('kasir.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="user_id">Pengguna</label>
                    <select class="form-control" id="user_id" name="user_id" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="products">Produk</label>
                    <div id="product-list">
                        <!-- Product list will be dynamically added here -->
                    </div>
                    <button type="button" class="btn btn-primary" id="add-product">Tambah Produk</button>
                </div>
                <div class="form-group">
                    <h5>Detail Transaksi</h5>
                    <table class="table" id="summary-table">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Summary rows will be dynamically added here -->
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('add-product').addEventListener('click', function() {
    let productList = document.getElementById('product-list');
    let index = productList.children.length;
    let productItem = document.createElement('div');
    productItem.className = 'product-item';
    productItem.innerHTML = `
        <select class="form-control" name="products[${index}][product_id]" required>
            @foreach($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
            @endforeach
        </select>
        <input type="number" class="form-control" name="products[${index}][quantity]" placeholder="Jumlah" required>
        <button type="button" class="btn btn-danger btn-sm remove-product">Hapus</button>
    `;
    productList.appendChild(productItem);
    updateSummary();
});

document.getElementById('product-list').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-product')) {
        e.target.parentElement.remove();
        updateSummary();
    }
});

document.getElementById('product-list').addEventListener('change', function(e) {
    if (e.target.matches('select[name^="products"]') || e.target.matches('input[name^="products"]')) {
        updateSummary();
    }
});

function updateSummary() {
    let summaryTableBody = document.querySelector('#summary-table tbody');
    summaryTableBody.innerHTML = ''; // Clear existing rows

    document.querySelectorAll('.product-item').forEach((item, index) => {
        let productSelect = item.querySelector('select[name^="products"]');
        let quantityInput = item.querySelector('input[name^="products"]');
        let productId = productSelect.value;
        let quantity = quantityInput.value;
        
        if (productId && quantity) {
            let productName = productSelect.options[productSelect.selectedIndex].text;
            let price = parseFloat(productSelect.options[productSelect.selectedIndex].dataset.price);
            let total = price * quantity;

            let row = document.createElement('tr');
            row.innerHTML = `
                <td>${productName}</td>
                <td>${quantity}</td>
                <td>${price.toFixed(2)}</td>
                <td>${total.toFixed(2)}</td>
            `;
            summaryTableBody.appendChild(row);
        }
    });
}
</script>
@endsection
