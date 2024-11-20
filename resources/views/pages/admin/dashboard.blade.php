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
            <div class="col-lg-8">
                <div class="row">
                    <!-- Users Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Users</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
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
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Admin</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 class="fs-4">{{ $totalAdmin }} Admin</h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Sales Card -->

                    <!-- Guru Card -->
                    <div class="col-xxl-4 col-xl-12">

                        <div class="card info-card sales-card">

                            <div class="card-body">
                                <h5 class="card-title">Konsumen</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 class="fs-4">{{$totalKonsumen}} Konsumen</h6>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div><!-- End Customers Card -->

                    <!-- Reports -->
                    <div class="col-12">
                        <div class="card">

                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>

                                    <li><a class="dropdown-item" href="#">Today</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Laporan</h5>

                                <!-- Line Chart -->
                                <div id="reportsChart"></div>


                                <!-- End Line Chart -->

                            </div>

                        </div>
                    </div><!-- End Reports -->

                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Aktivitas Terbaru</h5>
                        <div class="activity">
                            @foreach ($recentActivities as $activity)
                            <div class="activity-item d-flex">
                                <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>
                                <div class="activity-content">
                                    {{ $activity->pembelian->user->name }} melakukan pembayaran cicilan ke-{{ $activity->no_cicilan }} pada
                                    {{ $activity->tgl_bayar->format('d F Y') }} pukul
                                    {{ $activity->tgl_bayar->format('H:i') }}
                                    <span class="text-muted" style="font-size: 90%;"> ({{ $activity->tgl_bayar->diffForHumans() }}) </span>
                                </div>
                            </div><!-- End activity item-->
                            @endforeach
                        </div>
                    </div>
                </div><!-- End Recent Activity -->
            </div><!-- End Right side columns -->

        </div>
    </section>

</main><!-- End #main -->

@endsection