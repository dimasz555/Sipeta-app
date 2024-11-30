@extends('layouts.app')

@section('title')
Riwayat Boking
@endsection

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Riwayat Boking</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('profil') }}">Home</a></li>
                <li class="breadcrumb-item active">Riwayat Boking</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="container">
        @if($boking->isEmpty())
        <p>Tidak Ada Data Boking.</p>
        @else
        <div class="row">
            @foreach($boking as $boking)
            <div class="col-md-5 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Project {{ $boking->project->name }}</h5>
                        <div class="row mb-1 align-items-center">
                            <div class="col-6 d-flex justify-content-between">
                                <strong>Tanggal Boking</strong>
                                <span>:</span>
                            </div>
                            <div class="col-6">{{ \Carbon\Carbon::parse($boking->tgl_boking)->translatedFormat('j F Y') }}</div>
                        </div>
                        <div class="row mb-1 align-items-center">
                            <div class="col-6 d-flex justify-content-between">
                                <strong>Harga Boking</strong>
                                <span>:</span>
                            </div>
                            <div class="col-6">Rp {{ number_format($boking->harga_boking, 0, ',', '.') }}</div>
                        </div>
                        <div class="row mb-1 align-items-center">
                            <div class="col-6 d-flex justify-content-between">
                                <strong>Nama Blok</strong>
                                <span>:</span>
                            </div>
                            <div class="col-6">{{ $boking->blok->blok ?? '-' }}</div>
                        </div>
                        <div class="row mb-1 align-items-center">
                            <div class="col-6 d-flex justify-content-between">
                                <strong>Nomor Blok</strong>
                                <span>:</span>
                            </div>
                            <div class="col-6">{{ $boking->no_blok ?? '-' }}</div>
                        </div>
                        <div class="row mb-1 align-items-center">
                            <div class="col-6 d-flex justify-content-between">
                                <strong>Tanggal Lunas</strong>
                                <span>:</span>
                            </div>
                            <div class="col-6">{{ $boking->tgl_lunas ? \Carbon\Carbon::parse($boking->tgl_lunas)->translatedFormat('j F Y') : '-' }}</div>
                        </div>
                        <div class="row mb-1 align-items-center">
                            <div class="col-6 d-flex justify-content-between">
                                <strong>Status</strong>
                                <span>:</span>
                            </div>
                            <div class="col-6"> @if ($boking->status === 'proses')
                                <span class="badge bg-warning">PROSES</span>
                                @elseif ($boking->status === 'lunas')
                                <span class="badge bg-success">LUNAS</span>
                                @elseif ($boking->status === 'batal')
                                <span class="badge bg-danger">BATAL</span>
                                @else
                                <span class="badge bg-secondary">Tidak Diketahui</span>
                                @endif
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