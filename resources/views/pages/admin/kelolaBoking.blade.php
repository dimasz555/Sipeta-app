@extends('layouts.app')

@section('title')
Kelola Boking
@endsection

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Kelola Boking</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Kelola Boking</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Data Boking</h5>
            <x-primary-button class="w-full flex justify-center items-center gap-2" style="width: 200px;" title="Tambah Boking" data-bs-toggle="modal" data-bs-target="#addBoking">
                <i class="bi bi-plus-lg"></i>
                Tambah Boking
            </x-primary-button>
        </div>

        <div class="table-responsive">
            <table id="tableBoking" class="table table-striped table-bordered dt-responsive nowrap mx-auto" style="border-collapse: collapse; border-spacing: 0; width: 100%; font-size:14px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Boking</th>
                        <th>Nama</th>
                        <th>Project</th>
                        <th>Blok</th>
                        <th>Nomor Blok</th>
                        <th>Harga Boking</th>
                        <th>Tanggal Lunas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($boking as $bk)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($bk->tgl_boking)->translatedFormat('j F Y') }}</td>
                        <td>{{ $bk->user->name }}</td>
                        <td>{{ $bk->project->name }}</td>
                        <td>{{ $bk->blok->blok }}</td>
                        <td>{{ $bk->no_blok }}</td>
                        <td>{{ "Rp ". number_format($bk->harga_boking, 0, ',', '.') }}</td>
                        <td> @if($bk->tgl_lunas)
                            {{ \Carbon\Carbon::parse($bk->tgl_lunas)->translatedFormat('j F Y') }}
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @if ($bk->status === 'proses')
                            <x-badge-status class="bg-warning">PROSES</x-badge-status>
                            @elseif ($bk->status === 'lunas')
                            <x-badge-status class="bg-success">LUNAS</x-badge-status>
                            @elseif ($bk->status === 'batal')
                            <x-badge-status class="bg-danger">BATAL</x-badge-status>
                            @else
                            <x-badge-status class="bg-secondary">Tidak Diketahui</x-badge-status>
                            @endif
                        </td>
                        <td>
                            <x-button-action style="background-color: #BC55C3;" data-bs-toggle="modal" data-bs-target="#editBoking"
                                data-id="{{ $bk->id }}"
                                data-user-id="{{ $bk->user->id }}"
                                data-user-name="{{ $bk->user->name }}"
                                data-project-id="{{ $bk->project->id }}"
                                data-blok-id="{{ $bk->blok->id }}"
                                data-no_blok="{{ $bk->no_blok }}"
                                data-tgl_boking="{{ $bk->tgl_boking }}"
                                data-harga_boking="{{ $bk->harga_boking }}" title="Edit Data Boking">
                                <i class="bi bi-pencil text-white"></i>
                            </x-button-action>
                            @if ($bk->status !== 'lunas' && $bk->status !== 'batal') <!-- Check if status is not 'lunas' -->
                            <x-button-action style="background-color: #28a745;" data-bs-toggle="modal" data-bs-target="#confirmLunasModal"
                                data-id="{{ $bk->id }}"
                                data-user-id="{{ $bk->user->id }}"
                                data-user-name="{{ $bk->user->name }}"
                                data-project-name="{{ $bk->project->name }}"
                                data-blok-name="{{ $bk->blok->blok }}"
                                data-blok-number="{{ $bk->no_blok }}"
                                data-harga-boking="{{ number_format($bk->harga_boking, 0, ',', '.') }}"
                                title="Konfirmasi Lunas">
                                <i class="bi bi-check text-white"></i>
                            </x-button-action>
                            @endif
                            @if ($bk->status !== 'batal') <!-- Check if status is not 'lunas' -->
                            <x-button-action style="background-color: #E33437;" data-bs-toggle="modal" data-bs-target="#cancelBoking"
                                data-id="{{ $bk->id }}" data-user-name="{{ $bk->user->name }}"
                                data-project-name="{{ $bk->project->name }}" data-blok-name="{{ $bk->blok->blok }}"
                                data-blok-number="{{ $bk->no_blok }}"
                                data-harga-boking="{{ number_format($bk->harga_boking, 0, ',', '.') }}"
                                title="Batal Boking">
                                <i class="bi bi-x text-white"></i>
                            </x-button-action>
                            @endif
                            <x-button-action style="background-color: #E33437;" data-bs-toggle="modal" data-bs-target="#hapusBoking"
                                data-id="{{ $bk->id }}" data-user-name="{{ $bk->user->name }}"
                                data-project-name="{{ $bk->project->name }}" data-blok-name="{{ $bk->blok->blok }}"
                                data-blok-number="{{ $bk->no_blok }}"
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

    <!-- add modal -->
    <div class="modal fade" id="addBoking" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h3 class="text-center mb-1" id="addNewCardTitle">Tambah Boking</h3>
                    <!-- form -->
                    <form class="form row gy-1 gx-2 mt-75" method="POST" action="{{ route('tambah.boking') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="user_id">Nama Konsumen</label>
                                <select id="user_id" name="user_id" class="form-control select2" required>
                                    <!-- Options akan di-load oleh Select2 -->
                                </select>
                            </div>

                            <div class="col-12 mb-2">
                                <label class="form-label" for="project_id">Project</label>
                                <select id="project_id" name="project_id" class="form-select" required>
                                    <option value="">Pilih Project</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 mb-2">
                                <label class="form-label" for="blok_id">Blok</label>
                                <select id="blok_id" name="blok_id" class="form-select" required>
                                    <option value="">Pilih Blok</option>
                                    @foreach($bloks as $blok)
                                    <option value="{{ $blok->id }}">{{ $blok->blok }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 mb-2">
                                <label class="form-label" for="no_blok">Nomor Blok</label>
                                <input id="no_blok" name="no_blok" class="form-control" type="text" required />
                            </div>

                            <div class="col-12 mb-2">
                                <label class="form-label" for="tgl_boking">Tanggal Boking</label>
                                <input id="tgl_boking" name="tgl_boking" class="form-control" type="datetime-local" required />
                            </div>

                            <div class="col-12 mb-2">
                                <label class="form-label" for="harga_boking">Harga Boking</label>
                                <input id="harga_boking" name="harga_boking" class="form-control" type="number" required />
                            </div>
                        </div>

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">Tambah</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kembali</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end card modal -->

    <!--edit modal -->
    <div class="modal fade" id="editBoking" tabindex="-1" aria-labelledby="editBokingTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h3 class="text-center mb-1" id="editBokingTitle">Edit Boking</h3>
                    <!-- form -->
                    <form class="form row gy-1 gx-2 mt-75" method="POST" action="{{ route('edit.boking') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="edit_boking_id">

                        <div class="modal-body ">
                            <div class="col-12">
                                <label class="form-label" for="edit_user_id">Nama Konsumen</label>
                                <select id="edit_user_id" name="user_id" class="form-select select2" required>
                                    <!-- Options will be loaded dynamically -->
                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="form-label" for="edit_project_id">Project</label>
                                <select id="edit_project_id" name="project_id" class="form-select" required>
                                    <option value="">Pilih Project</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 mb-2">
                                <label class="form-label" for="edit_blok_id">Blok</label>
                                <select id="edit_blok_id" name="blok_id" class="form-select" required>
                                    <option value="">Pilih Blok</option>
                                    @foreach($bloks as $blok)
                                    <option value="{{ $blok->id }}">{{ $blok->blok }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 mb-2">
                                <label class="form-label" for="edit_no_blok">Nomor Blok</label>
                                <input id="edit_no_blok" name="no_blok" class="form-control" type="text" required />
                            </div>

                            <div class="col-12 mb-2">
                                <label class="form-label" for="edit_tgl_boking">Tanggal Boking</label>
                                <input id="edit_tgl_boking" name="tgl_boking" class="form-control" type="datetime-local" required />
                            </div>

                            <div class="col-12 mb-2">
                                <label class="form-label" for="edit_harga_boking">Harga Boking</label>
                                <input id="edit_harga_boking" name="harga_boking" class="form-control" type="number" required />
                            </div>
                        </div>

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">Edit</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--confirm modal -->
    <div class="modal fade" id="confirmLunasModal" tabindex="-1" aria-labelledby="confirmLunasTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h3 class="text-center mb-1" id="editBokingTitle">Konfirmasi Boking</h3>
                    <p>Apakah Yakin Ingin Melakukan Konfirmasi Boking?</p>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td><strong>Nama Pengguna</strong></td>
                                <td><span id="confirm_user_name"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Project</strong></td>
                                <td><span id="confirm_project_name"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Blok</strong></td>
                                <td><span id="confirm_blok_name"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Nomor Blok</strong></td>
                                <td><span id="confirm_blok_number"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Harga Boking</strong></td>
                                <td><span id="confirm_harga_boking"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer justify-content-center">
                    <form id="confirmLunasForm" method="POST" action="{{ route('confirm.boking') }}">
                        @csrf
                        <input type="hidden" name="id" id="confirm_boking_id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Konfirmasi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--cancel modal -->
    <div class="modal fade" id="cancelBoking" tabindex="-1" aria-labelledby="cancelBokingTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h3 class="text-center mb-1" id="editBokingTitle">Pembatalan Boking</h3>
                    <p>Apakah Yakin Ingin Melakukan Pembatalan Boking?</p>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td><strong>Nama Pengguna</strong></td>
                                <td><span id="cancel_user_name"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Project</strong></td>
                                <td><span id="cancel_project_name"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Blok</strong></td>
                                <td><span id="cancel_blok_name"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Nomor Blok</strong></td>
                                <td><span id="cancel_blok_number"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Harga Boking</strong></td>
                                <td><span id="cancel_harga_boking"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer justify-content-center">
                    <form action="{{ route('cancel.boking') }}" method="POST" id="cancelBokingForm">
                        @csrf
                        <input type="hidden" name="id" id="cancel_boking_id">
                        <button type="submit" class="btn btn-danger">Konfirmasi</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--hapus modal -->
    <div class="modal fade" id="hapusBoking" tabindex="-1" aria-labelledby="hapusBokingTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h3 class="text-center mb-1" id="hapusBokingTitle">Hapus Boking</h3>
                    <p>Apakah Yakin Ingin Menghapus Data Boking?</p>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td><strong>Nama Konsumen</strong></td>
                                <td><span id="hapus_user_name"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Project</strong></td>
                                <td><span id="hapus_project_name"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Blok</strong></td>
                                <td><span id="hapus_blok_name"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Nomor Blok</strong></td>
                                <td><span id="hapus_blok_number"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer justify-content-center">
                    <form action="{{ route('hapus.boking') }}" method="POST" id="hapusBokingForm">
                        @method('delete')
                        @csrf
                        <input type="hidden" name="id" id="hapus_boking_id">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    </form>
                </div>
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
        $('#tableBoking').DataTable({
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
        $('#addBoking').on('shown.bs.modal', function() {
            $(this).find('select#user_id').select2({
                dropdownParent: $('#addBoking'),
                placeholder: 'Cari Konsumen...',
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route("search.user") }}',
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
        $('#editBoking').on('shown.bs.modal', function() {
            $(this).find('select#edit_user_id').select2({
                dropdownParent: $('#editBoking'),
                placeholder: 'Cari Konsumen...',
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route("search.user") }}',
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

        $('#editBoking').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var userId = button.data('user-id');
            var userName = button.data('user-name');
            var projectId = button.data('project-id');
            var blokId = button.data('blok-id');
            var no_blok = button.data('no_blok');
            var tgl_boking = button.data('tgl_boking');
            var harga_boking = button.data('harga_boking');

            var modal = $(this);

            // Clear existing selections
            modal.find('#edit_user_id').val(null).trigger('change');

            // Menambahkan opsi pengguna terpilih
            var userOption = new Option(userName, userId, true, true);
            modal.find('#edit_user_id').append(userOption).trigger('change');

            // Set other values
            modal.find('#edit_boking_id').val(button.data('id'));
            modal.find('#edit_no_blok').val(button.data('no_blok'));
            modal.find('#edit_tgl_boking').val(button.data('tgl_boking'));
            modal.find('#edit_harga_boking').val(button.data('harga_boking'));
            modal.find('#edit_project_id').val(button.data('project-id')).trigger('change');
            modal.find('#edit_blok_id').val(button.data('blok-id')).trigger('change');
        });

        $('#confirmLunasModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            // Ambil data dari button
            var bookingId = button.data('id');
            var userName = button.data('user-name');
            var projectName = button.data('project-name');
            var blokName = button.data('blok-name');
            var blokNumber = button.data('blok-number');
            var hargaBoking = button.data('harga-boking');

            var modal = $(this);
            modal.find('#confirm_boking_id').val(bookingId); // Set the ID in the hidden input
            modal.find('#confirm_user_name').text(userName); // Set nama pengguna
            modal.find('#confirm_project_name').text(projectName); // Set nama project
            modal.find('#confirm_blok_name').text(blokName); // Set nama blok
            modal.find('#confirm_blok_number').text(blokNumber); // Set nama blok
            modal.find('#confirm_harga_boking').text("Rp " + hargaBoking); // Set harga boking
        });

        $('#cancelBoking').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            // Ambil data dari button
            var bookingId = button.data('id');
            var userName = button.data('user-name');
            var projectName = button.data('project-name');
            var blokName = button.data('blok-name');
            var blokNumber = button.data('blok-number');
            var hargaBoking = button.data('harga-boking');

            var modal = $(this);
            modal.find('#cancel_boking_id').val(bookingId); // Set the ID in the hidden input
            modal.find('#cancel_user_name').text(userName); // Set nama pengguna
            modal.find('#cancel_project_name').text(projectName); // Set nama project
            modal.find('#cancel_blok_name').text(blokName); // Set nama blok
            modal.find('#cancel_blok_number').text(blokNumber); // Set nama blok
            modal.find('#cancel_harga_boking').text("Rp " + hargaBoking); // Set harga boking
        });

        $('#hapusBoking').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var bookingId = button.data('id'); // Ambil ID
            var userName = button.data('user-name');
            var projectName = button.data('project-name');
            var blokName = button.data('blok-name');
            var blokNumber = button.data('blok-number');

            var modal = $(this);
            modal.find('#hapus_boking_id').val(bookingId); // Set ID di input hidden
            modal.find('#hapus_user_name').text(userName); // Set nama pengguna
            modal.find('#hapus_project_name').text(projectName); // Set nama project
            modal.find('#hapus_blok_name').text(blokName); // Set nama blok
            modal.find('#hapus_blok_number').text(blokNumber); // Set nomor blok
        });


    });
</script>

@endsection