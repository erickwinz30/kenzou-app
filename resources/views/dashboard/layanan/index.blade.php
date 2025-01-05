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
    <x-alert-success type="success" :message="session('success')" />
  @endif

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between" data-bs-toggle='modal'
              data-bs-target='#inputModal'>
              <h5 class="card-title">Data Layanan</h5>
              <div>
                <a href="/dashboard/layanan/create" type="button" class="btn btn-success d-inline">
                  <i class="bi bi-plus" style="margin-right: 2px;"></i>Layanan
                </a>
              </div>
            </div>

            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Layanan</th>
                    <th>Harga</th>
                    <th>Point</th>
                    <th>Detail</th>
                    <th>Kategori</th>
                    <th>Status</th>
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
                      <td>{{ $layanan->point }}</td>
                      <td>{{ Str::limit($layanan->detail, 20) }}</td>
                      <td>{{ $layanan->categoryLayanan->name }}</td>
                      <td class="text-center align-middle" style="padding: 0;">
                        @if ($layanan->is_active === 1)
                          <span
                            style="color:#219653; background-color: #e8f4ed; border-radius: 5px; padding: 3px 5px; display: inline-block;">
                            Aktif
                          </span>
                        @else
                          <span
                            style="color:#FFB22C; background-color: #F3FEB8; border-radius: 5px; padding: 3px 5px; display: inline-block;">
                            Tidak Aktif
                          </span>
                        @endif
                      </td>
                      <td class="text-center align-middle" style="padding: 0;">
                        <span
                          style="color:#219653; background-color: #e8f4ed; border-radius: 10px; padding: 5px 10px; display: inline-block;">
                          {{ $layanan->created_at }}
                        </span>
                      </td>
                      <td>
                        <form action="/dashboard/toggle-layanan-activation" method="POST"
                          id="toggleActivationLayananForm{{ $layanan->id }}">
                          @csrf
                          <button type="button" class="btn btn-secondary"
                            onclick="toggleActivationLayanan('{{ $layanan->id }}')">
                            @if ($layanan->is_active === 1)
                              <i class="bi bi-toggle-on"></i>
                            @else
                              <i class="bi bi-toggle-off"></i>
                            @endif
                            <input type="hidden" name="layananId" id="layananId" value="{{ $layanan->id }}">
                          </button>
                        </form>
                        <a href="/dashboard/layanan/{{ $layanan->id }}" class="btn btn-info"><i
                            class="bi bi-eye"></i></a>
                        <a href="/dashboard/layanan/{{ $layanan->id }}/edit" class="btn btn-warning"><i
                            class="bi bi-pencil"></i></a>
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

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
              <h5 class="card-title">Data Kategori Layanan</h5>
              <a href="/dashboard/category-layanan/create" type="button" class="btn btn-success d-inline">
                <i class="bi bi-plus"></i>Kategori
              </a>
            </div>

            <!-- Table with stripped rows -->
            <div class=" table-responsive">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Status Aktif</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($categories as $category)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $category->name }}</td>
                      <td class="text-center align-middle" style="padding: 0;">
                        @if ($category->is_active === 1)
                          <span
                            style="color:#219653; background-color: #e8f4ed; border-radius: 5px; padding: 3px 5px; display: inline-block;">
                            Aktif
                          </span>
                        @else
                          <span
                            style="color:#FFB22C; background-color: #F3FEB8; border-radius: 5px; padding: 3px 5px; display: inline-block;">
                            Tidak Aktif
                          </span>
                        @endif
                      </td>
                      <td>
                        <form action="/dashboard/toggle-category-layanan-activation" method="POST"
                          id="toggleActivationCategoryLayananForm{{ $category->id }}">
                          @csrf
                          <button type="button" class="btn btn-secondary"
                            onclick="toggleActivationCategoryLayanan('{{ $category->id }}')">
                            @if ($category->is_active === 1)
                              <i class="bi bi-toggle-on"></i>
                            @else
                              <i class="bi bi-toggle-off"></i>
                            @endif
                            <input type="hidden" name="layananId" id="layananId" value="{{ $category->id }}">
                          </button>
                        </form>
                        <a href="/dashboard/category-layanan/{{ $category->id }}/edit" class="btn btn-warning"><i
                            class="bi bi-pencil"></i></a>
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
    function toggleActivationLayanan(id) {
      Swal.fire({
        title: "Yakin ingin mengubah status aktivasi layanan?",
        text: "Aksi ini akan mengubah status aktivasi layanan!!!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2980B9",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it!"
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('toggleActivationLayananForm' + id).submit();
        }
      });
    };

    function toggleActivationCategoryLayanan(id) {
      Swal.fire({
        title: "Yakin ingin mengubah status aktivasi ketegori layanan?",
        text: "Aksi ini akan mengubah status aktivasi kategori layanan!!!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2980B9",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it!"
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('toggleActivationCategoryLayananForm' + id).submit();
        }
      });
    };
  </script>
@endsection
