@extends('dashboard.layout.main');

@section('container')
  <div class="pagetitle">
    <h1>Badge</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active">Badge</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @if (session()->has('success'))
    <div class="row justify-content-center">
      <div class="alert alert-success alert-dismissible fade show col-lg-12" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Data Badge</h5>

            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Min Point</th>
                    <th>Diskon</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($badges as $badge)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $badge->nama }}</td>
                      <td>{{ $badge->min_point }}</td>
                      <td>{{ $badge->discount * 100 }}%</td>
                      <td><img src="{{ asset('storage/' . $badge->image) }}" alt="{{ $badge->image }}"
                          style="max-width: 100px"></td>
                      <td>
                        <a href="/dashboard/badge/{{ $badge->id }}/edit" class="btn btn-warning" id="edit-button"><i
                            class="bi bi-pencil"></i></a>
                        <form action="/dashboard/badge/{{ $badge->id }}" method="POST" class="d-inline"
                          id="deleteForm{{ $badge->id }}">
                          @method('DELETE')
                          @csrf
                          <button type="button" class="btn btn-danger"
                            onclick="deleteConfirmation('{{ $badge->id }}')">
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
    };
  </script>
@endsection
