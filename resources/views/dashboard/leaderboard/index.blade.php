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
                  </tr>
                </thead>
                <tbody>
                  @foreach ($leaderboards as $member)
                    <tr>
                      <td>
                        @if ($loop->iteration == 1)
                          <img src="{{ asset('storage/' . $rankFirst) }}" alt="Badge Rank First" style="max-width: 100px">
                        @elseif ($loop->iteration == 2)
                          <img src="{{ asset('storage/' . $rankSecond) }}" alt="Badge Rank Second"
                            style="max-width: 100px">
                        @elseif($loop->iteration == 3)
                          <img src="{{ asset('storage/' . $rankThird) }}" alt="Badge Rank Third" style="max-width: 100px">
                        @else
                          {{ $loop->iteration }}
                        @endif
                      </td>
                      <td>{{ $member->nama }}</td>
                      <td>{{ $member->nomor_telepon }}</td>
                      <td>{{ $member->email }}</td>
                      <td>{{ $member->experience_point }}</td>
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
