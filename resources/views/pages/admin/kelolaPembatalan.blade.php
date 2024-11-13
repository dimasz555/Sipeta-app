@extends('layouts.app')

@section('title')
Pembayaran Kavling
@endsection

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Pembatalan Pembelian</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Kelola Pembayaran</li>
                <li class="breadcrumb-item active">Pembatalan Pembelian</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
</main>

@endsection