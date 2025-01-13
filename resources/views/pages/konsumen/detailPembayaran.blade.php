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
                        <td>: {{ "Rp " . number_format($pembayaran->harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Harga Boking</strong></td>
                        <td>: {{ "Rp " . number_format($pembayaran->boking->harga_boking, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Dp</strong></td>
                        <td>: {{ "Rp " . number_format($pembayaran->dp, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Bulan Cicilan </strong></td>
                        <td>: {{ $pembayaran->jumlah_bulan_cicilan }} Bulan</td>
                    </tr>

                    @if ($pembayaran->status === 'batal')
                    <tr>
                        <td><strong>Alasan Pembatalan</strong></td>
                        <td>: {{ $pembayaran->pembatalan->alasan_pembatalan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Pengembalian</strong></td>
                        <td>: {{ "Rp " . number_format($pembayaran->pembatalan->jumlah_pengembalian, 0, ',', '.') }}</td>
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
                                    {{ \Carbon\Carbon::create()->month($cicilan->bulan)->translatedFormat('F') }} {{ $cicilan->tahun }}
                                </div>
                            </div>
                            <div class="row mb-1 align-items-center">
                                <div class="col-6 d-flex justify-content-between">
                                    <strong>Harga Cicilan</strong>
                                    <span>:</span>
                                </div>
                                <div class="col-6">
                                    {{ "Rp " . number_format($cicilan->harga_cicilan, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="row mb-1 align-items-center">
                                <div class="col-6 d-flex justify-content-between">
                                    <strong>Tanggal Bayar</strong>
                                    <span>:</span>
                                </div>
                                <div class="col-6">
                                    @if($cicilan->tgl_bayar)
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
                            <div class="row mb-4 align-items-center">
                                <div class="col-6 d-flex justify-content-between">
                                    <strong>Kwitansi</strong>
                                    <span>:</span>
                                </div>
                                <div class="col-6">
                                    @if($cicilan->kwitansi)
                                    <a href="{{ asset('storage/kwitansi/' . $cicilan->kwitansi) }}" target="_blank" class="text-primary" style="text-decoration: underline;">
                                        Lihat Kwitansi
                                    </a>
                                    @else
                                    -
                                    @endif
                                </div>
                            </div>
                            @if($cicilan->status === 'belum dibayar')
                            <a href="{{ route('bayar.cicilan', Crypt::encrypt($cicilan->id)) }}" class="w-auto flex justify-center items-center position-absolute bottom-0 end-0 mb-2 mx-3" style="width: 150px; text-decoration: none;">
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