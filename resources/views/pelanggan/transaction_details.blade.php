@extends('pelanggan.layout')

@section('title', 'Detail Transaksi')

@section('content')
    <div class="container">
        <h2>Detail Transaksi #{{ $transaction->id }}</h2>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Informasi Transaksi</h5>
            </div>
            <div class="card-body">
                <p><strong>Tanggal:</strong> {{ $transaction->created_at->format('d-m-Y') }}</p>
                <p><strong>Total Harga:</strong> {{ $transaction->total_price }}</p>
            </div>
            <div class="card-body">
                <h5>Detail Barang:</h5>
                <ul class="list-group">
                    @foreach ($transaction->saleDetails as $detail)
                        <li class="list-group-item">
                            <strong>Nama Produk:</strong> {{ $detail->product->name }} <br>
                            <strong>Jumlah:</strong> {{ $detail->quantity }} <br>
                            <strong>Harga:</strong> {{ $detail->price }} <br>
                            <strong>Total:</strong> {{ $detail->total }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
