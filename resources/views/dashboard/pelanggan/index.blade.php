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
              <div class="d-flex justify-content-end">
                <a href="/dashboard/pelanggan" class="btn btn-primary me-2"
                  style="background-color: #4154f1; color:white">All</a>
                <a href="/dashboard/memberFetch" class="btn btn-primary me-2"
                  style="background-color: #4154f1; color:white" id="memberButton">Member</a>
                <a href="/dashboard/pelangganMember" class="btn btn-primary"
                  style="background-color: #4154f1; color:white" id="pelangganButton">Non Member</a>
              </div>
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

                            $tanggalLahir = \Carbon\Carbon::parse($tanggalLahir)->format('Y-m-d');
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
                          <a href="/dashboard/pelanggan/{{ $pelanggan->id }}/edit" class="btn btn-warning"
                            id="edit-button"><i class="bi bi-pencil"></i></a>
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
                        <td>-</td>
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
    document.getElementById('memberButton').addEventListener('click', function(event) {
      event.preventDefault();

      document.querySelector("tbody").innerHTML = "";

      fetch("/dashboard/memberFetch", {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
          }
        })
        .then(response => response.json())
        .then((data) => {
          console.log(data);
          let iteration = 1;
          data.forEach(pelanggan => {
            let row = document.createElement("tr");
            row.innerHTML = `
              <td>${iteration}</td>
              <td>${pelanggan.nomor_telepon}</td>
              <td>${pelanggan.nama}</td>
              <td>${pelanggan.email}</td>
              <td>
                <span style="color:#219653; background-color: #e8f4ed; border-radius: 50px; padding: 5px 10px; display: inline-block;">
                  ${pelanggan.tanggal_lahir} / ${pelanggan.umur}
                </span>
              </td>
              <td>${pelanggan.experience_point}</td>
              <td>${pelanggan.redeemable_point}</td>
              <td>${pelanggan.referral_code}</td>
              <td>
                <a href="/dashboard/pelanggan/${pelanggan.id}/edit" class="btn btn-warning" id="edit-button"><i class="bi bi-pencil"></i></a>
                <form action="/dashboard/pelanggan/${pelanggan.id}" method="POST" class="d-inline" id="deleteForm${pelanggan.id}">
                @method('DELETE')
                @csrf
                <button type="button" class="btn btn-danger" onclick="deleteConfirmation('${pelanggan.id}')"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            `;
            iteration++;
            document.querySelector("tbody").appendChild(row);
          });
        })
        .catch(error => console.error(error));
    });

    document.getElementById('pelangganButton').addEventListener('click', function(event) {
      event.preventDefault();

      document.querySelector("tbody").innerHTML = "";

      fetch("/dashboard/pelangganFetch", {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
          }
        })
        .then(response => response.json())
        .then((data) => {
          console.log(data);
          let iteration = 1;
          data.forEach(pelanggan => {
            let row = document.createElement("tr");
            row.innerHTML = `
              <td>${iteration}</td>
              <td>${pelanggan.nomor_telepon}</td>
              <td>${pelanggan.nama}</td>
              <td>${pelanggan.email}</td>
              <td>${pelanggan.tanggal_lahir} / ${pelanggan.umur}</td>
              <td>${pelanggan.experience_point}</td>
              <td>${pelanggan.redeemable_point}</td>
              <td>${pelanggan.referral_code}</td>
              <td>-</td>
            `;
            iteration++;
            document.querySelector("tbody").appendChild(row);
          });
        })
        .catch(error => console.error(error));
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
    };
  </script>
@endsection
