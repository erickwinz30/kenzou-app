@extends('dashboard.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Voucher</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active">Voucher</li>
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
              <h5 class="card-title">Data Voucher (Aktif)</h5>
              <div class="d-flex justify-content-end">
                <a href="/dashboard/voucher" class="btn btn-primary me-2"
                  style="background-color: #4154f1; color:white">Aktif</a>
                <a href="/dashboard/nonActiveFetch" class="btn btn-primary me-2"
                  style="background-color: #4154f1; color:white" id="nonActiveButton">Non-Aktif</a>
                <a href="/dashboard/allVoucherFetch" class="btn btn-primary"
                  style="background-color: #4154f1; color:white" id="allVoucherButton">All</a>
              </div>
            </div>

            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Keperluan Point</th>
                    <th>Diskon</th>
                    <th>Status</th>
                    <th>Tanggal Awal</th>
                    <th>Tanggal Akhir</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($vouchers as $voucher)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $voucher->nama }}</td>
                      <td>{{ $voucher->description }}</td>
                      <td>{{ $voucher->point_needed }}</td>
                      <td>{{ $voucher->discount * 100 }}%</td>
                      <td class="text-center align-middle" style="padding: 0;">
                        @if ($voucher->is_active === 1)
                          <span
                            style="color:#219653; background-color: #e8f4ed; border-radius: 10px; padding: 5px 10px; display: inline-block;">
                            Aktif
                          </span>
                        @else
                          <span
                            style="color:#FFB22C; background-color: #F3FEB8; border-radius: 10px; padding: 5px 10px; display: inline-block;">
                            Tidak Aktif
                          </span>
                        @endif
                      </td>
                      <td class="text-center align-middle" style="padding: 0;">
                        <span
                          style="color:#219653; background-color: #e8f4ed; border-radius: 10px; padding: 3px 5px; display: inline-block; box-sizing: border-box">
                          {{ $voucher->from_date }}
                        </span>
                      </td>
                      <td class="text-center align-middle" style="padding: 0;">
                        <span
                          style="color:#FFB22C; background-color: #F3FEB8; border-radius: 10px; padding: 5px 10px; display: inline-block;">
                          {{ $voucher->to_date }}
                        </span>
                      </td>
                      <td>
                        <a href="/dashboard/voucher/{{ $voucher->id }}/edit" class="btn btn-warning" id="edit-button"><i
                            class="bi bi-pencil"></i></a>
                        <form action="/dashboard/voucher/{{ $voucher->id }}" method="POST" class="d-inline"
                          id="deleteForm{{ $voucher->id }}">
                          @method('DELETE')
                          @csrf
                          <button type="button" class="btn btn-danger"
                            onclick="deleteConfirmation('{{ $voucher->id }}')">
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
    document.getElementById('nonActiveButton').addEventListener('click', function(event) {
      event.preventDefault();
      document.querySelector(".card-title").textContent = "Data Voucher (Non-Aktif)";

      document.querySelector("tbody").innerHTML = "";

      fetch("/dashboard/nonActiveFetch", {
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
          data.forEach(voucher => {
            let row = document.createElement("tr");
            row.innerHTML = `
              <td>${iteration}</td>
              <td>${voucher.nama}</td>
              <td>${voucher.description}</td>
              <td>${voucher.point_needed}</td>
              <td>${voucher.discount * 100}%</td>
              <td class="text-center align-middle" style="padding: 0;">
                <span
                  style="color:#FFB22C; background-color: #F3FEB8; border-radius: 10px; padding: 5px 10px; display: inline-block;">
                  Tidak Aktif
                </span>
              </td>
              <td class="text-center align-middle" style="padding: 0;">
                <span style="color:#219653; background-color: #e8f4ed; border-radius: 10px; padding: 5px 10px; display: inline-block;">
                  ${voucher.from_date}
                </span>
              </td>
              <td class="text-center align-middle" style="padding: 0;">
                <span
                  style="color:#FFB22C; background-color: #F3FEB8; border-radius: 10px; padding: 5px 10px; display: inline-block;">
                  ${voucher.to_date}
                </span>
              </td>
              <td>
                <a href="/dashboard/voucher/${voucher.id}/edit" class="btn btn-warning" id="edit-button"><i class="bi bi-pencil"></i></a>
                <form action="/dashboard/voucher/${voucher.id}" method="POST" class="d-inline" id="deleteForm${voucher.id}">
                @method('DELETE')
                @csrf
                <button type="button" class="btn btn-danger" onclick="deleteConfirmation('${voucher.id}')"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            `;
            iteration++;
            document.querySelector("tbody").appendChild(row);
          });
        })
        .catch(error => console.error(error));
    });

    document.getElementById('allVoucherButton').addEventListener('click', function(event) {
      event.preventDefault();
      document.querySelector(".card-title").textContent = "Data Voucher (Semua)";

      document.querySelector("tbody").innerHTML = "";

      fetch("/dashboard/allVoucherFetch", {
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
          data.forEach(voucher => {
            let row = document.createElement("tr");
            row.innerHTML = `
              <td>${iteration}</td>
              <td>${voucher.nama}</td>
              <td>${voucher.description}</td>
              <td>${voucher.point_needed}</td>
              <td>${voucher.discount * 100}%</td>
              <td class="text-center align-middle" style="padding: 0;">
                <span
                  style="border-radius: 10px; padding: 5px 10px; display: inline-block; 
                        color: ${voucher.is_active === 1 ? '#219653' : '#FFB22C'};
                        background-color: ${voucher.is_active === 1 ? '#e8f4ed' : '#F3FEB8'};">
                  ${voucher.is_active === 1 ? 'Aktif' : 'Tidak Aktif'}
                </span>
              </td>
              <td class="text-center align-middle" style="padding: 0;">
                <span style="color:#219653; background-color: #e8f4ed; border-radius: 10px; padding: 5px 10px; display: inline-block;">
                  ${voucher.from_date}
                </span>
              </td>
              <td class="text-center align-middle" style="padding: 0;">
                <span
                  style="color:#FFB22C; background-color: #F3FEB8; border-radius: 10px; padding: 5px 10px; display: inline-block;">
                  ${voucher.to_date}
                </span>
              </td>
              <td>
                <a href="/dashboard/voucher/${voucher.id}/edit" class="btn btn-warning" id="edit-button"><i class="bi bi-pencil"></i></a>
                <form action="/dashboard/voucher/${voucher.id}" method="POST" class="d-inline" id="deleteForm${voucher.id}">
                @method('DELETE')
                @csrf
                <button type="button" class="btn btn-danger" onclick="deleteConfirmation('${voucher.id}')"><i class="bi bi-trash"></i></button>
                </form>
              </td>
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
