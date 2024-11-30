@extends('layouts.app')


@section('title')
Kelola Konsumen
@endsection

@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Kelola Konsumen</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Kelola Konsumen</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Data Konsumen</h5>
            <x-primary-button class="w-full flex justify-center items-center gap-2" style="width: 200px;" title="Tambah Blok" data-bs-toggle="modal" data-bs-target="#addKonsumen">
                <i class="bi bi-plus-lg"></i>
                Tambah Konsumen
            </x-primary-button>
        </div>

        <div class="table-responsive">
            <table id="tableKonsumen" class="table table-striped table-bordered dt-responsive nowrap mx-auto" style="border-collapse: collapse; border-spacing: 0; width: 100%; font-size:14px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Nomor Hp</th>
                        <th>Jenis Kelamin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($konsumen as $ks)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $ks->name }}</td>
                        <td>{{ $ks->username }}</td>
                        <td>{{ $ks->phone }}</td>
                        <td>
                            @if($ks->gender == 'wanita')
                            Perempuan
                            @elseif($ks->gender == 'pria')
                            Laki-Laki
                            @else
                            Tidak Diketahui
                            @endif
                        </td>
                        <td>

                            <x-button-action style="background-color: #BC55C3;" data-bs-toggle="modal" data-bs-target="#editModal"
                                data-id="{{ $ks->id }}"
                                data-name="{{ $ks->name }}"
                                data-username="{{ $ks->username }}"
                                data-gender="{{ $ks->gender }}"
                                data-phone="{{ $ks->phone }}"
                                title="Edit Data Konsumen">
                                <i class="bi bi-pencil text-white"></i>
                            </x-button-action>
                            <x-button-action style="background-color: #E33437;" data-bs-toggle="modal" data-bs-target="#hapusModal"
                                data-id="{{ $ks->id }}"
                                data-name="{{ $ks->name }}"
                                data-username="{{ $ks->username }}"
                                title="Hapus Data Konsumen">
                                <i class="bi bi-trash text-white"></i>
                            </x-button-action>
                            <x-button-action style="background-color: #BC55C3;" data-bs-toggle="modal"
                                data-bs-target="#resetModal"
                                data-id="{{ $ks->id }}"
                                data-name="{{ $ks->name }}"
                                data-username="{{ $ks->username }}"
                                title="Reset Password">
                                <i class="bi bi-key text-white"></i>
                            </x-button-action>
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>


    <!-- card addKonsumen modal -->
    <div class="modal fade" id="addKonsumen" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h3 class="text-center mb-1" id="addNewCardTitle">Tambah Konsumen</h3>
                    <!-- form -->
                    <form class="form row gy-1 gx-2 mt-75" method="POST" action="{{route('tambah.konsumen')}}">
                        @method('post')
                        @csrf
                        <div class="col-12">
                            <label class="form-label" for="name">Nama</label>
                            <div class="input-group input-group-merge">
                                <input id="name" name="name" class="form-control" type="text" required />
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="username">Username</label>
                            <div class="input-group input-group-merge">
                                <input id="username" name="username" class="form-control" type="text" required />
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="phone">Nomor Telepon</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="number" name="phone" id="phone" required />
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="gender">Jenis Kelamin</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="" disabled selected>Pilih</option>
                                <option value="pria">Laki-laki</option>
                                <option value="wanita">Perempuan</option>
                            </select>
                        </div>

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-1 mt-1">Tambah</button>
                            <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end card modal -->


    <!-- card editKonsumen modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h3 class="text-center" id="addNewCardTitle">Edit Profil Konsumen</h3>
                    <!-- form -->
                    <form class="form row gy-1 gx-2 mt-75" method="POST" action="{{ route('edit.konsumen') }}">
                        @method('put')
                        @csrf
                        <input id="edit_id" name="id" class="form-control" type="text" hidden />

                        <div class="col-12">
                            <label class="form-label" for="name">Nama</label>
                            <div class="input-group input-group-merge">
                                <input id="edit_name" name="name" class="form-control" type="text" />
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="username">Username</label>
                            <div class="input-group input-group-merge">
                                <input id="edit_username" name="username" class="form-control" type="text" />
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="phone">Nomor Hp</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="number" name="phone" id="editphone" />
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="gender">Jenis Kelamin</label>
                            <select class="form-select" id="editgender" name="gender" required>
                                <option value="pria" {{ Auth::user()->gender === 'pria' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="wanita" {{ Auth::user()->gender === 'wanita' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-1 mt-1">Edit</button>
                            <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end card modal -->

    <!-- card hapus konsumen modal -->
    <div class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h3 class="text-center mb-1" id="addNewCardTitle">Hapus Data Konsumen</h3>
                    <p class="text-center">Kamu yakin ingin menghapus data ini?</p>
                    <!-- form -->
                    <form class="form row gy-1 gx-2 mt-75" method="POST" action="{{ route('hapus.konsumen') }}">
                        @method('delete')
                        @csrf
                        <input type="text" name="id" id="delete_id" hidden>
                        <div class="col-12">
                            <label class="form-label" for="name">Nama</label>
                            <div class="input-group input-group-merge">
                                <input id="delete_name" name="name" class="form-control" type="text" disabled />
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="username">Username</label>
                            <div class="input-group input-group-merge">
                                <input id="delete_username" name="username" class="form-control" type="text" disabled />
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-danger me-1 mt-1">Hapus</button>
                            <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end card modal -->

    <!-- card resetPassword modal -->
    <div class="modal fade" id="resetModal" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h3 class="text-center mb-1" id="addNewCardTitle">Reset Password Konsumen</h3>
                    <p class="text-center">Kamu yakin ingin melakukan reset password?</p>
                    <!-- form -->
                    <form class="form row gy-1 gx-2 mt-75" method="POST" action="{{ route('resetpassword.konsumen') }}">
                        @method('put')
                        @csrf
                        <input type="text" name="id" id="id" hidden>
                        <div class="col-12">
                            <label class="form-label" for="name">Nama</label>
                            <div class="input-group input-group-merge">
                                <input id="name" name="name" class="form-control" type="text" disabled />
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="username">Username</label>
                            <div class="input-group input-group-merge">
                                <input id="username" name="username" class="form-control" type="text" disabled />
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-danger me-1 mt-1">Reset</button>
                            <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end card modal -->


</main><!-- End #main -->

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
        $('#tableKonsumen').DataTable({
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            responsive: true,
            info: true,
            language: {
                paginate: {
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                search: "Pencarian :",
                emptyTable: "Tidak ada data",
                zeroRecords: "Tidak ada data",
                lengthMenu: "Menampilkan _MENU_ data per halaman",
            }
        });


        // $('#viewModal').on('show.bs.modal', function(event) {
        //     var button = $(event.relatedTarget);

        //     var id = button.data('id');
        //     var name = button.data('name');
        //     var username = button.data('username');
        //     var phone = button.data('phone');
        //     var gender = button.data('gender');

        //     // Cek nilai gender dan ubah ke label yang sesuai
        //     var genderLabel = (gender === 'pria') ? 'Laki-laki' : (gender === 'wanita' ? 'Perempuan' : '');

        //     var modal = $(this);
        //     modal.find('.modal-body #id').val(id);
        //     modal.find('.modal-body #name').val(name);
        //     modal.find('.modal-body #username').val(username);
        //     modal.find('.modal-body #phone').val(phone);
        //     modal.find('.modal-body #gender').val(genderLabel);
        // });

        $('#editModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);

            var id = button.data('id');
            var name = button.data('name');
            var username = button.data('username');
            var phone = button.data('phone');
            var gender = button.data('gender');

            var modal = $(this);
            modal.find('#edit_id').val(id);
            modal.find('#edit_name').val(name);
            modal.find('#edit_username').val(username);
            modal.find('#editphone').val(phone);
            modal.find('#editgender').val(gender);
        });


        $('#hapusModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var name = button.data('name')
            var username = button.data('username')

            var modal = $(this)
            modal.find('#delete_id').val(id)
            modal.find('#delete_name').val(name)
            modal.find('#delete_username').val(username)

        })

        $('#resetModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)

            var id = button.data('id')
            var name = button.data('name')
            var username = button.data('username')

            var modal = $(this)
            modal.find('.modal-body #id').val(id)
            modal.find('.modal-body #name').val(name)
            modal.find('.modal-body #username').val(username)

        })

        $('#addKonsumen').on('show.bs.modal', function() {
            console.log('addKonsumen modal triggered');
        });

        $('#viewModal').on('show.bs.modal', function() {
            console.log('viewModal modal triggered');
        });

        $('#editModal').on('show.bs.modal', function() {
            console.log('editModal modal triggered');
        });

        $('#hapusModal').on('show.bs.modal', function() {
            console.log('hapusModal modal triggered');
        });

        $('#resetModal').on('show.bs.modal', function() {
            console.log('resetModal modal triggered');
        });

        document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
            button.addEventListener('click', () => {
                const target = button.getAttribute('data-bs-target');
                console.log(`Modal triggered: ${target}`);
                const modal = document.querySelector(target);
                if (modal) {
                    console.log('Modal found:', modal);
                } else {
                    console.error('Modal not found:', target);
                }
            });
        });

    });
</script>

@endsection