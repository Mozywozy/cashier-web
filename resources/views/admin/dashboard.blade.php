@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container">
    @include('sweetalert::alert')

    <div class="row">
        <!-- Statistik Total Produk -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Total Produk</div>
                <div class="card-body">
                    <h3>{{ $totalProducts }}</h3>
                </div>
            </div>
        </div>

        <!-- Statistik Total Penjualan Hari Ini -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Total Penjualan Hari Ini</div>
                <div class="card-body">
                    <h3>Rp. {{ number_format($totalSalesToday, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- Statistik Total Penjualan Kemarin -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Total Penjualan Kemarin</div>
                <div class="card-body">
                    <h3>Rp. {{ number_format($totalSalesYesterday, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Pendapatan per Hari -->
    <div class="card mt-4">
        <div class="card-header">
            <h4>Pendapatan per Hari</h4>
            <form action="{{ route('admin.dashboard') }}" method="GET">
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
        </div>
        <div class="card-body">
            <canvas id="dailySalesChart"></canvas>
        </div>
    </div>

    <!-- Tabel Pengguna -->
    <div class="card mt-4">
        <div class="card-header">
            <h4>Daftar Pengguna</h4>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Tambah Pengguna</button>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="confirmDelete('{{ route('admin.users.delete', $user->id) }}')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Pengguna -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="petugas_gudang">Petugas Gudang</option>
                                <option value="petugas_kasir">Petugas Kasir</option>
                                <option value="pelanggan">Pelanggan</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Konfirmasi Penghapusan
        function confirmDelete(url) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector(`form[action="${url}"]`).submit();
                }
            });
        }

        // Grafik Pendapatan per Hari
        var ctx = document.getElementById('dailySalesChart').getContext('2d');
        var dailySalesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($dailySales->pluck('date')),
                datasets: [{
                    label: 'Pendapatan',
                    data: @json($dailySales->pluck('total')),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString(); // Format number with commas
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</div>
@endsection
