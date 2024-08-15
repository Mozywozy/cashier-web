@extends('kasir.layout')

@section('title', 'Edit Penjualan')

@section('content')
<div class="container">
    @include('sweetalert::alert')

    <div class="card">
        <div class="card-header">
            <h4>Edit Penjualan</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('kasir.update', $sale->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="user_id">Pengguna</label>
                    <select class="form-control" id="user_id" name="user_id" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $sale->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="products">Produk</label>
                    <div id="product-list">
                        @foreach($sale->details as $index => $detail)
                            <div class="product-item">
                                <select class="form-control" name="products[{{ $index }}][product_id]" required>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ $product->id == $detail->product_id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="number" class="form-control" name="products[{{ $index }}][quantity]" value="{{ $detail->quantity }}" placeholder="Jumlah" required>
                                <button type="button" class="btn btn-danger btn-sm remove-product">Hapus</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-primary" id="add-product">Tambah Produk</button>
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
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select>
        <input type="number" class="form-control" name="products[${index}][quantity]" placeholder="Jumlah" required>
        <button type="button" class="btn btn-danger btn-sm remove-product">Hapus</button>
    `;
    productList.appendChild(productItem);
});

document.getElementById('product-list').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-product')) {
        e.target.parentElement.remove();
    }
});
</script>
@endsection
