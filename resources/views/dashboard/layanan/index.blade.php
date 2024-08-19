@extends('dashboard.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Layanan</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active"><a href="/dashboard/layanan">Layanan</a></li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @if (session()->has('success'))
    <div class="row justify-content-center">
      <div class="alert alert-success alert-dismissible fade show col-lg-10 justify-content-center" role="alert">
        {{ session('success') }}
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
              <h5 class="card-title">Data Layanan</h5>
            </div>

            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Layanan</th>
                    <th>Harga</th>
                    <th>Detail</th>
                    <th data-type="datetime" data-format="YYYY/DD/MM">Tanggal Buat</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($layanans as $layanan)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $layanan->nama_layanan }}</td>
                      <td>Rp {{ number_format($layanan->harga, 0, ',', '.') }}</td>
                      <td>{{ Str::limit($layanan->detail, 20) }}</td>
                      <td class="text-center align-middle" style="padding: 0;">
                        <span
                          style="color:#219653; background-color: #e8f4ed; border-radius: 50px; padding: 5px 10px; display: inline-block;">
                          {{ $layanan->added_date }}
                        </span>
                      </td>
                      <td>
                        <a href="/dashboard/layanan/{{ $layanan->id }}/edit" class="btn btn-warning"><i
                            class="bi bi-pencil"></i></a>
                        <form action="/dashboard/layanan/{{ $layanan->id }}" method="POST" class="d-inline"
                          id="deleteForm{{ $layanan->id }}">
                          @method('DELETE')
                          @csrf
                          <button type="button" class="btn btn-danger"
                            onclick="deleteConfirmation('{{ $layanan->id }}')">
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
    }
  </script>
@endsection
