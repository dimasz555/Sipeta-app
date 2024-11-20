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
                <li class="breadcrumb-item"><a href="{{ route('index.pembelian') }}">Kelola Pembelian</a></li>
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
                        <td><strong>Harga Boking</strong></td>
                        <td>: {{ "Rp " . number_format($pembelian->boking->harga_boking, 0, ',', '.') }}</td>
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
                        <td><strong>PJB </strong></td>
                        <td>: @if($pembelian->pjb)
                            <a href="{{ asset('storage/' . $pembelian->pjb) }}" target="_blank" class="text-primary" style="text-decoration: underline;">
                                Lihat PJB
                            </a>
                            @else
                            -
                            @endif
                        </td>
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

            <div class="row">
                @foreach ($pembelian->cicilans as $cicilan)
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Cicilan ke-{{ $cicilan->no_cicilan }}</h5>
                            <div class="row mb-1 align-items-center">
                                <div class="col-6 d-flex justify-content-between">
                                    <strong>No Transaksi:</strong>
                                    <span>:</span>
                                </div>
                                <div class="col-6">
                                    {{ $cicilan->no_transaksi }}
                                </div>
                            </div>
                            <div class="row mb-1 align-items-center">
                                <div class="col-6 d-flex justify-content-between">
                                    <strong>Bulan:</strong>
                                    <span>:</span>
                                </div>
                                <div class="col-6">
                                    {{ \Carbon\Carbon::create()->month($cicilan->bulan)->translatedFormat('F') }} {{ $cicilan->tahun }}
                                </div>
                            </div>
                            <div class="row mb-1 align-items-center">
                                <div class="col-6 d-flex justify-content-between">
                                    <strong>Harga Cicilan:</strong>
                                    <span>:</span>
                                </div>
                                <div class="col-6">
                                    {{ "Rp " . number_format($cicilan->harga_cicilan, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="row mb-1 align-items-center">
                                <div class="col-6 d-flex justify-content-between">
                                    <strong>Tanggal Bayar:</strong>
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
                                    <strong>Status:</strong>
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
                            @if ($cicilan->status !== 'belum dibayar')
                            <x-primary-button
                                class="w-full flex justify-center items-center position-absolute bottom-0 end-0 mb-2 mx-3"
                                style="width: 100px;"
                                data-bs-toggle="modal"
                                data-bs-target="#uploadKwitansiModal"
                                title="Upload Kwitansi"
                                data-id="{{ $cicilan->id }}"
                                data-no-transaksi="{{ $cicilan->no_transaksi }}"
                                data-no-cicilan="{{ $cicilan->no_cicilan }}"
                                data-harga-cicilan="{{ $cicilan->harga_cicilan }}">
                                <i class="bi bi-upload me-2"></i> Kwitansi
                            </x-primary-button>
                            @else
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Upload Kwitansi -->
    <div class="modal fade" id="uploadKwitansiModal" tabindex="-1" aria-labelledby="uploadKwitansiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('upload.kwitansi') }}" method="POST" enctype="multipart/form-data" id="uploadKwitansiForm">
                    @csrf
                    <div class="modal-body px-sm-5 mx-50 pb-5">
                        <h3 class="text-center mb-1" id="uploadKwitansiModalLabel">Upload Kwitansi</h3>
                        <div class="mb-4">
                            <div class="row">
                                <!-- Cicilan ke -->
                                <div class="col-5">
                                    <strong>Cicilan ke</strong>
                                </div>
                                <div class="col-1 text-center">
                                    <strong>:</strong>
                                </div>
                                <div class="col-6">
                                    <span id="no_cicilan_modal"></span>
                                </div>
                            </div>
                            <div class="row">
                                <!-- No Transaksi -->
                                <div class="col-5">
                                    <strong>No Transaksi</strong>
                                </div>
                                <div class="col-1 text-center">
                                    <strong>:</strong>
                                </div>
                                <div class="col-6">
                                    <span id="no_transaksi_modal"></span>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Harga Cicilan -->
                                <div class="col-5">
                                    <strong>Harga Cicilan</strong>
                                </div>
                                <div class="col-1 text-center">
                                    <strong>:</strong>
                                </div>
                                <div class="col-6">
                                    <span id="harga_cicilan_modal"></span>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="id" id="id">

                        <!-- Form Input Kwitansi -->
                        <div class="col-12 mb-3">
                            <label for="kwitansi" class="form-label">Kwitansi</label>
                            <input type="file" class="form-control" id="kwitansi" name="kwitansi" accept=".pdf, .jpg, .jpeg, .png" required>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Upload</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kembali</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>

<script>
    // Menangani klik pada tombol upload kwitansi
    var modal = document.getElementById('uploadKwitansiModal');
    modal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget; // Tombol yang diklik
        var id = button.getAttribute('data-id');
        var noCicilan = button.getAttribute('data-no-cicilan');
        var noTransaksi = button.getAttribute('data-no-transaksi');
        var hargaCicilan = button.getAttribute('data-harga-cicilan');

        // Set nilai input di dalam modal
        document.getElementById('id').value = id;
        document.getElementById('no_cicilan_modal').textContent = noCicilan;
        document.getElementById('no_transaksi_modal').textContent = noTransaksi;
        document.getElementById('harga_cicilan_modal').textContent = 'Rp ' + new Intl.NumberFormat().format(hargaCicilan);
    });
</script>

@endsection