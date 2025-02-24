@extends('layouts.app')


@section('title')
    Kelola Pengeluaran
@endsection

@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Kelola Pengeluaran</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Kelola Pengeluaran</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Data Jenis Pengeluaran</h5>
                <x-primary-button class="w-full flex justify-center items-center gap-2" style="width: 200px;"
                    title="Tambah Project" data-bs-toggle="modal" data-bs-target="#addJenis">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Jenis
                </x-primary-button>
            </div>

            <div class="table-responsive">
                <table id="tableJenisPengeluaran" class="table table-striped table-bordered dt-responsive nowrap mx-auto"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%; font-size:14px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Pengeluaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jenis_pengeluaran as $jp)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $jp->name }}</td>
                                <td>
                                    <x-button-action style="background-color: #BC55C3;" data-bs-toggle="modal"
                                        data-bs-target="#editJenis" data-id="{{ $jp->id }}"
                                        data-name="{{ $jp->name }}" title="Edit Jenis Pengeluaran">
                                        <i class="bi bi-pencil text-white"></i>
                                    </x-button-action>
                                    <x-button-action style="background-color: #E33437;" data-bs-toggle="modal"
                                        data-bs-target="#hapusJenis" data-id="{{ $jp->id }}"
                                        data-name="{{ $jp->name }}" data-lokasi="{{ $jp->lokasi }}"
                                        title="Hapus Data Jenis">
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
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Data Pengeluaran</h5>
                <x-primary-button class="w-full flex justify-center items-center gap-2" style="width: 200px;"
                    title="Tambah Blok" data-bs-toggle="modal" data-bs-target="#addPengeluaran">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Pengeluaran
                </x-primary-button>
            </div>

            <div class="table-responsive">
                <table id="tablePengeluaran" class="table table-striped table-bordered dt-responsive nowrap mx-auto"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%; font-size:14px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Pengeluaran</th>
                            <th>Jenis Pengeluaran</th>
                            <th>Deskripsi</th>
                            <th>Kode</th>
                            <th>Metode Pembayaran</th>
                            <th>Jumlah</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengeluaran as $pl)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($pl->tgl_pengeluaran)->translatedFormat('j F Y') }}</td>
                                <td>{{ $pl->jenisPengeluaran->name }}</td>
                                <td>{{ $pl->deskripsi }}</td>
                                <td>{{ $pl->kode }}</td>
                                <td>
                                    @if ($pl->metode_pembayaran == 'tunai')
                                        Tunai
                                    @elseif($pl->metode_pembayaran == 'transfer')
                                        Transfer
                                    @else
                                        Tidak Diketahui
                                    @endif
                                </td>
                                <td>{{ "Rp ". number_format($pl->jumlah, 0, ',', '.') }}</td>
                                <td>
                                    @if ($pl->kategori == 'operasional')
                                        Pengeluaran Operasional
                                    @elseif($pl->kategori == 'kas')
                                        Pengeluaran Kas Lainnya
                                    @else
                                        Tidak Diketahui
                                    @endif
                                </td>
                                <td>
                                    <x-button-action style="background-color: #BC55C3;" data-bs-toggle="modal"
                                        data-bs-target="#editPengeluaran" data-id="{{ $pl->id }}"
                                        data-tgl_pengeluaran="{{ $pl->tgl_pengeluaran }}"
                                        data-jenis_pengeluaran-id="{{ $pl->jenisPengeluaran->id }}"
                                        data-jenis_pengeluaran-name="{{ $pl->jenisPengeluaran->name }}"
                                        data-deskripsi="{{ $pl->deskripsi }}" data-kode="{{ $pl->kode }}"
                                        data-metode_pembayaran="{{ $pl->metode_pembayaran }}"
                                        data-jumlah="{{ $pl->jumlah }}" data-kategori="{{ $pl->kategori }}"
                                        title="Edit Data Pengeluaran">
                                        <i class="bi bi-pencil text-white"></i>
                                    </x-button-action>
                                    <x-button-action style="background-color: #E33437;" data-bs-toggle="modal"
                                        data-bs-target="#hapusPengeluaran" data-id="{{ $pl->id }}"
                                        data-tgl_pengeluaran="{{ $pl->tgl_pengeluaran }}"
                                        data-jenis_pengeluaran-id="{{ $pl->jenisPengeluaran->id }}"
                                        data-jenis_pengeluaran-name="{{ $pl->jenisPengeluaran->name }}"
                                        data-deskripsi="{{ $pl->deskripsi }}" data-kode="{{ $pl->kode }}"
                                        data-metode_pembayaran="{{ $pl->metode_pembayaran }}"
                                        data-jumlah="{{ $pl->jumlah }}" data-kategori="{{ $pl->kategori }}"
                                        title="Hapus Data Pengeluaran">
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

        <!-- card addJenis modal -->
        <div class="modal fade" id="addJenis" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-sm-5 mx-50 pb-5">
                        <h3 class="text-center mb-4" id="addNewCardTitle">Tambah Jenis Pengeluaran</h3>
                        <!-- form -->
                        <form class="form row gy-1 gx-2 mt-75" method="POST" action="{{ route('tambah.jenis') }}">
                            @method('post')
                            @csrf
                            <div class="col-12 mb-3">
                                <label class="form-label" for="name">Jenis Pengeluaran</label>
                                <div class="input-group input-group-merge">
                                    <input id="name" name="name" class="form-control" type="text" required />
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary me-1 mt-1">Tambah</button>
                                <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end card modal -->

        <!-- card editJenis modal -->
        <div class="modal fade" id="editJenis" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-sm-5 mx-50 pb-5">
                        <h3 class="text-center mb-4" id="addNewCardTitle">Edit Jenis Pengeluaran</h3>
                        <!-- form -->
                        <form class="form row gy-1 gx-2 mt-75" method="POST" action="{{ route('update.jenis') }}">
                            @method('put')
                            @csrf
                            <input id="id" name="id" class="form-control" type="text" hidden />
                            <div class="col-12 mb-3">
                                <label class="form-label" for="name">Jenis Pengeluaran</label>
                                <div class="input-group input-group-merge">
                                    <input id="editJenisName" name="name" class="form-control" type="text"
                                        required />
                                </div>
                            </div>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary me-1 mt-1">Edit</button>
                                <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end card modal -->

        <!-- card hapusJenis modal -->
        <div class="modal fade" id="hapusJenis" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-sm-5 mx-50 pb-5">
                        <h3 class="text-center mb-1" id="addNewCardTitle">Hapus Jenis pengeluaran</h3>
                        <p class="text-center mb-4">Kamu yakin ingin menghapus data ini?</p>
                        <!-- form -->
                        <form class="form row gy-1 gx-2 mt-75" method="POST" action="{{ route('hapus.jenis') }}">
                            @method('delete')
                            @csrf
                            <input type="text" name="id" id="id" hidden>
                            <div class="col-12 mb-3">
                                <label class="form-label" for="name">Nama</label>
                                <div class="input-group input-group-merge">
                                    <input id="hapusJenisName" name="name" class="form-control" type="text"
                                        disabled />
                                </div>
                            </div>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-danger me-1 mt-1">Hapus</button>
                                <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end card modal -->

        <!-- card addPengeluaran modal -->
        <div class="modal fade" id="addPengeluaran" tabindex="-1" aria-labelledby="addNewCardTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-sm-5 mx-50 pb-5">
                        <h3 class="text-center mb-1" id="addNewCardTitle">Tambah Pengeluaran</h3>
                        <!-- form -->
                        <form class="form row gy-1 gx-2 mt-75" method="POST"
                            action="{{ route('tambah.pengeluaran') }}">
                            @method('post')
                            @csrf
                            <div class="col-12">
                                <label class="form-label" for="jenis_pengeluaran">Jenis Pengeluaran</label>
                                <div class="input-group input-group-merge">
                                    <select id="add_jenis_pengeluaran" name="jenis_pengeluaran_id" class="form-select"
                                        required>
                                        <option value="" disabled selected>Pilih Jenis Pengeluaran</option>
                                        @foreach ($jenis_pengeluaran as $jp)
                                            <option value="{{ $jp->id }}">
                                                {{ $jp->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="deskripsi">Deskripsi</label>
                                <div class="input-group input-group-merge">
                                    <input id="add_deskripsi" name="deskripsi" class="form-control" type="text"
                                        required />
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="tgl_pengeluaran">Tanggal Pengeluaran</label>
                                <div class="input-group input-group-merge">
                                    <input id="add_tgl_pengeluaran" name="tgl_pengeluaran" class="form-control"
                                        type="datetime-local" required />
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="kode">Kode</label>
                                <div class="input-group input-group-merge">
                                    <input id="add_kode" name="kode" class="form-control" type="text" />
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="metode_pembayaran">Metode Pembayaran</label>
                                <div class="input-group input-group-merge">
                                    <select id="add_metode" name="metode_pembayaran" class="form-select" required>
                                        <option value="" disabled selected>Pilih Metode Pembayaran</option>
                                        <option value="tunai">Tunai</option>
                                        <option value="transfer">Transfer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="jumlah">Jumlah Pengeluaran</label>
                                <div class="input-group input-group-merge">
                                    <input id="add_jumlah" name="jumlah" class="form-control" type="text" required
                                        oninput="formatRupiah(this)" />
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="kategori">Kategori</label>
                                <div class="input-group input-group-merge">
                                    <select id="add_kategori" name="kategori" class="form-select" required>
                                        <option value="" disabled selected>Pilih Kategori Pengeluaran</option>
                                        <option value="operasional">Pengeluaran Operasional</option>
                                        <option value="kas">Pengeluaran Kas Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary me-1 mt-1">Tambah</button>
                                <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end card modal -->

        <!-- card editPengeluaran modal -->
        <div class="modal fade" id="editPengeluaran" tabindex="-1" aria-labelledby="addNewCardTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-sm-5 mx-50 pb-5">
                        <h3 class="text-center mb-1" id="addNewCardTitle">Edit Pengeluaran</h3>
                        <!-- form -->
                        <form class="form row gy-1 gx-2 mt-75" method="POST"
                            action="{{ route('update.pengeluaran') }}">
                            @method('put')
                            @csrf
                            <input id="editId" name="id" class="form-control" type="hidden" />
                            <div class="col-12">
                                <label class="form-label" for="jenis_pengeluaran">Jenis Pengeluaran</label>
                                <div class="input-group input-group-merge">
                                    <select id="edit_jenis_pengeluaran" name="jenis_pengeluaran_id" class="form-select"
                                        required>
                                        <option value="" disabled>Pilih Jenis Pengeluaran</option>
                                        @foreach ($jenis_pengeluaran as $jp)
                                            <option value="{{ $jp->id }}">{{ $jp->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="deskripsi">Deskripsi</label>
                                <div class="input-group input-group-merge">
                                    <input id="edit_deskripsi" name="deskripsi" class="form-control" type="text"
                                        required />
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="tgl_pengeluaran">Tanggal Pengeluaran</label>
                                <div class="input-group input-group-merge">
                                    <input id="edit_tgl_pengeluaran" name="tgl_pengeluaran" class="form-control"
                                        type="datetime-local" required />
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="kode">Kode</label>
                                <div class="input-group input-group-merge">
                                    <input id="edit_kode" name="kode" class="form-control" type="text" />
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="metode_pembayaran">Metode Pembayaran</label>
                                <div class="input-group input-group-merge">
                                    <select id="edit_metode" name="metode_pembayaran" class="form-select" required>
                                        <option value="" disabled>Pilih Metode Pembayaran</option>
                                        <option value="tunai">Tunai</option>
                                        <option value="transfer">Transfer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="jumlah">Jumlah Pengeluaran</label>
                                <div class="input-group input-group-merge">
                                    <input id="edit_jumlah" name="jumlah" class="form-control" type="text" required
                                        oninput="formatRupiah(this)" />
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="kategori">Kategori</label>
                                <div class="input-group input-group-merge">
                                    <select id="edit_kategori" name="kategori" class="form-select" required>
                                        <option value="" disabled>Pilih Kategori Pengeluaran</option>
                                        <option value="operasional">Pengeluaran Operasional</option>
                                        <option value="kas">Pengeluaran Kas Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary me-1 mt-1">Simpan</button>
                                <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end card modal -->

        <!-- card hapusPengeluaran modal -->
        <div class="modal fade" id="hapusPengeluaran" tabindex="-1" aria-labelledby="addNewCardTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-sm-5 mx-50 pb-5">
                        <h3 class="text-center mb-1" id="addNewCardTitle">Hapus Pengeluaran</h3>
                        <p class="text-center">Kamu yakin ingin menghapus data ini?</p>
                        <!-- form -->
                        <form class="form row gy-1 gx-2 mt-75" method="POST" action="{{ route('hapus.pengeluaran') }}">
                            @method('delete')
                            @csrf
                            <input type="hidden" name="id" id="hapusId">
                            <div class="col-12">
                                <label class="form-label" for="hapus_jenis">Jenis Pengeluaran</label>
                                <div class="input-group input-group-merge">
                                    <input id="hapus_jenis" class="form-control" type="text" disabled />
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="hapus_deskripsi">Deskripsi</label>
                                <div class="input-group input-group-merge">
                                    <input id="hapus_deskripsi" class="form-control" type="text" disabled />
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="hapus_tgl">Tanggal Pengeluaran</label>
                                <div class="input-group input-group-merge">
                                    <input id="hapus_tgl" class="form-control" type="text" disabled />
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-danger me-1 mt-1">Hapus</button>
                                <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end card modal -->



    </main><!-- End #main -->


    <!-- datatable js -->
    <script src="{{ asset('plugin/jQuery-3.7.0/jquery-3.7.0.js') }}"></script>
    <script src="{{ asset('plugin/DataTables-1.13.8/js/jquery.dataTables.min.js') }}"></script>

    <script src="{{ asset('plugin/pdfmake-0.2.7/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugin/pdfmake-0.2.7/vfs_fonts.js') }}"></script>

    <script src="{{ asset('plugin/JSZip-3.10.1/jszip.min.js') }}"></script>


    <script src="{{ asset('plugin/Buttons-2.4.2/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugin/Buttons-2.4.2/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugin/Buttons-2.4.2/js/buttons.print.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#tableJenisPengeluaran').DataTable({
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

            $('#tablePengeluaran').DataTable({
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

            $('#editJenis').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var name = button.data('name');

                var modal = $(this);
                modal.find('.modal-body #id').val(id);
                modal.find('.modal-body #editJenisName').val(name);
            });

            $('#hapusJenis').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget)
                var id = button.data('id')
                var name = button.data('name')

                var modal = $(this)
                modal.find('.modal-body #id').val(id)
                modal.find('.modal-body #hapusJenisName').val(name)
            })

            // Edit Pengeluaran Modal
            $('#editPengeluaran').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var jenis_id = button.data('jenis_pengeluaran_id');
                var deskripsi = button.data('deskripsi');
                var tgl = button.data('tgl_pengeluaran');
                var kode = button.data('kode');
                var metode = button.data('metode_pembayaran');
                var jumlah = button.data('jumlah');
                var kategori = button.data('kategori');

                var modal = $(this);
                modal.find('.modal-body #editId').val(id);
                modal.find('.modal-body #edit_jenis_pengeluaran').val(button.data('jenis_pengeluaran-id')).trigger('change');
                modal.find('.modal-body #edit_deskripsi').val(deskripsi);
                modal.find('.modal-body #edit_tgl_pengeluaran').val(tgl);
                modal.find('.modal-body #edit_kode').val(kode);
                modal.find('.modal-body #edit_metode').val(metode);
                modal.find('.modal-body #edit_jumlah').val(jumlah);
                modal.find('.modal-body #edit_kategori').val(kategori);
            });

            // Hapus Pengeluaran Modal
            $('#hapusPengeluaran').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var jenis = button.data('jenis_pengeluaran-name');
                var deskripsi = button.data('deskripsi');
                var tgl = button.data('tgl_pengeluaran');

                var modal = $(this);
                modal.find('.modal-body #hapusId').val(id);
                modal.find('.modal-body #hapus_jenis').val(jenis);
                modal.find('.modal-body #hapus_deskripsi').val(deskripsi);
                modal.find('.modal-body #hapus_tgl').val(tgl);
            });


        });

        function formatRupiah(input) {
            // Menghapus karakter selain angka
            let value = input.value.replace(/[^\d]/g, '');
            // Menambahkan titik setiap 3 digit
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = value;
        }
    </script>
@endsection
