<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Pegawai</title>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        crossorigin="anonymous">


    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #ffffff;
            display: block;
        }

        .sidebar a:hover {
            background-color: #575d63;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
        }

        .profile {
            color: #ffffff;
            padding: 15px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .sidebar a {
                float: left;
            }

            .content {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .sidebar a {
                text-align: center;
                float: none;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="profile">
            <h4>Nama Aplikasi</h4>
            <p>admin@example.com</p>
        </div>
        <a href="#menu1">Menu 1</a>
        <a href="#menu2">Menu 2</a>
        <a href="#menu3">Menu 3</a>
        <a href="#menu4">Menu 4</a>
    </div>

    <!-- Modal Tambah Pegawai -->
    <div class="modal fade" id="createModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Pegawai</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createForm" action="{{ url('store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="foto_profile">Foto Profile</label>
                            <input class="form-control" id="foto_profile" name="foto_profile" type="file">
                        </div>
                        <div class="form-group">
                            <label for="jabatan">Jabatan</label>
                            <select class="select2-multiple form-control" name="jabatan[]" multiple="multiple"
                                id="select2Multiple" style="width: 100%" required>
                                @foreach ($jabatan as $j)
                                    <option value="{{ $j->nama }}">{{ $j->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kontrak">Kontrak</label>
                            <input type="text" name="daterange" class="form-control"
                                value="{{ \Carbon\Carbon::now()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->addDays(7)->format('m/d/Y') }}"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="gaji">Gaji</label>
                            <input type="number" class="form-control" id="gaji" name="gaji" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END Modal Tambah Pegawai -->

    <div class="content">
        <div class="container mt-5">
            <h2 class="text-center">Monitoring Pegawai</h2>
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createModal">Tambah
                Pegawai</button>
            <table id="myTable" class="display table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Foto Profile</th>
                        <th>Jabatan</th>
                        <th>Tanggal Dipekerjakan</th>
                        <th>Tanggal Berhenti</th>
                        <th>Gaji</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="pegawaiTable">
                    @foreach ($pegawai as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>{{ $p->nama }}</td>
                            <td><img src="{{ asset('images/' . $p->foto_profile) }}" alt="{{ $p->foto_profile }}"
                                    width="50"></td>
                            <td>{{ implode(', ', $p->jabatan) }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_dipekerjakan)->translatedFormat('d F Y') }}
                            </td>
                            <td>{{ $p->tanggal_berhenti ? \Carbon\Carbon::parse($p->tanggal_berhenti)->translatedFormat('d F Y') : '-' }}
                            </td>
                            <td>{{ 'Rp ' . number_format($p->gaji, 0, ',', '.') }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-toggle="modal"
                                    data-target="#editModal{{ $p->id }}">Edit</button>
                                <button class="btn btn-danger btn-sm"
                                    onclick="deletePegawai({{ $p->id }})">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus pegawai ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Pegawai -->
    @foreach ($pegawai as $p)
        <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editModalLabel{{ $p->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{ $p->id }}">Edit Pegawai</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm{{ $p->id }}" action="{{ url('update', $p->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="editId{{ $p->id }}" name="nama"
                                value="{{ $p->id }}">
                            <div class="form-group">
                                <label for="editNama{{ $p->id }}">Nama</label>
                                <input type="text" class="form-control" id="editNama{{ $p->id }}"
                                    name="nama" value="{{ $p->nama }}" required>
                            </div>
                            <div class="form-group">
                                <label for="editFotoProfile{{ $p->id }}">Foto Profile</label>
                                <div class="mb-2">
                                    <img src="{{ asset('images/' . $p->foto_profile) }}"
                                        alt="{{ $p->foto_profile }}" width="100"
                                        id="previewFotoProfile{{ $p->id }}">
                                </div>
                                <button type="button" class="btn btn-primary"
                                    onclick="document.getElementById('editFotoProfileInput{{ $p->id }}').click();">Ganti
                                    Foto</button>
                                <input type="file" class="form-control file-input d-none"
                                    id="editFotoProfileInput{{ $p->id }}" name="foto_profile"
                                    onchange="previewImage(event, {{ $p->id }})">
                            </div>
                            <div class="form-group">
                                <label for="editJabatan{{ $p->id }}">Jabatan</label>
                                <select class="select2-multiple form-control" name="jabatan[]" multiple="multiple"
                                    id="editJabatan{{ $p->id }}" style="width: 100%">
                                    @foreach ($jabatan as $j)
                                        {{-- <option value="{{ $j->nama }}">
                                            {{ $j->nama }}</option> --}}
                                        <option value="{{ $j->nama }}"
                                            @if (in_array($j->nama, old('jabatan', $p->jabatan))) selected @endif>
                                            {{ $j->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('.select2-multiple').select2({
                                        dropdownParent: $('#editJabatan{{ $p->id }}')
                                    });
                                });
                            </script>
                            <div class="form-group">
                                <label for="editKontrak{{ $p->id }}">Kontrak</label>
                                <input type="text" name="daterange" class="form-control"
                                    id="editKontrak{{ $p->id }}"
                                    value="{{ \Carbon\Carbon::parse($p->tanggal_dipekerjakan)->format('m/d/Y') }} - {{ $p->tanggal_berhenti ? \Carbon\Carbon::parse($p->tanggal_berhenti)->format('m/d/Y') : \Carbon\Carbon::now()->addDays(7)->format('m/d/Y') }}"
                                    required />
                            </div>
                            <div class="form-group">
                                <label for="editGaji{{ $p->id }}">Gaji</label>
                                <input type="number" class="form-control" id="editGaji{{ $p->id }}"
                                    name="gaji" value="{{ $p->gaji }}" required>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- END Modal Edit Pegawai -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.5.4/js/fileinput.min.js"
        integrity="sha512-0wQvB58Ha5coWmcgtg4f11CTSSxfrfLClUp9Vy0qhzYzCZDSnoB4Vhu5JXJFs7rU24LE6JsH+6hpP7vQ22lk5w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            $('.select2-multiple').select2({
                dropdownParent: $('#createModal')
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
    <script>
        $("#foto_profile").fileinput();
    </script>
    <script>
        function previewImage(event, id) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('previewFotoProfile' + id);
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <script>
        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                    .format('YYYY-MM-DD'));
            });
        });
    </script>
    <script>
        $(function() {
            $('input[name="editdaterange"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                    .format('YYYY-MM-DD'));
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#createForm').validate({
                rules: {
                    nama: {
                        required: true
                    },
                    foto_profile: {
                        required: true
                    },
                    jabatan: {
                        required: true
                    },
                    daterange: {
                        required: true
                    },
                    gaji: {
                        required: true
                    }
                }
            });
        });
    </script>
    <script>
        function deletePegawai(id) {
            $('#deleteModal').modal('show');
            $('#confirmDelete').off('click').on('click', function() {
                $.ajax({
                    url: `pegawai/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        console.error("Error: ", xhr);
                        alert("Terjadi kesalahan saat menghapus pegawai");
                    }
                });
            });
        }
    </script>

</body>

</html>
