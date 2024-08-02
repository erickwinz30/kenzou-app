@extends('dashboard.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Kasir</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Kasir</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @if (session()->has('success'))
    <div class="row justify-content-center">
      <div class="alert alert-success alert-dismissible fade show col-lg-10" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  @if (session()->has('error'))
    <div class="row justify-content-center">
      <div class="alert alert-danger alert-dismissible fade show col-lg-12 justify-content-center" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between" data-bs-toggle='modal'
              data-bs-target='#inputModal'>
              <h5 class="card-title">Data Kasir</h5>
              <button type="button" class="btn btn-success d-inline">
                <i class="bi bi-plus" style="margin-right: 2px;"></i>Kasir
              </button>
            </div>

            <!-- Input Modal -->
            <div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Kasir</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form action="/kasir" method="POST">
                      @csrf
                      <div class="mb-3">
                        <label for="nama" class="form-label @error('nama') is-invalid @enderror">Nama Kasir</label>
                        <input type="text" class="form-control" id="nama" name="nama"
                          value="{{ old('nama') }}" required autofocus>
                        @error('nama')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                      <div class="mb-3">
                        <label for="username" class="form-label @error('username') is-invalid @enderror">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                          value="{{ old('username') }}" required autofocus>
                        @error('username')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                      <div class="mb-3">
                        <label for="email" class="form-label @error('email') is-invalid @enderror">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                          value="{{ old('email') }}" autofocus>
                        @error('email')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                      <div class="mb-3">
                        <label for="nomor_telepon" class="form-label @error('nomor_telepon') is-invalid @enderror">No.
                          Hp</label>
                        <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon"
                          value="{{ old('nomor_telepon') }}" required autofocus>
                        @error('nomor_telepon')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                      <div class="input-group mb-3" style="margin-top: 26px;">
                        <label class="input-group-text" for="inputGroupSelect02">Role</label>
                        <select class="form-select" id="inputGroupSelect02" name="is_admin">
                          <option selected>Pilih...</option>
                          <option value="0">Kasir</option>
                          <option value="1">Admin</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="password" class="form-label @error('password') is-invalid @enderror">Password</label>
                        <div class="d-flex">
                          <input type="password" class="form-control" id="password" name="password" required autofocus>
                          <button type="button" class="btn btn-outline-secondary" style="margin-left: 6px;"
                            id="togglePassword">
                            <i class="bi bi-eye"></i>
                          </button>
                        </div>
                        @error('password')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel"
              aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Kasir</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form action="" method="POST" id="edit-form">
                      @method('put')
                      @csrf
                      <input type="hidden" name="id" id="edit_id">
                      <div class="mb-3">
                        <label for="edit_nama" class="form-label @error('edit_nama') is-invalid @enderror">Nama
                          Kasir</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required autofocus>
                        @error('edit_nama')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                      <div class="mb-3">
                        <label for="edit_username"
                          class="form-label @error('edit_username') is-invalid @enderror">Username</label>
                        <input type="text" class="form-control" id="edit_username" name="username" required
                          autofocus>
                        @error('edit_username')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                      <div class="mb-3">
                        <label for="edit_email"
                          class="form-label @error('edit_email') is-invalid @enderror">Email</label>
                        <input type="edit_email" class="form-control" id="edit_email" name="email" autofocus>
                        @error('edit_email')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                      <div class="mb-3">
                        <label for="edit_nomor_telepon"
                          class="form-label @error('edit_nomor_telepon') is-invalid @enderror">No.
                          Hp</label>
                        <input type="text" class="form-control" id="edit_nomor_telepon" name="nomor_telepon"
                          required autofocus>
                        @error('edit_nomor_telepon')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                      <div class="input-group mb-3">
                        <select class="form-select" id="inputGroupSelect02" name="is_admin">
                          <option value="">Pilih..</option>
                          <option value="0">Kasir</option>
                          <option value="1">Admin</option>
                        </select>
                        <label class="input-group-text" for="inputGroupSelect02">Role</label>
                      </div>
                      <div class="mb-3">
                        <label for="edit_password"
                          class="form-label @error('edit_password') is-invalid @enderror">Password</label>
                        <div class="d-flex">
                          <input type="password" class="form-control" id="password" name="password" autofocus>
                          <button type="button" class="btn btn-outline-secondary" style="margin-left: 6px;"
                            id="togglePassword">
                            <i class="bi bi-eye"></i>
                          </button>
                        </div>
                        @error('edit_password')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Edit</button>
                      </div>
                    </form>
                  </div>

                </div>
              </div>
            </div>

            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>
                      <b>N</b>ama
                    </th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>No. Hp</th>
                    <th>Akses Admin</th>
                    <th data-type="datetime" data-format="YYYY/DD/MM">Terdaftar</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($users as $user)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $user->nama }}</td>
                      <td>{{ $user->username }}</td>
                      <td>{{ $user->email }}</td>
                      <td>{{ $user->nomor_telepon }}</td>
                      <td>
                        @if ($user->is_admin == 1)
                          Ya
                        @else
                          Tidak
                        @endif
                      </td>
                      <td>
                        <span
                          style="color:#219653; background-color: #e8f4ed; border-radius: 50px; padding: 5px 10px; display: inline-block;">
                          {{ $user->created_at }}
                        </span>
                      </td>
                      <td>
                        <button class="btn btn-warning" id="edit-button" data-bs-toggle='modal'
                          data-bs-target='#editModal' data-id="{{ $user->id }}" data-nama="{{ $user->nama }}"
                          data-username="{{ $user->username }}" data-email="{{ $user->email }}"
                          data-nomor_telepon="{{ $user->nomor_telepon }}" data-is_admin="{{ $user->is_admin }}"><i
                            class="bi bi-pencil"></i></button>
                        <form action="/kasir/{{ $user->id }}" method="POST" class="d-inline"
                          id="deleteForm{{ $user->id }}">
                          @method('DELETE')
                          @csrf
                          <button type="button" class="btn btn-danger"
                            onclick="deleteConfirmation('{{ $user->id }}')">
                            <i class="bi bi-trash"></i>
                          </button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- End Table with stripped rows -->
          </div>
        </div>

      </div>
    </div>
  </section>

  <script>
    //menampilkan data kasir saat edit
    document.addEventListener('DOMContentLoaded', function() {
      const editButtons = document.querySelectorAll('#edit-button');
      editButtons.forEach(button => {
        button.addEventListener('click', function() {
          const id = button.getAttribute('data-id');
          const nama = button.getAttribute('data-nama');
          const username = button.getAttribute('data-username');
          const email = button.getAttribute('data-email');
          const nomorTelepon = button.getAttribute('data-nomor_telepon');
          const isAdmin = button.getAttribute('data-is_admin');

          //menambahkan id ke action dari form edit
          var editForm = document.querySelector('#edit-form');
          editForm.action = `/kasir/${id}`;

          document.querySelector('#editModal input[id="edit_id"]').value = id;
          document.querySelector('#editModal input[id="edit_nama"]').value = nama;
          document.querySelector('#editModal input[id="edit_username"]').value = username;
          document.querySelector('#editModal input[id="edit_email"]').value = email;
          document.querySelector('#editModal input[id="edit_nomor_telepon"]').value = nomorTelepon;
          document.querySelector('#editModal select[id="edit_is_admin"]').value = isAdmin;
        });
      });
    });

    //tombol tampilkan password
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('password');
      const icon = this.querySelector('i');
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
      } else {
        passwordInput.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
      }
    });

    //konfirmasi hapus data
    function deleteConfirmation(id) {
      Swal.fire({
        title: "Yakin ingin menghapus?",
        text: "Aksi ini tidak bisa mengembalikan data!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2980B9",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('deleteForm' + id).submit();
        }
      });
    }
  </script>
@endsection
