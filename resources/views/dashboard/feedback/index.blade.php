@extends('dashboard.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Feedback</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active">Feedback</li>
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
            <h5 class="card-title">Data Feedback</h5>

            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Member</th>
                    <th>Subject</th>
                    <th>Deskripsi</th>
                    <th>Tanggal</th>
                    <th>Telah dibaca</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($feedbacks as $feedback)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $feedback->nama }}</td>
                      <td>{{ $feedback->subject }}</td>
                      <td>{{ $feedback->description }}</td>
                      <td class="text-center align-middle" style="padding: 0;">
                        <span
                          style="color:#FFB22C; background-color: #F3FEB8; border-radius: 5px; padding: 3px 5px; display: inline-block;">
                          {{ \Carbon\Carbon::parse($feedback->created_at)->format('d-m-Y H:i:s') }}
                        </span>
                      </td>
                      <td class="text-center align-middle" style="padding: 0;">
                        @if ($feedback->is_read === 1)
                          <span
                            style="color:#219653; background-color: #e8f4ed; border-radius: 5px; padding: 3px 5px; display: inline-block;">
                            Sudah
                          </span>
                        @else
                          <span
                            style="color:#FFB22C; background-color: #F3FEB8; border-radius: 5px; padding: 3px 5px; display: inline-block;">
                            Belum
                          </span>
                        @endif
                      </td>
                      <td class="text-center align-middle" style="padding: 0;">
                        <a href="/dashboard/feedback/{{ $feedback->id }}" class="btn btn-info"><i
                            class="bi bi-eye"></i></a>
                        <form action="/dashboard/feedback/{{ $feedback->id }}" method="POST"
                          id="deleteForm{{ $feedback->id }}">
                          @csrf
                          @method('DELETE')
                          <button type="button" class="btn btn-secondary"
                            onclick="deleteFunction('{{ $feedback->id }}')">
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
    function deleteFunction(id) {
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
          document.getElementById('deleteForm' + id).submit();
        }
      });
    };
  </script>
@endsection
