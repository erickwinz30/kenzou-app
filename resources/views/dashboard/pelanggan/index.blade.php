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
<x-alert-success :message="session('success')" />
@endif

@if (session()->has('error'))
<x-alert-error :message="session('error')" />
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
                  <th>Preferensi Layanan</th>
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
                    $preferensiLayanan = $pelanggan->preferences->pluck('nama_layanan')->toArray();
                    $jumlahPreferensiLayanan = $pelanggan->preferences->pluck('jumlah_penggunaan')->toArray();
                    $preferensiLayananString = implode(
                    ', ',
                    array_map(
                    function ($nama, $jumlah) {
                    return "$nama ($jumlah kali)";
                    },
                    $preferensiLayanan,
                    $jumlahPreferensiLayanan,
                    ),
                    );
                    @endphp
                    {{ $preferensiLayananString ? $preferensiLayananString : '-' }}
                  </td>
                  <td>
                    @php
                    $tanggalLahir = $pelanggan->member->tanggal_lahir;
                    $umur = \Carbon\Carbon::parse($tanggalLahir)->age;
                    $tanggalLahir = \Carbon\Carbon::parse($tanggalLahir)->format('Y-m-d');
                    @endphp
                    <span
                      style="color:#219653; background-color: #e8f4ed; border-radius: 10px; padding: 5px 10px; display: inline-block;">
                      {{ $tanggalLahir }} / {{ $umur }}
                    </span>
                  </td>
                  <td>{{ $pelanggan->member->experience_point }} ({{ $pelanggan->rank }})</td>
                  <td>{{ $pelanggan->member->redeemable_point }}</td>
                  <td>{{ $pelanggan->member->referral_code }}</td>
                  <td>
                    <a href="/dashboard/pelanggan/{{ $pelanggan->id }}/edit" class="btn btn-warning" id="edit-button"><i
                        class="bi bi-pencil"></i></a>
                    <form action="/dashboard/pelanggan/{{ $pelanggan->id }}" method="POST" class="d-inline"
                      id="deleteForm{{ $pelanggan->id }}">
                      @method('DELETE')
                      @csrf
                      <button type="button" class="btn btn-danger" onclick="deleteConfirmation('{{ $pelanggan->id }}')">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </td>
                  @else
                  <td>-</td>
                  <td>-</td>
                  <td>
                    @php
                    $preferensiLayanan = $pelanggan->preferences->pluck('nama_layanan')->toArray();
                    $jumlahPreferensiLayanan = $pelanggan->preferences->pluck('jumlah_penggunaan')->toArray();
                    $preferensiLayananString = implode(
                    ', ',
                    array_map(
                    function ($nama, $jumlah) {
                    return "$nama ($jumlah kali)";
                    },
                    $preferensiLayanan,
                    $jumlahPreferensiLayanan,
                    ),
                    );
                    @endphp
                    {{ $preferensiLayananString ? $preferensiLayananString : '-' }}
                  </td>
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
      <!-- Penjualan Bulan Ini -->

      <div class="card">

        <div class="card-body">
          <h5 class="card-title">Jumlah Layanan yang Dipilih</h5>

          <!-- Line Chart -->
          <div id="thisYearLayananChart"></div>

          <script>
            document.addEventListener("DOMContentLoaded", () => {
                fetch('/dashboard/fetch-all-layanan-count', {
                    headers: {
                      'Accept': 'application/json'
                    }
                  })
                  .then(response => {
                    if (!response.ok) {
                      throw new Error('Network response was not ok');
                    }
                    return response.json();
                  })
                  .then(data => {
                    console.log('Jumlah seluruh layanan data:', data); // Debug log

                    const layananNames = data.map(item => item.nama_layanan);
                    const layananCount = data.map(item => item.jumlah_penggunaan);

                    // 4) Inisialisasi Chart
                    const options = {
                      series: [{
                        name: 'Jumlah Layanan',
                        data: layananCount,
                      }],
                      chart: {
                        type: 'bar',
                        height: 350
                      },
                      plotOptions: {
                        bar: {
                          distributed: true,
                          dataLabels: {
                            position: 'top',
                          },
                        }
                      },
                      colors: ['#ff6b6b', '#ffca3a', '#8ac926', '#1982c4', '#6a4c93', '#f72585', '#7209b7', '#3a0ca3',
                        '#4361ee', '#4cc9f0'
                      ],
                      dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                          return val;
                        },
                        offsetY: -20,
                        style: {
                          fontSize: '12px',
                          colors: ["#304758"]
                        }
                      },
                      xaxis: {
                        categories: layananNames,
                        position: 'bottom',
                        axisBorder: {
                          show: false
                        },
                        axisTicks: {
                          show: false
                        },
                        crosshairs: {
                          fill: {
                            type: 'gradient',
                            gradient: {
                              colorFrom: '#D8E3F0',
                              colorTo: '#BED1E6',
                              stops: [0, 100],
                              opacityFrom: 0.4,
                              opacityTo: 0.5,
                            }
                          }
                        },
                        tooltip: {
                          enabled: true,
                        }
                      },
                      yaxis: {
                        axisBorder: {
                          show: false
                        },
                        axisTicks: {
                          show: false,
                        },
                        labels: {
                          show: true,
                        }
                      },
                    };

                    const chart = new ApexCharts(document.querySelector("#thisYearLayananChart"), options);
                    chart.render();
                  })
                  .catch(error => {
                    console.error('Error fetching data:', error);
                  });
              });
          </script>
          <!-- End Line Chart -->

        </div>

      </div>
      <!-- End Penjualan Bulan Ini -->

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

            let preferensiLayanan = pelanggan.preferences.map(preference => {
              return `${preference.nama_layanan} (${preference.jumlah_penggunaan} kali)`;
            }).join(', ');

            row.innerHTML = `
              <td>${iteration}</td>
              <td>${pelanggan.nomor_telepon}</td>
              <td>${pelanggan.nama}</td>
              <td>${pelanggan.email}</td>
              <td>${preferensiLayanan ? preferensiLayanan : '-'}</td>
              <td>
                <span style="color:#219653; background-color: #e8f4ed; border-radius: 10px; padding: 5px 10px; display: inline-block;">
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

            let preferensiLayanan = pelanggan.preferences.map(preferences => {
              return `${preferences.nama_layanan} (${preferences.jumlah_penggunaan} kali)`;
            }).join(', ');

            row.innerHTML = `
              <td>${iteration}</td>
              <td>${pelanggan.nomor_telepon}</td>
              <td>${pelanggan.nama}</td>
              <td>${pelanggan.email}</td>
              <td>${preferensiLayanan ? preferensiLayanan : '-'}</td>
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
