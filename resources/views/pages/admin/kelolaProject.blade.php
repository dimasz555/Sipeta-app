@extends('layouts.app')


@section('title')
Kelola Project
@endsection

@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Kelola Project</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Kelola Project</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Data Project</h5>
            <x-primary-button class="w-full flex justify-center items-center gap-2" style="width: 200px;" title="Tambah Project" data-bs-toggle="modal" data-bs-target="#addProject">
                <i class="bi bi-plus-lg"></i>
                Tambah Project
            </x-primary-button>
        </div>

        <div class="table-responsive">
            <table id="tableProject" class="table table-striped table-bordered dt-responsive nowrap mx-auto" style="border-collapse: collapse; border-spacing: 0; width: 100%; font-size:14px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Project</th>
                        <th>Lokasi Project</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($project as $pj)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pj->name }}</td>
                        <td>{{ $pj->lokasi }}</td>
                        <td>
                            <x-button-action style="background-color: #BC55C3;" data-bs-toggle="modal" data-bs-target="#editProject"
                                data-id="{{ $pj->id }}" data-name="{{ $pj->name }}" data-lokasi="{{ $pj->lokasi }}"
                                title="Edit Data Project">
                                <i class="bi bi-pencil text-white"></i>
                            </x-button-action>
                            <x-button-action style="background-color: #E33437;" data-bs-toggle="modal" data-bs-target="#hapusProject"
                                data-id="{{ $pj->id }}" data-name="{{ $pj->name }}" data-lokasi="{{ $pj->lokasi }}"
                                title="Hapus Data Project">
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
            <h5 class="card-title mb-0">Data Blok</h5>
            <x-primary-button class="w-full flex justify-center items-center gap-2" style="width: 200px;" title="Tambah Blok" data-bs-toggle="modal" data-bs-target="#addBlok">
                <i class="bi bi-plus-lg"></i>
                Tambah Blok
            </x-primary-button>
        </div>

        <div class="table-responsive">
            <table id="tableBlok" class="table table-striped table-bordered dt-responsive nowrap mx-auto" style="border-collapse: collapse; border-spacing: 0; width: 100%; font-size:14px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Blok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($blok as $bl)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $bl->blok }}</td>
                        <td>
                            <x-button-action style="background-color: #BC55C3;" data-bs-toggle="modal" data-bs-target="#editBlok"
                                data-id="{{ $bl->id }}" data-blok="{{ $bl->blok }}"
                                title="Edit Data Blok">
                                <i class="bi bi-pencil text-white"></i>
                            </x-button-action>
                            <x-button-action style="background-color: #E33437;" data-bs-toggle="modal" data-bs-target="#hapusBlok"
                                data-id="{{ $bl->id }}" data-blok="{{ $bl->blok }}"
                                title="Hapus Data Blok">
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

    <!-- card addProject modal -->
    <div class="modal fade" id="addProject" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h3 class="text-center mb-1" id="addNewCardTitle">Tambah Project</h3>
                    <!-- form -->
                    <form class="form row gy-1 gx-2 mt-75" method="POST" action="{{route('tambah.project')}}">
                        @method('post')
                        @csrf
                        <div class="col-12">
                            <label class="form-label" for="name">Nama</label>
                            <div class="input-group input-group-merge">
                                <input id="name" name="name" class="form-control" type="text" required />
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="name">Lokasi</label>
                            <div class="input-group input-group-merge">
                                <input id="lokasi" name="lokasi" class="form-control" type="text" required />
                            </div>
                        </div>

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-1 mt-1">Tambah</button>
                            <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end card modal -->

    <!-- card editProject modal -->
    <div class="modal fade" id="editProject" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h3 class="text-center" id="addNewCardTitle">Edit Project</h3>
                    <!-- form -->
                    <form class="form row gy-1 gx-2 mt-75" method="POST" action="{{ route('edit.project') }}">
                        @method('put')
                        @csrf
                        <input id="id" name="id" class="form-control" type="text" hidden />
                        <div class="col-12">
                            <label class="form-label" for="name">Nama</label>
                            <div class="input-group input-group-merge">
                                <input id="name" name="name" class="form-control" type="text" required />
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="lokasi">Lokasi</label>
                            <div class="input-group input-group-merge">
                                <input id="lokasi" name="lokasi" class="form-control" type="text" />
                            </div>
                        </div>

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-1 mt-1">Edit</button>
                            <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end card modal -->

    <!-- card hapusProject modal -->
    <div class="modal fade" id="hapusProject" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h3 class="text-center mb-1" id="addNewCardTitle">Hapus Project</h3>
                    <p class="text-center">Kamu yakin ingin menghapus data ini?</p>
                    <!-- form -->
                    <form class="form row gy-1 gx-2 mt-75" method="POST" action="{{ route('hapus.project') }}">
                        @method('delete')
                        @csrf
                        <input type="text" name="id" id="id" hidden>
                        <div class="col-12">
                            <label class="form-label" for="name">Nama</label>
                            <div class="input-group input-group-merge">
                                <input id="name" name="name" class="form-control" type="text" disabled />
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="lokasi">Lokasi</label>
                            <div class="input-group input-group-merge">
                                <input id="lokasi" name="lokasi" class="form-control" type="text" disabled />
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-danger me-1 mt-1">Hapus</button>
                            <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                                Kembali
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end card modal -->

    <!-- card addBlok modal -->
    <div class="modal fade" id="addBlok" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h3 class="text-center mb-1" id="addNewCardTitle">Tambah Blok</h3>
                    <!-- form -->
                    <form class="form row gy-1 gx-2 mt-75" method="POST" action="{{route('tambah.blok')}}">
                        @method('post')
                        @csrf
                        <div class="col-12">
                            <label class="form-label" for="blok">Nama Blok</label>
                            <div class="input-group input-group-merge">
                                <input id="blok" name="blok" class="form-control" type="text" required />
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-1 mt-1">Tambah</button>
                            <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                                Kembali
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end card modal -->

    <!-- card editBlok modal -->
    <div class="modal fade" id="editBlok" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h3 class="text-center mb-1" id="addNewCardTitle">Edit Blok</h3>
                    <!-- form -->
                    <form class="form row gy-1 gx-2 mt-75" method="POST" action="{{ route('edit.blok') }}">
                        @method('put')
                        @csrf
                        <input id="id" name="id" class="form-control" type="text" hidden />
                        <div class="col-12">
                            <label class="form-label" for="blok">Nama Blok</label>
                            <div class="input-group input-group-merge">
                                <input id="blok" name="blok" class="form-control" type="text" required />
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-1 mt-1">Edit</button>
                            <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                                kembali
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end card modal -->

    <!-- card hapusProject modal -->
    <div class="modal fade" id="hapusBlok" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h3 class="text-center mb-1" id="addNewCardTitle">Hapus Blok</h3>
                    <p class="text-center">Kamu yakin ingin menghapus data ini?</p>
                    <!-- form -->
                    <form class="form row gy-1 gx-2 mt-75" method="POST" action="{{ route('hapus.blok') }}">
                        @method('delete')
                        @csrf
                        <input type="text" name="id" id="id" hidden>
                        <div class="col-12">
                            <label class="form-label" for="blok">Blok</label>
                            <div class="input-group input-group-merge">
                                <input id="blok" name="blok" class="form-control" type="text" disabled />
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-danger me-1 mt-1">Hapus</button>
                            <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                                Kembali
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
        $('#tableProject').DataTable({
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

<script>
    $(document).ready(function() {
        $('#tableBlok').DataTable({
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

<script>
    $(document).ready(function() {
        $('#editProject').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var lokasi = button.data('lokasi');

            var modal = $(this);
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #name').val(name);
            modal.find('.modal-body #lokasi').val(lokasi);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#hapusProject').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var name = button.data('name')
            var lokasi = button.data('lokasi')

            var modal = $(this)
            modal.find('.modal-body #id').val(id)
            modal.find('.modal-body #name').val(name)
            modal.find('.modal-body #lokasi').val(lokasi)

        })
    });
</script>

<script>
    $(document).ready(function() {
        $('#editBlok').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var blok = button.data('blok');

            var modal = $(this);
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #blok').val(blok);

        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#hapusBlok').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var blok = button.data('blok')

            var modal = $(this)
            modal.find('.modal-body #id').val(id)
            modal.find('.modal-body #blok').val(blok)

        })
    });
</script>


@endsection