@extends('kasir.layout')

@section('title', 'Detail Penjualan')

@section('content')
<div class="container">
    @include('sweetalert::alert')

    <div class="card">
        <div class="card-header">
            <h4>Detail Penjualan</h4>
            <a href="{{ route('kasir.details.export') }}" class="btn btn-primary">Export PDF</a>
        </div>
        <div class="card-body">
            <form action="{{ route('kasir.sale_details') }}" method="GET">
                <div class="form-group">
                    <label for="month">Pilih Bulan dan Tahun</label>
                    <div class="input-group">
                        <select class="form-control" id="month" name="month">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>
                        <select class="form-control" id="year" name="year">
                            @for ($y = 2020; $y <= date('Y'); $y++)
                                <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Filter</button>
                        </div>
                    </div>
                </div>
            </form>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Penjualan</th>
                        <th>Nama Produk</th>
                        <th>Kuantitas</th>
                        <th>Harga</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($saleDetails as $key => $detail)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $detail->sale->created_at->format('d-m-Y') }}</td>
                        <td>{{ $detail->product->name }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->price, 2, ',', '.') }}</td>
                        <td>{{ number_format($detail->total, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
