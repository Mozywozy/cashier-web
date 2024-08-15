@extends('gudang.layout')

@section('title', 'Daftar Produk')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('sweetalert::alert')
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar Produk</h4>
                        <a href="{{ route('gudang.create') }}" class="btn btn-primary">Tambah Produk</a>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Stok</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1  }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->stok }}</td>
                                        <td>{{ $product->price }}</td>
                                        <td>{{ $product->status }}</td>
                                        <td>
                                            <a href="{{ route('gudang.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('gudang.destroy', $product->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</button>
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
