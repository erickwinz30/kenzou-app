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
                      {{ $challenge->from_date }}
                    </span>
                  </td>
                  <td class="text-center align-middle" style="padding: 0;">
                    <span
                      style="color:#FFB22C; background-color: #F3FEB8; border-radius: 5px; padding: 3px 5px; display: inline-block;">
                      {{ $challenge->to_date }}
                    </span>
                  </td>
                  <td>{{ $challenge->target }}</td>
                  <td>{{ $challenge->unit->unit_type }}</td>
                  <td class="text-center align-middle" style="padding: 0;">
                    @if ($challenge->is_active === 1)
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
                  <td>
                    <a href="/dashboard/challenge/{{ $challenge->id }}/edit" class="btn btn-warning" id="edit-button"><i
                        class="bi bi-pencil"></i></a>
                    <form action="/dashboard/toggle-challenge-activation" method="POST" id="toggleForm">
                      @csrf
                      <button type="button" class="btn btn-secondary" onclick="toggleConfirmation()">
                        @if ($challenge->is_active === 1)
                        <i class="bi bi-toggle-on"></i>
                        @else
                        <i class="bi bi-toggle-off"></i>
                        @endif
                        <input type="hidden" name="challengeId" id="challengeId" value="{{ $challenge->id }}">
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
  function toggleConfirmation() {
  Swal.fire({
    title: "Yakin ingin mengubah status challenge?",
    text: "Aksi ini akan mengubah status challenge!!!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#2980B9",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, update it!"
    }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById('toggleForm').submit();
    }
    });
  };

  document.getElementById('activeButton').addEventListener('click', function(event) {
    event.preventDefault();
    document.querySelector(".card-title").textContent = "Data Challenge (Aktif)";

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
              style="color:#219653; background-color: #e8f4ed; border-radius: 10px; padding: 5px 10px; display: inline-block;">
              ${challenge.from_date}
            </span>
          </td>
          <td class="text-center align-middle" style="padding: 0;">
            <span
              style="color:#FFB22C; background-color: #F3FEB8; border-radius: 10px; padding: 5px 10px; display: inline-block;">
              ${challenge.to_date}
            </span>
          </td>
          <td>${challenge.target}</td>
          <td>${challenge.unit_id}</td>
          <td class="text-center align-middle" style="padding: 0;">
            <span style="border-radius: 10px; padding: 5px 10px; display: inline-block; 
            color: ${challenge.is_active === 1 ? '#219653' : '#FFB22C'};
            background-color: ${challenge.is_active === 1 ? '#e8f4ed' : '#F3FEB8'};">
              ${challenge.is_active === 1 ? 'Aktif' : 'Tidak Aktif'}
            </span>
          </td>
          <td>
            <a href="/dashboard/challenge/${challenge.id}/edit" class="btn btn-warning" id="edit-button"><i
                class="bi bi-pencil"></i></a>
            <form action="/dashboard/toggle-challenge-activation" method="POST" id="toggleForm">
              @csrf
              <button type="button" class="btn btn-secondary" onclick="toggleConfirmation()">
                <i class="bi bi-toggle-on"></i>
                <input type="hidden" name="challengeId" id="challengeId" value="${challenge.id}">
              </button>
            </form>
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
    document.querySelector(".card-title").textContent = "Data Challenge (Non-Aktif)";

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
              style="color:#219653; background-color: #e8f4ed; border-radius: 10px; padding: 5px 10px; display: inline-block;">
              ${challenge.from_date}
            </span>
          </td>
          <td class="text-center align-middle" style="padding: 0;">
            <span
              style="color:#FFB22C; background-color: #F3FEB8; border-radius: 10px; padding: 5px 10px; display: inline-block;">
              ${challenge.to_date}
            </span>
          </td>
          <td>${challenge.target}</td>
          <td>${challenge.unit_id}</td>
          <td class="text-center align-middle" style="padding: 0;">
            <span style="border-radius: 10px; padding: 5px 10px; display: inline-block; 
            color: ${challenge.is_active === 1 ? '#219653' : '#FFB22C'};
            background-color: ${challenge.is_active === 1 ? '#e8f4ed' : '#F3FEB8'};">
              ${challenge.is_active === 1 ? 'Aktif' : 'Tidak Aktif'}
            </span>
          </td>
          <td>
            <a href="/dashboard/challenge/${challenge.id}/edit" class="btn btn-warning" id="edit-button"><i
                class="bi bi-pencil"></i></a>
            <form action="/dashboard/toggle-challenge-activation" method="POST" id="toggleForm">
              @csrf
              <button type="button" class="btn btn-secondary" onclick="toggleConfirmation()">
                <i class="bi bi-toggle-on"></i>
                <input type="hidden" name="challengeId" id="challengeId" value="${challenge.id}">
              </button>
            </form>
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
