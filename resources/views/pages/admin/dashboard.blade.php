@extends('layouts.app')


@section('title')
    Dashboard
@endsection

@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">

                <!-- Left side columns -->
                <div class="col-lg-10">
                    <div class="row">
                        <!-- Users Card -->
                        <div class="col-xxl-4 col-md-4">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Users</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6 class="fs-4">{{ $totalUsers }} User</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Sales Card -->

                        <!-- Admin Card -->
                        <div class="col-xxl-4 col-md-4">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Admin</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6 class="fs-4">{{ $totalAdmin }} Admin</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Sales Card -->

                        <!-- Konsumen Card -->
                        <div class="col-xxl-4 col-md-4">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Konsumen</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6 class="fs-4">{{ $totalKonsumen }} Konsumen</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Customers Card -->

                        <!-- Reports -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Laporan Keuangan</h5>

                                    <!-- Laporan Cards -->
                                    <div class="row mb-4">
                                        <!-- Total Penjualan Card -->
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <div class="card border-primary h-100">
                                                <div
                                                    class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                                            style="width: 45px; height: 45px; font-size: 1.5rem;">
                                                            <i class="bi bi-cash-stack"></i>
                                                        </div>
                                                        <span class="text-muted ps-2" style="font-size: 1.1rem;">Total
                                                            Penjualan</span>
                                                    </div>
                                                    <h6 class="fs-4 fw-bold mb-0">Rp
                                                        {{ number_format($totalPenjualan, 0, ',', '.') }}</h6>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Total Pemasukan Card -->
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <div class="card border-success h-100">
                                                <div
                                                    class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white"
                                                            style="width: 45px; height: 45px; font-size: 1.5rem;">
                                                            <i class="bi bi-wallet2"></i>
                                                        </div>
                                                        <span class="text-muted ps-2" style="font-size: 1.1rem;">Total
                                                            Pemasukan</span>
                                                    </div>
                                                    <h6 class="fs-4 fw-bold mb-0">Rp
                                                        {{ number_format($totalPemasukan, 0, ',', '.') }}</h6>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Total Piutang Card -->
                                        <div class="col-md-4">
                                            <div class="card border-danger h-100">
                                                <div
                                                    class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger text-white"
                                                            style="width: 45px; height: 45px; font-size: 1.5rem;">
                                                            <i class="bi bi-receipt"></i>
                                                        </div>
                                                        <span class="text-muted ps-2" style="font-size: 1.1rem;">Total
                                                            Piutang</span>
                                                    </div>
                                                    <h6 class="fs-4 fw-bold mb-0">Rp
                                                        {{ number_format($totalPiutang, 0, ',', '.') }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Line Chart -->
                                    <div id="reportsChart"></div>
                                    <!-- End Line Chart -->

                                </div>
                            </div>
                        </div>

                        <!-- Grafik Metode Pembayaran -->
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Grafik Metode Pembayaran</h5>
                                    <form action="{{ route('dashboard') }}" class="row g-3 mb-4">
                                        <div class="col-md-4">
                                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                                            <input type="date" id="start_date" name="start_date" class="form-control"
                                                value="{{ request('start_date', $startDate) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="end_date" class="form-label">Tanggal Selesai</label>
                                            <input type="date" id="end_date" name="end_date" class="form-control"
                                                value="{{ request('end_date', $endDate) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label invisible">Filter</label>
                                            <x-primary-button class="w-100 h-50 d-flex justify-center align-items-center">
                                                Filter Data
                                            </x-primary-button>
                                        </div>
                                    </form>

                                    {{-- Chart --}}
                                    <canvas id="paymentChart" height="120"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Grafik Pemasukan -->
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Grafik Pemasukan</h5>

                                    {{-- Chart --}}
                                    <canvas id="incomeChart" height="120"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Grafik Penjualan DP -->
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Grafik Penjualan</h5>

                                    {{-- Chart --}}
                                    <canvas id="dpChart" height="120"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Left side columns -->

                <!-- Right side columns -->
                <div class="col-lg-2">

                    <!-- Recent Activity -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Aktivitas Terbaru</h5>
                            <div class="activity">
                                @if ($recentActivities->isEmpty())
                                    <p class="text-muted">Belum ada aktivitas</p>
                                @else
                                    @foreach ($recentActivities as $activity)
                                        <div class="activity-item d-flex">
                                            <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>
                                            <div class="activity-content">
                                                {{ $activity->pembelian->user->name }} melakukan pembayaran cicilan
                                                ke-{{ $activity->no_cicilan }} pada
                                                {{ $activity->tgl_bayar->format('d F Y') }} pukul
                                                {{ $activity->tgl_bayar->format('H:i') }}
                                                <span class="text-muted" style="font-size: 90%;">
                                                    ({{ $activity->tgl_bayar->diffForHumans() }})
                                                </span>
                                            </div>
                                        </div><!-- End activity item-->
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div><!-- End Recent Activity -->
                </div><!-- End Right side columns -->

            </div>
        </section>

    </main><!-- End #main -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('paymentChart').getContext('2d');

        // Data dari Controller
        const paymentMethods = @json($paymentMethods);
        const paymentData = @json($paymentData);

        // Membuat Chart
        const paymentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: paymentMethods.map(method => method.toUpperCase()),
                datasets: [{
                    label: 'Total Pembayaran Cicilan (Rp)',
                    data: paymentMethods.map(method => paymentData[method] || 0),
                    backgroundColor: ['#3b82f6', '#f97316', '#22c55e', '#64748b'],
                    borderColor: '#ddd',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'Rp ' + value.toLocaleString('id-ID')
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Grafik Pemasukan Per Project
        const incomeCtx = document.getElementById('incomeChart').getContext('2d');
        const projectIncomeData = @json($projectIncomeData);

        const incomeChart = new Chart(incomeCtx, {
            type: 'bar',
            data: {
                labels: projectIncomeData.map(project => project.name),
                datasets: [{
                    label: 'Total Pemasukan per Project (Rp)',
                    data: projectIncomeData.map(project => project.total),
                    backgroundColor: '#3b82f6', // Warna biru yang sama untuk semua bar
                    borderColor: '#ddd',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'Rp ' + value.toLocaleString('id-ID')
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Grafik DP Per Project
        const dpCtx = document.getElementById('dpChart').getContext('2d');
        const projectDPData = @json($projectDPData);

        const dpChart = new Chart(dpCtx, {
            type: 'bar',
            data: {
                labels: projectDPData.map(project => project.name),
                datasets: [{
                    label: 'Total DP per Project (Rp)',
                    data: projectDPData.map(project => project.dp),
                    backgroundColor: '#22c55e', // Warna hijau
                    borderColor: '#ddd',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'Rp ' + value.toLocaleString('id-ID')
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>

@endsection
