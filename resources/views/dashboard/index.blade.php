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
    <!-- Right side columns -->
    <div class="row">
      <div class="col-12">
        <div class="row">
          <!-- Jumlah Mobil -->
          <div class="col-lg-4">
            <div class="card info-card customers-card">
              <div class="card-body">
                <h5 class="card-title">Mobil <span>| Hari ini</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-car-front-fill"></i>
                  </div>
                  <div class="ps-3">
                    <h6>{{ $todayTransaksi }}</h6>
                    <span class="text-danger small pt-1 fw-bold">{{ $yesterdayCarPercentage }}%</span>
                    @if ($yesterdayCarPercentage < 0)
                      <span class="text-muted small pt-2 ps-1">Turun</span>
                    @else
                      <span class="text-muted small pt-2 ps-1">Naik</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End Jumlah Mobil -->

          <!-- Penjualan Hari Ini -->
          <div class="col-lg-4">
            <div class="card info-card sales-card">
              <div class="card-body">
                <h5 class="card-title">Pendapatan <span>| Hari Ini</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-currency-dollar"></i>
                  </div>
                  <div class="ps-3">
                    <h6 class="fs-5">Rp {{ number_format($todaySales, 0, ',', '.') }}</h6>
                    <span class="text-danger small pt-1 fw-bold">{{ $yesterdaySalesPercentage }}%</span>
                    @if ($yesterdaySalesPercentage < 0)
                      <span class="text-muted small pt-2 ps-1">Turun</span>
                    @else
                      <span class="text-muted small pt-2 ps-1">Naik</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End Penjualan Hari Ini -->

          <!-- This Month Sales -->
          <div class="col-lg-4">
            <div class="card info-card revenue-card">
              <div class="card-body">
                <h5 class="card-title">Pendapatan <span>| Bulan Ini</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-currency-dollar"></i>
                  </div>
                  <div class="ps-3">
                    <h6 class="fs-5">Rp {{ number_format($thisMonth, 0, ',', '.') }}</h6>
                    <span class="text-danger small pt-1 fw-bold">{{ $lastMonth }}%</span>
                    @if ($lastMonth < 0)
                      <span class="text-muted small pt-2 ps-1">Turun</span>
                    @else
                      <span class="text-muted small pt-2 ps-1">Naik</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End This Month Sales -->
        </div>
      </div>

      <!-- Left side columns -->
      <div class="col-12">
        <!-- Reports -->
        <div class="card">

          <div class="card-body">
            <h5 class="card-title">Penjualan Per Jam <span>/ Hari ini</span></h5>

            <!-- Line Chart -->
            <div id="reportsChart"></div>

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

        </div><!-- End Reports -->

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

        <!-- Top Selling -->

        <div class="card top-selling overflow-auto">

          <div class="card-body pb-0">
            <h5 class="card-title">Penjualan <span>| Terbaru</span></h5>
            <table class="table table-borderless">
              <thead>
                <tr>
                  <th scope="col">Transaksi ID</th>
                  <th scope="col">Pelanggan</th>
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
                    @if ($transaksi->pelanggan->member_id)
                      <td>{{ $transaksi->pelanggan->member->nama }}</td>
                    @else
                      <td>{{ $transaksi->pelanggan->nomor_telepon }}</td>
                    @endif
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
                    <td>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>

          </div>

        </div>
        <!-- End Top Selling -->

        <!-- Penjualan Bulan Ini -->

        <div class="card">

          <div class="card-body">
            <h5 class="card-title">Penjualan Bulan Ini <span>/ per Hari</span></h5>

            <!-- Line Chart -->
            <div id="thisMonthSalesChart"></div>

            <script>
              document.addEventListener("DOMContentLoaded", () => {
                fetch('/dashboard/fetch-sales-this-month', {
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
                    const perHari = data.map(item => {
                      const date = new Date(item.day);
                      return date.getDate(); // This will return the day of the month as a number (1-31)
                    });

                    console.log('Sales Data:', salesData); // Debug log
                    console.log('Tanggal:', perHari); // Debug log

                    new ApexCharts(document.querySelector("#thisMonthSalesChart"), {
                      series: [{
                        name: 'Sales',
                        data: salesData,
                      }],
                      chart: {
                        height: 350,
                        type: 'bar',
                        toolbar: {
                          show: false
                        },
                      },
                      markers: {
                        size: 4
                      },
                      colors: ['#ff6b6b', '#ffca3a', '#8ac926'],
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
                        categories: perHari.map(date => `${date}`), // Display hours in a readable format
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

        </div>
        <!-- End Penjualan Bulan Ini -->

        <!-- Jumlah Mobil Bulan Ini -->

        <div class="card">

          <div class="card-body">
            <h5 class="card-title">Jumlah Mobil Bulan Ini <span>/ per Hari</span></h5>

            <!-- Line Chart -->
            <div id="thisMonthCarChart"></div>

            <script>
              document.addEventListener("DOMContentLoaded", () => {
                fetch('/dashboard/fetch-car-this-month', {
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

                    const carData = data.map(item => item.jumlah_transaksi);
                    const perHari = data.map(item => {
                      const date = new Date(item.day);
                      return date.getDate(); // This will return the day of the month as a number (1-31)
                    });

                    console.log('Car Data:', carData); // Debug log
                    console.log('Tanggal:', perHari); // Debug log

                    new ApexCharts(document.querySelector("#thisMonthCarChart"), {
                      series: [{
                        name: 'Mobil',
                        data: carData,
                      }],
                      chart: {
                        height: 350,
                        type: 'bar',
                        toolbar: {
                          show: false
                        },
                      },
                      markers: {
                        size: 4
                      },
                      colors: ['#ffca3a', '#ff6b6b', '#8ac926'],
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
                        categories: perHari.map(date => `${date}`), // Display hours in a readable format
                        labels: {
                          formatter: function(value) {
                            return `${value}`; // Format the x-axis labels to display hours
                          }
                        }
                      },
                      yaxis: {
                        labels: {
                          formatter: function(value) {
                            return `${value}`; // Format y-axis labels to display currency
                          }
                        }
                      },
                      tooltip: {
                        x: {
                          format: 'DD'
                        },
                        y: {
                          formatter: function(value) {
                            return `${value}`; // Format tooltip to display currency
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

        </div>
        <!-- End Reports -->

        <!-- Penjualan Bulan Ini -->

        <div class="card">

          <div class="card-body">
            <h5 class="card-title">Penjualan Tahun Ini <span>/ per Bulan</span></h5>

            <!-- Line Chart -->
            <div id="thisYearSalesChart"></div>

            <script>
              document.addEventListener("DOMContentLoaded", () => {
                fetch('/dashboard/fetch-sales-this-year', {
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
                    const perBulan = data.map(item => {
                      const date = new Date(item.month);
                      return date.getMonth() + 1; // This will return the day of the month as a number (1-31)
                    });

                    console.log('Sales Data:', salesData); // Debug log
                    console.log('Bulan:', perBulan); // Debug log

                    new ApexCharts(document.querySelector("#thisYearSalesChart"), {
                      series: [{
                        name: 'Sales',
                        data: salesData,
                      }],
                      chart: {
                        height: 350,
                        type: 'bar',
                        toolbar: {
                          show: false
                        },
                      },
                      markers: {
                        size: 4
                      },
                      colors: ['#ff6b6b', '#ffca3a', '#8ac926'],
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
                        categories: perBulan.map(date => `${date}`), // Display hours in a readable format
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
                          format: 'MM'
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

        </div>
        <!-- End Penjualan Bulan Ini -->

      </div><!-- End Left side columns -->
    </div>
  </section>
@endsection
