@extends('kasir.layout')

@section('title', 'Daftar Penjualan')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('sweetalert::alert')
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar Penjualan</h4>
                        <a href="{{ route('kasir.create') }}" class="btn btn-primary">Tambah Penjualan</a>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama User</th>
                                    <th>Total Belanja</th>
                                    <th>Tanggal Penjualan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $index => $sale)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $sale->user->name }}</td>
                                        <td>{{ $sale->total }}</td>
                                        <td>{{ $sale->created_at->format('d-m-Y H:i') }}</td>
                                        <td>
                                            {{-- <a href="{{ route('sales.details', $sale->id) }}" class="btn btn-info btn-sm">Detail</a> --}}
                                            <a href="{{ route('kasir.edit', $sale->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('kasir.destroy', $sale->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus penjualan ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
