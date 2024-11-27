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

    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Data Pembatalan Pembelian</h5>
        </div>

        <div class="table-responsive">
            <table id="tablePembatalan" class="table table-striped table-bordered dt-responsive nowrap mx-auto" style="border-collapse: collapse; border-spacing: 0; width: 100%; font-size:14px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pembelian</th>
                        <th>Tanggal Pembatalan</th>
                        <th>Nama</th>
                        <th>Project</th>
                        <th>Blok</th>
                        <th>Harga Tanah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembatalan as $pb)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($pb->pembelian->tgl_pembelian)->translatedFormat('j F Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($pb->tgl_pembatalan)->translatedFormat('j F Y') }}</td>
                        <td>{{ $pb->pembelian->user->name }}</td>
                        <td>{{ $pb->pembelian->boking->project->name ?? 'Tidak Diketahui' }}</td>
                        <td>{{ $pb->pembelian->boking->no_blok ?? 'Tidak Diketahui' }}</td>
                        <td>{{ "Rp ". number_format($pb->pembelian->harga, 0, ',', '.') }}</td>
                        <td>
                            @if ($pb->pembelian->status === 'proses')
                            <x-badge-status class="bg-warning">PROSES</x-badge-status>
                            @elseif ($pb->pembelian->status === 'selesai')
                            <x-badge-status class="bg-success">SELESAI</x-badge-status>
                            @elseif ($pb->pembelian->status === 'batal')
                            <x-badge-status class="bg-danger">BATAL</x-badge-status>
                            @else
                            <x-badge-status class="bg-secondary">Tidak Diketahui</x-badge-status>
                            @endif
                        </td>
                        <td>
                            <x-button-action style="background-color: #BC55C3;"
                                title="Detail Pembatalan">
                                <a href="{{ route('pembelian.detail', $pb->encrypted_id) }}" class="text-white"><i class="bi bi-eye"></i></a>
                            </x-button-action>
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
        $('#tablePembatalan').DataTable({
            responsive: true,
            info: false,
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
    });
</script>

@endsection