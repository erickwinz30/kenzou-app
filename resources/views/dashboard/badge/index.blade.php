@extends('dashboard.layout.main')

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
    <x-alert-success :message="session('success')" />
  @endif

  <div class="alert alert-warning" role="alert">
    Pastikan penggunaan badge dan leaderboard sudah benar, dari min-point sampai max-point, dan urutan peringkat agar
    tidak terjadi kesalahan saat melakukan pencatatan transaksi. Non-aktif kan jika badge tidak digunakan.
  </div>

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title">Data Badge untuk Member</h5>
              <a href="/dashboard/badge/create" type="button" class="btn btn-success d-inline">
                <i class="bi bi-plus me-1"></i>Tambah
              </a>
            </div>

            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Min Point</th>
                    <th>Max Point</th>
                    <th>Diskon</th>
                    <th>Gambar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($badges as $badge)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $badge->nama }}</td>
                      <td>{{ $badge->min_point }}</td>
                      <td>{{ $badge->max_point }}</td>
                      <td>{{ $badge->discount * 100 }}%</td>
                      <td>
                        <img src="{{ asset('storage/' . $badge->image) }}" alt="{{ $badge->image }}"
                          style="max-width: 100px">
                      </td>
                      <td class="text-center">
                        @if ($badge->is_active === 1)
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
                        <form action="/dashboard/badge/badge-active-switch" method="POST"
                          id="badgeToggleActivationForm{{ $badge->id }}">
                          @csrf
                          <button type="button" class="btn btn-secondary"
                            onclick="badgeToggleActivation('{{ $badge->id }}')">
                            @if ($badge->is_active === 1)
                              <i class="bi bi-toggle-on"></i>
                            @else
                              <i class="bi bi-toggle-off"></i>
                            @endif
                            <input type="hidden" name="badgeId" id="badgeId" value="{{ $badge->id }}">
                          </button>
                        </form>
                        <a href="/dashboard/badge/{{ $badge->id }}/edit" class="btn btn-warning" id="edit-button"><i
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

      {{-- Badge for Leaderboard --}}
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title">Data Badge untuk Leaderboard</h5>
              <a href="/dashboard/badge-leaderboard/create" type="button" class="btn btn-success d-inline">
                <i class="bi bi-plus me-1"></i>Tambah
              </a>
            </div>

            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Peringkat</th>
                    <th>Diskon</th>
                    <th>Gambar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($leaderboards as $leaderboard)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $leaderboard->badge_name }}</td>
                      <td>{{ $leaderboard->rank }}</td>
                      <td>{{ $leaderboard->discount * 100 }}%</td>
                      <td><img src="{{ asset('storage/' . $leaderboard->image) }}" alt="{{ $leaderboard->image }}"
                          style="max-width: 100px"></td>
                      <td class="text-center">
                        @if ($leaderboard->is_active === 1)
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
                        <form action="/dashboard/badge/leaderboard-active-switch" method="POST"
                          id="leaderboardToggleActivationForm{{ $leaderboard->id }}">
                          @csrf
                          <button type="button" class="btn btn-secondary"
                            onclick="leaderboardToggleActivation('{{ $leaderboard->id }}')">
                            @if ($leaderboard->is_active === 1)
                              <i class="bi bi-toggle-on"></i>
                            @else
                              <i class="bi bi-toggle-off"></i>
                            @endif
                            <input type="hidden" name="leaderboardId" id="leaderboardId" value="{{ $leaderboard->id }}">
                          </button>
                        </form>
                        <a href="/dashboard/badge-leaderboard/{{ $leaderboard->id }}/edit" class="btn btn-warning"
                          id="edit-button"><i class="bi bi-pencil"></i></a>
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
    // toggle badge confirmation
    function badgeToggleActivation(id) {
      Swal.fire({
        title: "Yakin ingin mengubah status aktivasi badge?",
        text: "Aksi ini akan mengubah status aktivasi badge!!!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2980B9",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it!"
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('badgeToggleActivationForm' + id).submit();
        }
      });
    };

    function leaderboardToggleActivation(id) {
      Swal.fire({
        title: "Yakin ingin mengubah status aktivasi badge leaderboard?",
        text: "Aksi ini akan mengubah status aktivasi badge leaderboard!!!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2980B9",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it!"
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('leaderboardToggleActivationForm' + id).submit();
        }
      });
    };
  </script>
@endsection
