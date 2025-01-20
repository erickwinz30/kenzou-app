@extends('dashboard.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">

      <!-- Left side columns -->
      <div class="col-12">
        <div class="row">

          <!-- Right side columns -->
          <!-- Jumlah Mobil -->
          <div class="col-lg-6 col-md-6">
            <div class="card info-card customers-card">
              <div class="card-body">
                <h5 class="card-title">Mobil <span>| Hari ini</span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-car-front-fill"></i>
                  </div>
                  <div class="ps-3">
                    <h6>{{ $todayTransaksi }}</h6>
                  </div>
                </div>

              </div>
            </div><!-- End Jumlah Mobil -->
          </div>

          <!-- Penjualan Hari Ini -->
          <div class="col-lg-6 col-md-6">
            <div class="card info-card sales-card">
              <div class="card-body">
                <h5 class="card-title">Pendapatan <span>| Hari Ini</span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-currency-dollar"></i>
                  </div>
                  <div class="ps-3">
                    <h6 class="fs-5">Rp {{ number_format($todaySales, 0, ',', '.') }}</h6>
                  </div>
                </div>
              </div>
            </div><!-- End Penjualan Hari Ini -->
          </div>

          <!-- Reports -->
          <div class="col-12">
            <div class="card">
              {{-- chart --}}
              <div class="card-body">
                <h5 class="card-title">Penjualan Per Jam <span>/ Hari ini</span></h5>

                <!-- Line Chart -->
                <div id="reportsChart"></div>

                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    fetch('/dashboard/fetch-sales-cashier', {
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
                        console.log('Fetched data:', data); // Debug log

                        const salesData = data.map(item => item.subtotal);
                        const perJam = data.map(item => {
                          const dateStr = item.hour.replace(' ', 'T'); // Ensure correct date format for parsing
                          const parsedDate = new Date(dateStr);
                          return parsedDate.getHours();
                        });

                        console.log('Sales Data:', salesData); // Debug log
                        console.log('Jam:', perJam); // Debug log

                        new ApexCharts(document.querySelector("#reportsChart"), {
                          series: [{
                            name: 'Sales',
                            data: salesData,
                          }],
                          chart: {
                            height: 350,
                            type: 'area',
                            toolbar: {
                              show: false
                            },
                          },
                          markers: {
                            size: 4
                          },
                          colors: ['#2eca6a', '#ff771d', '#4154f1'],
                          fill: {
                            type: "gradient",
                            gradient: {
                              shadeIntensity: 1,
                              opacityFrom: 0.3,
                              opacityTo: 0.4,
                              stops: [0, 90, 100]
                            }
                          },
                          dataLabels: {
                            enabled: false
                          },
                          stroke: {
                            curve: 'smooth',
                            width: 2
                          },
                          xaxis: {
                            categories: perJam.map(hour => `${hour}:00`), // Display hours in a readable format
                            labels: {
                              formatter: function(value) {
                                return `${value}`; // Format the x-axis labels to display hours
                              }
                            }
                          },
                          yaxis: {
                            labels: {
                              formatter: function(value) {
                                return `Rp ${value.toLocaleString('id-ID')}`; // Format y-axis labels to display currency
                              }
                            }
                          },
                          tooltip: {
                            x: {
                              format: 'HH:mm'
                            },
                            y: {
                              formatter: function(value) {
                                return `Rp ${value.toLocaleString('id-ID')}`; // Format tooltip to display currency
                              }
                            },
                          }
                        }).render();
                      })
                      .catch(error => {
                        console.error('Error fetching data:', error);
                      });
                  });
                </script>
                <!-- End Line Chart -->

              </div>
              {{-- end chart --}}

            </div>

            <!-- Jumlah Mobil -->
            <div class="card">

              <div class="card-body">
                <h5 class="card-title">Jumlah Mobil <span>/ Hari ini</span></h5>

                <!-- Line Chart -->
                <div id="perHourSalesChart"></div>

                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    fetch('/dashboard/fetch-sales-data', {
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
                        console.log('Fetched data:', data); // Debug log

                        const perJam = data.map(item => {
                          const dateStr = item.hour.replace(' ', 'T'); // Ensure correct date format for parsing
                          const parsedDate = new Date(dateStr);
                          return parsedDate.getHours();
                        });
                        const jumlahMobil = data.map(item => item.jumlah_transaksi);

                        console.log('Jam:', perJam); // Debug log
                        console.log('Jumlah Mobil:', jumlahMobil); // Debug log

                        new ApexCharts(document.querySelector("#perHourSalesChart"), {
                          series: [{
                            name: 'Mobil',
                            data: jumlahMobil,
                          }],
                          chart: {
                            height: 350,
                            type: 'area',
                            toolbar: {
                              show: false
                            },
                          },
                          markers: {
                            size: 4
                          },
                          colors: ['#4154f1', '#2eca6a', '#ff771d'],
                          fill: {
                            type: "gradient",
                            gradient: {
                              shadeIntensity: 1,
                              opacityFrom: 0.3,
                              opacityTo: 0.4,
                              stops: [0, 90, 100]
                            }
                          },
                          dataLabels: {
                            enabled: false
                          },
                          stroke: {
                            curve: 'smooth',
                            width: 2
                          },
                          xaxis: {
                            categories: perJam.map(hour => `${hour}:00`), // Display hours in a readable format
                            labels: {
                              formatter: function(value) {
                                return `${value}`; // Format the x-axis labels to display hours
                              }
                            }
                          },
                          tooltip: {
                            x: {
                              format: 'HH:mm'
                            },
                          }
                        }).render();
                      })
                      .catch(error => {
                        console.error('Error fetching data:', error);
                      });
                  });
                </script>
                <!-- End Line Chart -->

              </div>

            </div>
            <!-- End Jumlah Mobil -->

            <!-- Recent Sale -->
            <div class="col-12">
              <div class="card top-selling overflow-auto">

                <div class="card-body pb-0">
                  <h5 class="card-title">Penjualan <span>| Terbaru</span></h5>
                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Transaksi ID</th>
                        <th scope="col">No. Telp</th>
                        <th scope="col">Layanan</th>
                        <th scope="col">Kasir</th>
                        <th scope="col">Tanggal Transaksi</th>
                        <th scope="col">Total Harga</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($recentTransactions as $transaksi)
                        <tr>
                          <td>{{ Str::limit($transaksi->id, 8) }}</td>
                          <td>{{ $transaksi->nomor_telepon }}</td>
                          <td>
                            @php
                              $namaLayanan = $transaksi->detail_layanan->pluck('layanan.nama_layanan')->toArray();
                              echo implode(', ', $namaLayanan);
                            @endphp
                          </td>
                          <td>{{ $transaksi->user->nama }}</td>
                          <td class="text-center align-middle" style="padding: 0;">
                            <span
                              style="color:#219653; background-color: #e8f4ed; border-radius: 50px; padding: 3px 5px; display: inline-block; box-sizing: border-box">
                              {{ \Carbon\Carbon::parse($transaksi->date)->locale('id')->diffForHumans() }}
                            </span>
                          </td>
                          <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Recent Sale -->
          </div><!-- End Reports -->
        </div>
      </div><!-- End Left side columns -->

    </div><!-- End Right side columns -->

    </div>
  </section>
@endsection
