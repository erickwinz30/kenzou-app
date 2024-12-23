@extends('dashboard.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Challenge</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active">Challenge</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @if (session()->has('success'))
    <x-alert-success :message="session('success')" />
  @endif

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title">Data Challenge</h5>
              <div class="d-flex justify-content-end">
                <a href="/dashboard/challenge" class="btn btn-primary me-2"
                  style="background-color: #4154f1; color:white">Semua</a>
                <a href="/dashboard/challenge-active-fetch" class="btn btn-primary me-2"
                  style="background-color: #4154f1; color:white" id="activeButton">Aktif</a>
                <a href="/dashboard/challenge-nonactive-fetch" class="btn btn-primary"
                  style="background-color: #4154f1; color:white" id="nonActiveButton">Non-Aktif</a>
              </div>
            </div>

            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Deskripsi</th>
                    <th>Dari</th>
                    <th>Sampai</th>
                    <th>Target</th>
                    <th>Satuan</th>
                    <th>Hadiah yang diperoleh</th>
                    <th>Dapat diulang</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($challenges as $challenge)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $challenge->description }}</td>
                      <td class="text-center align-middle" style="padding: 0;">
                        <span
                          style="color:#219653; background-color: #e8f4ed; border-radius: 5px; padding: 3px 5px; display: inline-block; box-sizing: border-box">
                          {{ \Carbon\Carbon::parse($challenge->from_date)->format('d-m-Y H:i:s') }}
                        </span>
                      </td>
                      <td class="text-center align-middle" style="padding: 0;">
                        <span
                          style="color:#FFB22C; background-color: #F3FEB8; border-radius: 5px; padding: 3px 5px; display: inline-block;">
                          {{ \Carbon\Carbon::parse($challenge->to_date)->format('d-m-Y H:i:s') }}
                        </span>
                      </td>
                      <td>{{ $challenge->target }}</td>
                      <td>{{ $challenge->unit }}</td>
                      <td>{{ $challenge->layanan->nama_layanan }}</td>
                      <td class="text-center align-middle" style="padding: 0;">
                        @if ($challenge->is_repeatable === 1)
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
                        <form action="/dashboard/toggle-challenge-repeatable" method="POST"
                          id="toggleRepeatableForm{{ $challenge->id }}">
                          @csrf
                          <button type="button" class="btn btn-secondary"
                            onclick="toggleRepeatable('{{ $challenge->id }}')">
                            @if ($challenge->is_repeatable === 1)
                              <i class="bi bi-toggle-on"></i>
                            @else
                              <i class="bi bi-toggle-off"></i>
                            @endif
                            <input type="hidden" name="challengeId" id="challengeId" value="{{ $challenge->id }}">
                          </button>
                        </form>
                      </td>
                      <td class="text-center align-middle" style="padding: 0;">
                        @if ($challenge->is_active === 1)
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
                        <form action="/dashboard/toggle-challenge-activation" method="POST"
                          id="toggleActivationForm{{ $challenge->id }}">
                          @csrf
                          <button type="button" class="btn btn-secondary"
                            onclick="toggleActivation('{{ $challenge->id }}')">
                            @if ($challenge->is_active === 1)
                              <i class="bi bi-toggle-on"></i>
                            @else
                              <i class="bi bi-toggle-off"></i>
                            @endif
                            <input type="hidden" name="challengeId" id="challengeId" value="{{ $challenge->id }}">
                          </button>
                        </form>
                      </td>
                      <td>
                        <a href="/dashboard/challenge/{{ $challenge->id }}/edit" class="btn btn-warning"
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
    function toggleRepeatable(id) {
      Swal.fire({
        title: "Yakin ingin mengubah status perulangan challenge?",
        text: "Aksi ini akan mengubah status perulangan challenge!!!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2980B9",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it!"
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('toggleRepeatableForm' + id).submit();
        }
      });
    };

    function toggleActivation(id) {
      Swal.fire({
        title: "Yakin ingin mengubah status aktivasi challenge?",
        text: "Aksi ini akan mengubah status aktivasi challenge!!!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2980B9",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it!"
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('toggleActivationForm' + id).submit();
        }
      });
    };

    document.getElementById('activeButton').addEventListener('click', function(event) {
      event.preventDefault();
      document.querySelector(".card-title").textContent = "Data Challenge (Status: Aktif)";

      document.querySelector("tbody").innerHTML = "";

      fetch("/dashboard/challenge-active-fetch", {
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
          data.forEach(challenge => {
            let row = document.createElement("tr");
            row.innerHTML = `
          <td>${iteration}</td>
          <td>${challenge.description}</td>
          <td class="text-center align-middle" style="padding: 0;">
            <span
              style="color:#219653; background-color: #e8f4ed; border-radius: 5px; padding: 3px 5px; display: inline-block;">
              ${challenge.from_date}
            </span>
          </td>
          <td class="text-center align-middle" style="padding: 0;">
            <span
              style="color:#FFB22C; background-color: #F3FEB8; border-radius: 5px; padding: 3px 5px; display: inline-block;">
              ${challenge.to_date}
            </span>
          </td>
          <td>${challenge.target}</td>
          <td>${challenge.unit}</td>
          <td>${challenge.layanan}</td>
          <td class="text-center align-middle" style="padding: 0;">
            <span style="border-radius: 10px; padding: 5px 10px; display: inline-block; 
            color: ${challenge.is_repeatable === 1 ? '#219653' : '#FFB22C'};
            background-color: ${challenge.is_repeatable === 1 ? '#e8f4ed' : '#F3FEB8'};">
              ${challenge.is_repeatable === 1 ? 'Aktif' : 'Tidak Aktif'}
            </span>
            <form action="/dashboard/toggle-challenge-repeatable" method="POST" id="toggleRepeatableForm${challenge.id}">
              @csrf
              <button type="button" class="btn btn-secondary" onclick="toggleRepeatable('${challenge.id}')">
                <i class="bi bi-toggle-on"></i>
                <input type="hidden" name="challengeId" id="challengeId" value="${challenge.id}">
              </button>
            </form>
          </td>
          <td class="text-center align-middle" style="padding: 0;">
            <span style="border-radius: 10px; padding: 5px 10px; display: inline-block; 
            color: ${challenge.is_active === 1 ? '#219653' : '#FFB22C'};
            background-color: ${challenge.is_active === 1 ? '#e8f4ed' : '#F3FEB8'};">
              ${challenge.is_active === 1 ? 'Aktif' : 'Tidak Aktif'}
            </span>
            <form action="/dashboard/toggle-challenge-activation" method="POST" id="toggleActivationForm${challenge.id}">
              @csrf
              <button type="button" class="btn btn-secondary" onclick="toggleActivation('${challenge.id}')">
                <i class="bi bi-toggle-on"></i>
                <input type="hidden" name="challengeId" id="challengeId" value="${challenge.id}">
              </button>
            </form>
          </td>
          <td>
            <a href="/dashboard/challenge/${challenge.id}/edit" class="btn btn-warning" id="edit-button"><i
                class="bi bi-pencil"></i></a>
          </td>
        `;
            iteration++;
            document.querySelector("tbody").appendChild(row);
          });
        })
        .catch(error => console.error(error));
    });

    document.getElementById('nonActiveButton').addEventListener('click', function(event) {
      event.preventDefault();
      document.querySelector(".card-title").textContent = "Data Challenge (Status: Non-Aktif)";

      document.querySelector("tbody").innerHTML = "";

      fetch("/dashboard/challenge-nonactive-fetch", {
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
          data.forEach(challenge => {
            let row = document.createElement("tr");
            row.innerHTML = `
          <td>${iteration}</td>
          <td>${challenge.description}</td>
          <td class="text-center align-middle" style="padding: 0;">
            <span
              style="color:#219653; background-color: #e8f4ed; border-radius: 5px; padding: 3px 5px; display: inline-block;">
              ${challenge.from_date}
            </span>
          </td>
          <td class="text-center align-middle" style="padding: 0;">
            <span
              style="color:#FFB22C; background-color: #F3FEB8; border-radius: 5px; padding: 3px 5px; display: inline-block;">
              ${challenge.to_date}
            </span>
          </td>
          <td>${challenge.target}</td>
          <td>${challenge.unit}</td>
          <td>${challenge.layanan}</td>
          <td class="text-center align-middle" style="padding: 0;">
            <span style="border-radius: 10px; padding: 5px 10px; display: inline-block; 
            color: ${challenge.is_repeatable === 1 ? '#219653' : '#FFB22C'};
            background-color: ${challenge.is_repeatable === 1 ? '#e8f4ed' : '#F3FEB8'};">
              ${challenge.is_repeatable === 1 ? 'Aktif' : 'Tidak Aktif'}
            </span>
            <form action="/dashboard/toggle-challenge-repeatable" method="POST" id="toggleRepeatableForm${challenge.id}">
              @csrf
              <button type="button" class="btn btn-secondary" onclick="toggleRepeatable('${challenge.id}')">
                <i class="bi bi-toggle-on"></i>
                <input type="hidden" name="challengeId" id="challengeId" value="${challenge.id}">
              </button>
            </form>
          </td>
          <td class="text-center align-middle" style="padding: 0;">
            <span style="border-radius: 10px; padding: 5px 10px; display: inline-block; 
            color: ${challenge.is_active === 1 ? '#219653' : '#FFB22C'};
            background-color: ${challenge.is_active === 1 ? '#e8f4ed' : '#F3FEB8'};">
              ${challenge.is_active === 1 ? 'Aktif' : 'Tidak Aktif'}
            </span>
            <form action="/dashboard/toggle-challenge-activation" method="POST" id="toggleActivationForm${challenge.id}">
              @csrf
              <button type="button" class="btn btn-secondary" onclick="toggleActivation('${challenge.id}')">
                <i class="bi bi-toggle-on"></i>
                <input type="hidden" name="challengeId" id="challengeId" value="${challenge.id}">
              </button>
            </form>
          </td>
          <td>
            <a href="/dashboard/challenge/${challenge.id}/edit" class="btn btn-warning" id="edit-button"><i
                class="bi bi-pencil"></i></a>
          </td>
        `;
            iteration++;
            document.querySelector("tbody").appendChild(row);
          });
        })
        .catch(error => console.error(error));
    });
  </script>
@endsection
