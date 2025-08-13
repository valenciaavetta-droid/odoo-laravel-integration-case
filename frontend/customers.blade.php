<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Customers</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .navbar-custom {
      background-color: #f8c8dc;
    }
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
    <h3 class="fw-bold mb-0">Customers</h3>
  </div>
</div>

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  @if(count($customers) === 0)
    <div class="alert alert-warning">Tidak ada customer ditemukan.</div>
  @else
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Kota</th>
            <th>Negara</th>
          </tr>
        </thead>
        <tbody>
          @foreach($customers as $cust)
            <tr>
              <td>{{ $cust['name'] }}</td>
              <td>{{ $cust['email'] ?? '-' }}</td>
              <td>{{ $cust['phone'] ?? '-' }}</td>
              <td>{{ $cust['city'] ?? '-' }}</td>
              <td>{{ $cust['country_id'][1] ?? '-' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
