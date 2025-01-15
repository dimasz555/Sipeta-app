@extends('layouts.app')

@section('title')
Pembayaran Cicilan
@endsection

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Pembayaran Cicilan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('profil') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('index.pembayaran.kavling') }}">Pembayaran Kavling</a></li>
                <li class="breadcrumb-item active">Pembayaran Cicilan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Detail Cicilan</h5>
            <p>Berikut adalah detail cicilan yang harus dibayar. Silakan lakukan pembayaran untuk cicilan ini:</p>


            <!-- Tampilkan detail cicilan -->
            <div class="row mb-2">
                <div class="col-6 d-flex justify-content-between">
                    <strong>Cicilan ke-</strong>
                    <span>:</span>
                </div>
                <div class="col-6">{{ $cicilan->no_cicilan }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-6 d-flex justify-content-between">
                    <strong>No Transaksi</strong>
                    <span>:</span>
                </div>
                <div class="col-6">{{ $cicilan->no_transaksi }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-6 d-flex justify-content-between">
                    <strong>Harga Cicilan</strong>
                    <span>:</span>
                </div>
                <div class="col-6">{{ "Rp " . number_format($cicilan->harga_cicilan, 0, ',', '.') }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-6 d-flex justify-content-between">
                    <strong>Bulan Cicilan</strong>
                    <span>:</span>
                </div>
                <div class="col-6">{{ \Carbon\Carbon::create()->month($cicilan->bulan)->translatedFormat('F') }} {{ $cicilan->tahun }}</div>
            </div>
            
            </div>
            <!-- Pesan Peringatan Batas Waktu Pembayaran -->
            <div class="alert alert-warning mb-4">
                <strong>Peringatan!</strong> Batas waktu pembayaran dilakukan selama 15 menit.
                Jika melewati waktu, silakan refresh halaman ini atau <a href="javascript:history.back()">kembali ke halaman sebelumnya</a> untuk melakukan pembayaran kembali.
            </div>

            @if($cicilan->status === 'belum dibayar')
            <!-- Tombol Bayar dengan x-primary-button -->
            <x-primary-button
                id="pay-button"
                class="w-100 flex justify-center items-center mb-3"> <!-- Tombol mengisi lebar penuh card -->
                Bayar Sekarang
            </x-primary-button>
            @elseif($cicilan->status === 'pending')
            <x-primary-button class="w-100 flex justify-center items-center mb-3" disabled>
                Menunggu Pembayaran
            </x-primary-button>
            @elseif($cicilan->status === 'lunas')
            <x-primary-button class="w-100 flex justify-center items-center mb-3" disabled>
                Sudah Dibayar
            </x-primary-button>
            @endif
        </div>
    </div>
</main>

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.clientKey') }}"></script>

<script type="text/javascript">
    var snapToken = "{{ $snapToken }}";

    var payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function() {
        window.snap.pay(snapToken, {
            onSuccess: function(result) {
                // Tambahkan parameter success pada URL
                window.location.href = "{{ route('pembayaran.kavling.detail', ['id' => Crypt::encrypt($cicilan->pembelian->id)]) }}?payment_status=success";
            },
            onPending: function(result) {
                window.location.href = "{{ route('pembayaran.kavling.detail', ['id' => Crypt::encrypt($cicilan->pembelian->id)]) }}?payment_status=pending";
            },
            onError: function(result) {
                window.location.href = "{{ route('pembayaran.kavling.detail', ['id' => Crypt::encrypt($cicilan->pembelian->id)]) }}?payment_status=error";
            },
            onClose: function() {
                window.location.href = "{{ route('pembayaran.kavling.detail', ['id' => Crypt::encrypt($cicilan->pembelian->id)]) }}?payment_status=close";
            }
        });
    });
</script>


@endsection