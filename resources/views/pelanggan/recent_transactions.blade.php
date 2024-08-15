@extends('pelanggan.layout')

@section('title', 'Transaksi Terbaru')

@section('content')
    <div class="container">
        <h2>Transaksi Terbaru</h2>
        <div class="row">
            @forelse ($transactions as $transaction)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Transaksi #{{ $transaction->id }}</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Tanggal:</strong> {{ $transaction->created_at->format('d-m-Y') }}</p>
                            <p><strong>Total Harga:</strong> {{ $transaction->total_price }}</p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('pelanggan.transaction_details', $transaction->id) }}" class="btn btn-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        Belum ada transaksi terbaru.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
