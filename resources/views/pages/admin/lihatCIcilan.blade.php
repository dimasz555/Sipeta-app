@extends('layouts.app')


@section('title')
    Daftar Cicilan Lunas
@endsection

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Daftar Cicilan Lunas</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Kelola Pembelian</li>
                    <li class="breadcrumb-item active">Daftar Cicilan Lunas</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Data Cicilan</h5>
            </div>

            <div class="table-responsive">
                <table id="tableCicilan" class="table table-striped table-bordered dt-responsive nowrap mx-auto"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%; font-size:14px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tanggal Pembayaran</th>
                            <th>Project-Blok</th>
                            <th>Nomor Cicilan</th>
                            <th>Jumlah Pembayaran</th>
                            <th>Metode Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cicilanLunas as $cl)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $cl['nama_user'] }}</td>
                                <td>{{ $cl['tanggal_bayar'] ? \Carbon\Carbon::parse($cl['tanggal_bayar'])->translatedFormat('j F Y') : '-' }}
                                </td>
                                <td>{{ $cl['nama_project_blok'] }}</td>
                                <td>{{ 'Cicilan ke- ' . $cl['no_cicilan'] }}</td>
                                <td>{{ 'Rp ' . number_format($cl['jumlah_uang'], 0, ',', '.') }}</td>
                                <td>
                                    @if (strtoupper($cl['metode_pembayaran']) === 'BCA')
                                        <x-badge-status class="bg-blue-500">BCA</x-badge-status>
                                    @elseif (strtoupper($cl['metode_pembayaran']) === 'BSI')
                                        <x-badge-status class="bg-orange-500">BSI</x-badge-status>
                                    @elseif (strtoupper($cl['metode_pembayaran']) === 'CASH')
                                        <x-badge-status class="bg-success">CASH</x-badge-status>
                                    @elseif (strtoupper($cl['metode_pembayaran']) === 'MIDTRANS')
                                        <x-badge-status class="bg-secondary">MIDTRANS</x-badge-status>
                                    @else
                                        <x-badge-status
                                            class="bg-danger">{{ strtoupper('Tidak Diketahui') }}</x-badge-status>
                                    @endif
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- jQuery -->
    <script src="{{ asset('plugin/jQuery-3.7.0/jquery-3.7.0.js') }}"></script>

    <!-- DataTables JS -->
    <script src="{{ asset('plugin/DataTables-1.13.8/js/jquery.dataTables.min.js') }}"></script>

    <!-- Other JS -->
    <script src="{{ asset('plugin/pdfmake-0.2.7/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugin/pdfmake-0.2.7/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugin/JSZip-3.10.1/jszip.min.js') }}"></script>
    <script src="{{ asset('plugin/Buttons-2.4.2/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugin/Buttons-2.4.2/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugin/Buttons-2.4.2/js/buttons.print.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#tableCicilan').DataTable({
                responsive: true,
                info: true,
                "language": {
                    "paginate": {
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    },
                    "search": "Pencarian :",
                    "emptyTable": "Tidak ada data",
                    "zeroRecords": "Tidak ada data",
                    "lengthMenu": "Menampilkan _MENU_ data per halaman",
                }
            });
        })
    </script>
@endsection
