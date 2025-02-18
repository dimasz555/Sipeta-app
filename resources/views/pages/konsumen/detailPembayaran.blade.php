@extends('layouts.app')

@section('title')
    Detail Cicilan
@endsection

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Detail Cicilan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('profil') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('index.pembayaran.kavling') }}">Pembayaran Kavling</a></li>
                    <li class="breadcrumb-item active">Detail Cicilan</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <div class="card">
            <div class="card-body">

                <div class="card-body mt-4">
                    {{-- <h5 class="card-title">Laporan Pembyaran</h5> --}}

                    <!-- Laporan Cards -->
                    <div class="row">
                        <!-- Total Penjualan Card -->
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="card border-primary h-100">
                                <div
                                    class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                            style="width: 45px; height: 45px; font-size: 1.5rem;">
                                            <i class="bi bi-cash-stack"></i>
                                        </div>
                                        <span class="text-muted ps-2" style="font-size: 1.1rem;">Harga Tanah</span>
                                    </div>
                                    <h6 class="fs-4 fw-bold mb-0">Rp {{ number_format($pembayaran->harga, 0, ',', '.') }}
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <!-- Total Pemasukan Card -->
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="card border-success h-100">
                                <div
                                    class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white"
                                            style="width: 45px; height: 45px; font-size: 1.5rem;">
                                            <i class="bi bi-wallet2"></i>
                                        </div>
                                        <span class="text-muted ps-2" style="font-size: 1.1rem;">Total
                                            Pembayaran</span>
                                    </div>
                                    <h6 class="fs-4 fw-bold mb-0">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <!-- Total Piutang Card -->
                        <div class="col-md-4">
                            <div class="card border-danger h-100">
                                <div
                                    class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger text-white"
                                            style="width: 45px; height: 45px; font-size: 1.5rem;">
                                            <i class="bi bi-receipt"></i>
                                        </div>
                                        <span class="text-muted ps-2" style="font-size: 1.1rem;">Sisa
                                            Pembayaran</span>
                                    </div>
                                    <h6 class="fs-4 fw-bold mb-0">Rp {{ number_format($sisaPembayaran, 0, ',', '.') }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <table class="table table-borderless mt-3">
                    <tbody>
                        <tr>
                            <td><strong>Nama Konsumen</strong></td>
                            <td>: {{ $pembayaran->user->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Pembelian</strong></td>
                            <td>: {{ \Carbon\Carbon::parse($pembayaran->tgl_pembelian)->translatedFormat('j F Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Project</strong></td>
                            <td>: {{ $pembayaran->boking->project->name ?? 'Tidak Diketahui' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Blok</strong></td>
                            <td>: {{ $pembayaran->boking->blok->blok }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nomor Blok</strong></td>
                            <td>: {{ $pembayaran->boking->no_blok }}</td>
                        </tr>
                        <tr>
                            <td><strong>Harga Tanah</strong></td>
                            <td>: {{ 'Rp ' . number_format($pembayaran->harga, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Harga Boking</strong></td>
                            <td>: {{ 'Rp ' . number_format($pembayaran->boking->harga_boking, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Dp</strong></td>
                            <td>: {{ 'Rp ' . number_format($pembayaran->dp, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah Bulan Cicilan </strong></td>
                            <td>: {{ $pembayaran->jumlah_bulan_cicilan }} Bulan</td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>:
                                @if ($pembayaran->status === 'proses')
                                    <span class="badge bg-warning">PROSES</span>
                                @elseif ($pembayaran->status === 'selesai')
                                    <span class="badge bg-success">SELESAI</span>
                                @elseif ($pembayaran->status === 'batal')
                                    <span class="badge bg-danger">BATAL</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Diketahui</span>
                                @endif
                            </td>
                        </tr>
                        @if ($pembayaran->status === 'batal')
                            <tr>
                                <td><strong>Alasan Pembatalan</strong></td>
                                <td>: {{ $pembayaran->pembatalan->alasan_pembatalan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jumlah Pengembalian</strong></td>
                                <td>:
                                    {{ 'Rp ' . number_format($pembayaran->pembatalan->jumlah_pengembalian, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <h5 class="card-title">Data Cicilan</h5>
                <div class="row">
                    @foreach ($pembayaran->cicilans as $cicilan)
                        <div class="col-md-6"> <!-- Full width card -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Cicilan ke-{{ $cicilan->no_cicilan }}</h5>
                                    <div class="row mb-1 align-items-center">
                                        <div class="col-6 d-flex justify-content-between">
                                            <strong>No Transaksi</strong>
                                            <span>:</span>
                                        </div>
                                        <div class="col-6">
                                            {{ $cicilan->no_transaksi }}
                                        </div>
                                    </div>
                                    <div class="row mb-1 align-items-center">
                                        <div class="col-6 d-flex justify-content-between">
                                            <strong>Bulan</strong>
                                            <span>:</span>
                                        </div>
                                        <div class="col-6">
                                            {{ \Carbon\Carbon::create()->month($cicilan->bulan)->translatedFormat('F') }}
                                            {{ $cicilan->tahun }}
                                        </div>
                                    </div>
                                    <div class="row mb-1 align-items-center">
                                        <div class="col-6 d-flex justify-content-between">
                                            <strong>Harga Cicilan</strong>
                                            <span>:</span>
                                        </div>
                                        <div class="col-6">
                                            {{ 'Rp ' . number_format($cicilan->harga_cicilan, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="row mb-1 align-items-center">
                                        <div class="col-6 d-flex justify-content-between">
                                            <strong>Tanggal Bayar</strong>
                                            <span>:</span>
                                        </div>
                                        <div class="col-6">
                                            @if ($cicilan->tgl_bayar)
                                                {{ \Carbon\Carbon::parse($cicilan->tgl_bayar)->translatedFormat('j F Y') }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-1 align-items-center">
                                        <div class="col-6 d-flex justify-content-between">
                                            <strong>Status</strong>
                                            <span>:</span>
                                        </div>
                                        <div class="col-6">
                                            @if ($cicilan->status === 'belum dibayar')
                                                <span class="badge bg-warning">BELUM DIBAYAR</span>
                                            @elseif ($cicilan->status === 'lunas')
                                                <span class="badge bg-success">LUNAS</span>
                                            @elseif ($cicilan->status === 'batal')
                                                <span class="badge bg-danger">BATAL</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Diketahui</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-1 align-items-center">
                                        <div class="col-6 d-flex justify-content-between">
                                            <strong>Metode Pembayaran</strong>
                                            <span>:</span>
                                        </div>
                                        @if ($cicilan->payment_by === 'bca')
                                            <div class="col-6">
                                                BCA
                                            </div>
                                        @elseif ($cicilan->payment_by === 'bsi')
                                            <div class="col-6">
                                                BSI
                                            </div>
                                        @elseif ($cicilan->payment_by === 'cash')
                                            <div class="col-6">
                                                Cash
                                            </div>
                                        @elseif ($cicilan->payment_by === 'midtrans')
                                            <div class="col-6">
                                                Midtrans
                                            </div>
                                        @else
                                            <div class="col-6">
                                                -
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-6 d-flex justify-content-between">
                                            <strong>Kwitansi</strong>
                                            <span>:</span>
                                        </div>
                                        <div class="col-6">
                                            @if ($cicilan->kwitansi)
                                                <a href="{{ asset('storage/kwitansi/' . $cicilan->kwitansi) }}"
                                                    target="_blank" class="text-primary"
                                                    style="text-decoration: underline;">
                                                    Lihat Kwitansi
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                    @if ($cicilan->status === 'belum dibayar')
                                        <a href="{{ route('bayar.cicilan', Crypt::encrypt($cicilan->id)) }}"
                                            class="w-auto flex justify-center items-center position-absolute bottom-0 end-0 mb-2 mx-3"
                                            style="width: 150px; text-decoration: none;">
                                            <x-primary-button>
                                                Bayar Cicilan
                                            </x-primary-button>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
@endsection
