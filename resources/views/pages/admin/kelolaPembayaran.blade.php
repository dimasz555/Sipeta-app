@extends('layouts.app')

@section('title')
Kelola Pembelian
@endsection

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Index Pembelian</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Kelola Pembelian</li>
                <li class="breadcrumb-item active">Index Pembelian</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Data Pembelian</h5>
            <x-primary-button class="w-full flex justify-center items-center" style="width: 200px;" data-bs-toggle="modal" data-bs-target="#addPembelian">Tambah Pembelian</x-primary-button>
        </div>

        <div class="table-responsive">
            <table id="tablePembelian" class="table table-striped table-bordered dt-responsive nowrap mx-auto" style="border-collapse: collapse; border-spacing: 0; width: 100%; font-size:14px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pembelian</th>
                        <th>Nama</th>
                        <th>Project</th>
                        <th>Nomor Blok</th>
                        <th>Harga Tanah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembelian as $bk)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($bk->tgl_pembelian)->translatedFormat('j F Y') }}</td>
                        <td>{{ $bk->user->name }}</td>
                        <td>{{ $bk->boking->project->name ?? 'Tidak Diketahui' }}</td> <!-- Mengambil nama proyek dari boking -->
                        <td>{{ $bk->boking->no_blok }}</td>
                        <td>{{ "Rp ". number_format($bk->harga, 0, ',', '.') }}</td>
                        <td>
                            @if ($bk->status === 'proses')
                            <x-badge-status class="bg-warning">PROSES</x-badge-status>
                            @elseif ($bk->status === 'slesai')
                            <x-badge-status class="bg-success">SELESAI</x-badge-status>
                            @elseif ($bk->status === 'batal')
                            <x-badge-status class="bg-danger">BATAL</x-badge-status>
                            @else
                            <x-badge-status class="bg-secondary">Tidak Diketahui</x-badge-status>
                            @endif
                        </td>
                        <td>
                            <x-button-action style="background-color: #BC55C3;" data-bs-toggle="modal" data-bs-target="#editBoking"
                                data-id="{{ $bk->id }}" data-user-id="{{ $bk->user->id }}" data-user-name="{{ $bk->user->name }}"
                                data-boking-id="{{ $bk->boking->id }}"
                                data-blok-id="{{ $bk->boking->blok->id }}" data-no_blok="{{ $bk->no_blok }}"
                                data-tgl_boking="{{ $bk->tgl_boking }}"
                                data-harga_boking="{{ $bk->harga_boking }}" title="Detail Pembelian">
                                <a href="{{ route('pembelian.detail', $bk->encrypted_id) }}" class="text-white"><i class="bi bi-eye"></i></a>
                            </x-button-action>
                            <x-button-action style="background-color: #BC55C3;" data-bs-toggle="modal" data-bs-target="#editPembelianModal"
                                data-id="{{ $bk->id }}"
                                data-user-id="{{ $bk->user->id }}"
                                data-user-name="{{ $bk->user->name }}"
                                data-boking-id="{{ $bk->boking->id }}"
                                data-harga="{{ $bk->harga }}"
                                data-dp="{{ $bk->dp }}"
                                data-jumlah-bulan-cicilan="{{ $bk->jumlah_bulan_cicilan }}"
                                data-harga-cicilan-perbulan="{{ $bk->harga_cicilan_perbulan }}"
                                title="Edit Data Pembelian">
                                <i class="bi bi-pencil text-white"></i>
                            </x-button-action>
                            <x-button-action style="background-color: #E33437;" data-bs-toggle="modal" data-bs-target="#cancelBoking"
                                data-id="{{ $bk->id }}" data-user-name="{{ $bk->user->name }}"
                                data-boking-name="{{ $bk->boking->no_blok }}"
                                data-harga-boking="{{ number_format($bk->harga_boking, 0, ',', '.') }}"
                                title="Batal Boking">
                                <i class="bi bi-x text-white"></i>
                            </x-button-action>
                            <x-button-action style="background-color: #E33437;" data-bs-toggle="modal" data-bs-target="#hapusBoking"
                                data-id="{{ $bk->id }}" data-user-name="{{ $bk->user->name }}"
                                data-boking-name="{{ $bk->boking->no_blok }}"
                                title="Hapus Data Boking">
                                <i class="bi bi-trash text-white"></i>
                            </x-button-action>
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>


            </table>
        </div>
    </div>

    <!-- Modal Tambah Pembelian -->
    <div class="modal fade" id="addPembelian" tabindex="-1" aria-labelledby="addPembelianLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTambahPembelian" method="POST" action="{{ route('tambah.pembelian') }}">
                    @csrf
                    <div class="modal-body px-sm-5 mx-50 pb-5">
                        <h3 class="text-center mb-1" id="addNewCardTitle">Tambah Pembelian</h3>
                        <div class="col-12 mb-3">
                            <label class="form-label" for="user_id">Nama Konsumen</label>
                            <select id="user_id" name="user_id" class="form-select select2" required>
                                <!-- Options will be loaded dynamically -->
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="boking_id" class="form-label">Boking</label>
                            <select id="boking_id" name="boking_id" class="form-select" required>
                                <option value="">Pilih Boking</option>
                                @foreach ($boking as $boking)
                                <option value="{{ $boking->id }}"> {{ $boking->project->name }} - {{ $boking->no_blok }} - {{ $boking->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="harga" class="form-label">Harga Tanah</label>
                            <input type="number" class="form-control" id="harga" name="harga" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="dp" class="form-label">DP</label>
                            <input type="number" class="form-control" id="dp" name="dp" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="jumlah_bulan_cicilan" class="form-label">Jumlah Bulan Cicilan</label>
                            <input type="number" class="form-control" id="jumlah_bulan_cicilan" name="jumlah_bulan_cicilan" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="harga_cicilan_perbulan" class="form-label">Harga Cicilan per Bulan</label>
                            <input type="number" class="form-control" id="harga_cicilan_perbulan" name="harga_cicilan_perbulan" required>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">Tambah</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kembali</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Pembelian -->
    <div class="modal fade" id="editPembelianModal" tabindex="-1" aria-labelledby="editPembelianModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditPembelian" method="POST" action="{{ route('edit.pembelian') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body px-sm-5 mx-50 pb-5">
                        <h3 class="text-center mb-1" id="addNewCardTitle">Edit Pembelian</h3>
                        <input type="hidden" name="id" id="edit_pembelian_id">
                        <div class="col-12 mb-3">
                            <label for="edit_user_id" class="form-label">Nama Konsumen</label>
                            <select id="edit_user_id" name="user_id" class="form-select select2" required>
                                <!-- Options will be loaded dynamically -->
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="boking_id" class="form-label">Boking</label>
                            <select id="edit_boking_id" name="boking_id" class="form-select" required>
                                <option value="">Pilih Boking</option>
                                @foreach ($pembelian as $pb)
                                <option value="{{ $pb->boking->id }}"> {{ $pb->boking->project->name }} - {{ $pb->boking->no_blok }} - {{ $pb->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="editHarga" class="form-label">Harga Tanah</label>
                            <input type="number" class="form-control" id="editHarga" name="harga" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="editDp" class="form-label">DP</label>
                            <input type="number" class="form-control" id="editDp" name="dp" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="editJumlahBulanCicilan" class="form-label">Jumlah Bulan Cicilan</label>
                            <input type="number" class="form-control" id="editJumlahBulanCicilan" name="jumlah_bulan_cicilan" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="editHargaCicilanPerbulan" class="form-label">Harga Cicilan per Bulan</label>
                            <input type="number" class="form-control" id="editHargaCicilanPerbulan" name="harga_cicilan_perbulan" required>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Edit</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kembali</button>
                        </div>
                    </div>
                </form>
            </div>
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
        $('#tablePembelian').DataTable({
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

        // Initialize Select2 for adding new booking
        $('#addPembelian').on('shown.bs.modal', function() {
            $(this).find('select#user_id').select2({
                dropdownParent: $('#addPembelian'),
                placeholder: 'Cari Konsumen...',
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route("search.user.boking") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results,
                        };
                    },
                    cache: true
                },
                // Custom loading indicator
                language: {
                    inputTooShort: function() {
                        return 'Masukkan minimal 1 karakter';
                    },
                    searching: function() {
                        return 'Memuat...'; // Teks yang ditampilkan saat sedang mencari
                    },
                    noResults: function() {
                        return 'Tidak ada hasil ditemukan';
                    },
                    // Custom message saat tidak ada data
                    errorLoading: function() {
                        return 'Gagal memuat hasil';
                    }
                }
            });
        });

        // Initialize Select2 for editing booking
        $('#editPembelianModal').on('shown.bs.modal', function() {
            $(this).find('select#edit_user_id').select2({
                dropdownParent: $('#editPembelianModal'),
                placeholder: 'Cari Konsumen...',
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route("search.user.boking") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results,
                        };
                    },
                    cache: true
                },
                // Custom loading indicator
                language: {
                    inputTooShort: function() {
                        return 'Masukkan minimal 1 karakter';
                    },
                    searching: function() {
                        return 'Memuat...'; // Teks yang ditampilkan saat sedang mencari
                    },
                    noResults: function() {
                        return 'Tidak ada hasil ditemukan';
                    },
                    // Custom message saat tidak ada data
                    errorLoading: function() {
                        return 'Gagal memuat hasil';
                    }
                }
            });
        });

        // Populate data into the edit modal when the edit button is clicked
        $('#editPembelianModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);

            // Ambil data dari button
            var id = button.data('id');
            var userId = button.data('user-id');
            var userName = button.data('user-name');
            var bokingId = button.data('boking-id');
            var harga = button.data('harga');
            var dp = button.data('dp');
            var jumlahBulanCicilan = button.data('jumlah-bulan-cicilan');
            var hargaCicilanPerbulan = button.data('harga-cicilan-perbulan');

            var modal = $(this);

            // Clear existing selections
            modal.find('#edit_user_id').val(null).trigger('change');

            // Menambahkan opsi pengguna terpilih
            var userOption = new Option(userName, userId, true, true);
            modal.find('#edit_user_id').append(userOption).trigger('change');

            console.log('Pembelian ID:', id);
            console.log('User ID:', userId);
            console.log('Boking ID:', bokingId);
            console.log('Harga:', harga);
            console.log('DP:', dp);
            console.log('Jumlah Bulan Cicilan:', jumlahBulanCicilan);
            console.log('Harga Cicilan Per Bulan:', hargaCicilanPerbulan);


            // Set data di modal
            modal.find('#edit_pembelian_id').val(button.data('id'));
            modal.find('#editUser').val(button.data('userName'));
            modal.find('#edit_boking_id').val(button.data('bokingId')).trigger('change');
            modal.find('#editHarga').val(button.data('harga'));
            modal.find('#editDp').val(button.data('dp'));
            modal.find('#editJumlahBulanCicilan').val(button.data('jumlahBulanCicilan'));
            modal.find('#editHargaCicilanPerbulan').val(button.data('hargaCicilanPerbulan'));
        });

    });
</script>

@endsection