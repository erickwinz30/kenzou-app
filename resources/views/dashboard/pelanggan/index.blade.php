@extends('dashboard.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Pelanggan</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active">Pelanggan</li>
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
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
              <h5 class="card-title">Data Pelanggan</h5>
            </div>

            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>No. Hp</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th data-type="datetime" data-format="YYYY/DD/MM">Tgl Lahir / Umur</th>
                    <th>Exp Point</th>
                    <th>Redeem Point</th>
                    <th>Referral Code</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($pelanggans as $pelanggan)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $pelanggan->nomor_telepon }}</td>
                      @if ($pelanggan->member_id)
                        <td>{{ $pelanggan->member->nama }}</td>
                        <td>{{ $pelanggan->member->email }}</td>
                        <td>
                          @php
                            $tanggalLahir = $pelanggan->member->tanggal_lahir;
                            $umur = \Carbon\Carbon::parse($tanggalLahir)->age;
                          @endphp
                          <span
                            style="color:#219653; background-color: #e8f4ed; border-radius: 50px; padding: 5px 10px; display: inline-block;">
                            {{ $tanggalLahir }} / {{ $umur }}
                          </span>
                        </td>
                        <td>{{ $pelanggan->member->experience_point }}</td>
                        <td>{{ $pelanggan->member->redeemable_point }}</td>
                        <td>{{ $pelanggan->member->referral_code }}</td>
                        <td>
                          <button class="btn btn-warning" id="edit-button"><i class="bi bi-pencil"></i></button>
                          <form action="/dashboard/pelanggan/{{ $pelanggan->id }}" method="POST" class="d-inline"
                            id="deleteForm{{ $pelanggan->id }}">
                            @method('DELETE')
                            @csrf
                            <button type="button" class="btn btn-danger"
                              onclick="deleteConfirmation('{{ $pelanggan->id }}')">
                              <i class="bi bi-trash"></i>
                            </button>
                          </form>
                        </td>
                      @else
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                          <button class="btn btn-warning" id="edit-button"><i class="bi bi-pencil"></i></button>
                          <form action="/dashboard/pelanggan/{{ $pelanggan->id }}" method="POST" class="d-inline"
                            id="deleteForm{{ $pelanggan->id }}">
                            @method('DELETE')
                            @csrf
                            <button type="button" class="btn btn-danger"
                              onclick="deleteConfirmation('{{ $pelanggan->id }}')">
                              <i class="bi bi-trash"></i>
                            </button>
                          </form>
                        </td>
                      @endif
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
