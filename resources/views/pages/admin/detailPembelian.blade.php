@extends('layouts.app')

@section('title')
Detail Pembelian
@endsection

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Detail Pembelian</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('index.pembelian') }}">Index Pembelian</a></li>
                <li class="breadcrumb-item active">Detail Pembelian</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="card">
        <div class="card-body">
            <table class="table table-borderless mt-3">
                <tbody>
                    <tr>
                        <td><strong>Nama Konsumen</strong></td>
                        <td>: {{ $pembelian->user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Pembelian</strong></td>
                        <td>: {{ \Carbon\Carbon::parse($pembelian->tgl_pembelian)->translatedFormat('j F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Project</strong></td>
                        <td>: {{ $pembelian->boking->project->name ?? 'Tidak Diketahui' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Blok</strong></td>
                        <td>: {{ $pembelian->boking->blok->blok }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nomor Blok</strong></td>
                        <td>: {{ $pembelian->boking->no_blok }}</td>
                    </tr>
                    <tr>
                        <td><strong>Harga Tanah</strong></td>
                        <td>: {{ "Rp " . number_format($pembelian->harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Dp</strong></td>
                        <td>: {{ "Rp " . number_format($pembelian->dp, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Bulan Cicilan </strong></td>
                        <td>: {{ $pembelian->jumlah_bulan_cicilan }} Bulan</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>:
                            @if ($pembelian->status === 'proses')
                            <span class="badge bg-warning">PROSES</span>
                            @elseif ($pembelian->status === 'selesai')
                            <span class="badge bg-success">SELESAI</span>
                            @elseif ($pembelian->status === 'batal')
                            <span class="badge bg-danger">BATAL</span>
                            @else
                            <span class="badge bg-secondary">Tidak Diketahui</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>


            <h5 class="card-title">Data Cicilan</h5>
            <div class="row">
                @foreach ($pembelian->cicilans as $cicilan)
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Cicilan ke-{{ $loop->iteration }}</h5>
                            <p class="card-text">{{ $cicilan->no_transaksi }}</p>
                            <p class="card-text"><strong>Bulan:</strong> {{ \Carbon\Carbon::create()->month($cicilan->bulan)->translatedFormat('F') }} {{ $cicilan->tahun }}</p>
                            <p class="card-text"><strong>Harga Cicilan:</strong> {{ "Rp " . number_format($cicilan->harga_cicilan, 0, ',', '.') }}</p>
                            <p class="card-text">
                                @if ($cicilan->status === 'belum dibayar')
                                <span class="badge bg-warning">BELUM DIBAYAR</span>
                                @elseif ($cicilan->status === 'lunas')
                                <span class="badge bg-success">LUNAS</span>
                                @else
                                <span class="badge bg-secondary">Tidak Diketahui</span>
                                @endif
                            </p>

                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</main>
@endsection