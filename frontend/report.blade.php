<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Grafik Penjualan Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    .navbar-custom { background-color: #f8c8dc; }
      .btn-pink {
    background-color: #f8c8dc;
    border: none;
  }
  .btn-pink:hover {
    background-color: #f5b2cd;
  }
  </style>
</head>
<body class="bg-light">

  <nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
      <div class="container">

    <a class="navbar-brand fw-bold text-dark" href="#">  <img src="https://omah-rynata-homemade2.odoo.com/web/image/res.company/1/logo" alt="Logo" height="40" class="rounded-circle">   Sales</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav ms-auto"> <!-- Menu sekarang pindah ke kanan -->
        <li class="nav-item">
    <a class="nav-link text-dark fw-semibold" href="/sales/orders">Orders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark fw-semibold" href="/sales/customers">Customers</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark fw-semibold" href="/sales/report">Reports</a>
        </li>
      </ul>
      <div class="ms-3">
        <a href="/" class="btn btn-outline-dark btn-sm">Dashboard</a>
      </div>
    </div>
  </div>
</nav>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    {{-- Kiri: Icon Back + Judul --}}
    <div class="d-flex align-items-center">
<a href="/sales" class="me-2 fs-4 fw-bold text-dark" title="Kembali ke Sales">
  <i class="bi bi-chevron-left"></i>
</a>

      <h3 class="fw-bold mb-0">Grafik Penjualan Produk</h3>
    </div>

    {{-- (opsional) kanan, bisa taruh tombol atau info lain --}}
    {{-- <div>Right Content</div> --}}
  </div>

  {{-- Isi grafiknya di sini --}}
  <canvas id="salesChart" height="120"></canvas>
</div>



  @if(isset($error))
    <div class="alert alert-danger">{{ $error }}</div>
  @elseif(count($reportData) === 0)
    <div class="alert alert-warning">Data tidak tersedia.</div>
  @else
    <canvas id="salesChart" height="120"></canvas>
<script>
  const ctx = document.getElementById('salesChart').getContext('2d');
  const labels = {!! json_encode(array_keys($reportData)) !!};
  const dataValues = {!! json_encode(array_map(fn($d) => $d['total'], $reportData)) !!};

  const salesChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Total Penjualan (Rp)',
        data: dataValues,
        backgroundColor: [
          '#f5a8c0', // soft pink
          '#f38db3', // rose pink
          '#ec6ca1', // pink cerah
          '#e94c90', // hot pink
          '#e3267d', // deep pink
        ],
        borderColor: '#f5b2cd',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
            }
          }
        }
      }
    }
  });
</script>

  @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
