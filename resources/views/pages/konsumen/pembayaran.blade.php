@extends('layouts.app')

@section('title')
Pembayaran Kavling
@endsection

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Pembayaran Kavling</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('profil') }}">Home</a></li>
                <li class="breadcrumb-item active">Pembayaran Kavling</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="container">
        @if($pembayaran->isEmpty())
        <p>Tidak Ada Data Pembayaran.</p>
        @else
        <div class="row">
            @foreach($pembayaran as $pb)
            <div class="col-md-5 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Project {{ $pb->boking->project->name }}</h5>
                        <div class="row mb-1 align-items-center">
                            <div class="col-6 d-flex justify-content-between">
                                <strong>Tanggal Pembelian</strong>
                                <span>:</span>
                            </div>
                            <div class="col-6">{{ \Carbon\Carbon::parse($pb->tgl_pembelian)->translatedFormat('j F Y') }}</div>
                        </div>
                        <div class="row mb-1 align-items-center">
                            <div class="col-6 d-flex justify-content-between">
                                <strong>Blok</strong>
                                <span>:</span>
                            </div>
                            <div class="col-6">{{ $pb->boking->blok->blok ?? '-' }}</div>
                        </div>
                        <div class="row mb-1 align-items-center">
                            <div class="col-6 d-flex justify-content-between">
                                <strong>Nomor Blok</strong>
                                <span>:</span>
                            </div>
                            <div class="col-6">{{ $pb->boking->no_blok ?? '-' }}</div>
                        </div>
                        <div class="row mb-1 align-items-center">
                            <div class="col-6 d-flex justify-content-between">
                                <strong>Harga</strong>
                                <span>:</span>
                            </div>
                            <div class="col-6">Rp {{ number_format($pb->harga, 0, ',', '.') }}</div>
                        </div>
                        <div class="row mb-1 align-items-center">
                            <div class="col-6 d-flex justify-content-between">
                                <strong>Dp</strong>
                                <span>:</span>
                            </div>
                            <div class="col-6">Rp {{ number_format($pb->dp, 0, ',', '.') ?? '-' }}</div>
                        </div>
                        <div class="row mb-1 align-items-center">
                            <div class="col-6 d-flex justify-content-between">
                                <strong>Tanggal Lunas</strong>
                                <span>:</span>
                            </div>
                            <div class="col-6">{{ $pb->tgl_lunas ? \Carbon\Carbon::parse($pb->tgl_lunas)->translatedFormat('j F Y') : '-' }}</div>
                        </div>
                        <div class="row mb-1 align-items-center">
                            <div class="col-6 d-flex justify-content-between">
                                <strong>PJB</strong>
                                <span>:</span>
                            </div>
                            <div class="col-6">@if($pb->pjb)
                                <a href="{{ asset('storage/' . $pb->pjb) }}" target="_blank" class="text-primary" style="text-decoration: underline;">
                                    Lihat PJB
                                </a>
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
                            <div class="col-6"> @if ($pb->status === 'proses')
                                <span class="badge bg-warning">PROSES</span>
                                @elseif ($pb->status === 'selesai')
                                <span class="badge bg-success">SELESAI</span>
                                @elseif ($pb->status === 'batal')
                                <span class="badge bg-danger">BATAL</span>
                                @else
                                <span class="badge bg-secondary">Tidak Diketahui</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 d-flex justify-content-end">
                                <a href="{{ route('pembayaran.kavling.detail', $pb->encrypted_id) }}" class="w-full flex justify-center items-center" style="width: 150px; text-decoration: none;">
                                    <x-primary-button>Lihat Cicilan</x-primary-button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</main>

@endsection