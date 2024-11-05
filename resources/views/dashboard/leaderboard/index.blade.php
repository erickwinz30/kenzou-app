@extends('dashboard.layout.main')

@section('container')
<section class="section">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Leaderboard</h5>

          <!-- Table with stripped rows -->
          <div class="table-responsive">
            <table class="table datatable">
              <thead>
                <tr>
                  <th>Rank</th>
                  <th>Nama</th>
                  <th>No. Hp</th>
                  <th>Email</th>
                  <th>Exp Point</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($leaderboards as $member)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $member->nama }}</td>
                  <td>{{ $member->nomor_telepon }}</td>
                  <td>{{ $member->email }}</td>
                  <td>{{ $member->experience_point }}</td>
                  <td>
                    <a href="/dashboard/member/{{ $member->id }}/edit" class="btn btn-warning" id="edit-button"><i
                        class="bi bi-pencil"></i></a>
                    <form action="/dashboard/member/{{ $member->id }}" method="POST" class="d-inline"
                      id="deleteForm{{ $member->id }}">
                      @method('DELETE')
                      @csrf
                      <button type="button" class="btn btn-danger" onclick="deleteConfirmation('{{ $member->id }}')">
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
@endsection