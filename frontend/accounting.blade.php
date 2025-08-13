<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Accounting Overview</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .navbar-custom { background-color: #f8c8dc; }
    .card-custom {
      background-color: #ffe0ef;
      border: none;
      border-radius: 16px;
      padding: 20px;
      height: 100%;
    }
    .card-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: #d63384;
    }
    .card-value {
      font-size: 1.25rem;
      font-weight: bold;
      color: #000;
    }
    .btn-outline-pink {
      border-color: #f8c8dc;
      color: #d63384;
    }
    .btn-outline-pink:hover {
      background-color: #f8c8dc;
      color: #000;
    }
    .badge-soft {
      background-color: #fcd6e6;
      color: #d63384;
      font-weight: 500;
    }
  </style>
</head>
<body class="bg-light">

<!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
      <div class="container">

    <a class="navbar-brand fw-bold text-dark" href="#">  <img src="https://omah-rynata-homemade2.odoo.com/web/image/res.company/1/logo" alt="Logo" height="40" class="rounded-circle">
    Accounting
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav ms-auto"> <!-- Menu sekarang pindah ke kanan --></ul>
      <div class="ms-3">
        <a href="/" class="btn btn-outline-dark btn-sm">Dashboard</a>
      </div>
    </div>
  </div>
</nav>

<!-- Content -->
<div class="container py-4">
  <h3 class="mb-4 fw-bold">Ringkasan Keuangan</h3>

  @if(isset($error))
    <div class="alert alert-danger">{{ $error }}</div>
  @else
  <div class="row g-4">
    <!-- Profit & Loss -->
    <div class="col-md-4">
      <div class="card card-custom shadow-sm">
        <div class="card-title">Profit & Loss</div>
        <div class="card-body px-0">
          <p class="mb-1">Pendapatan: <span class="card-value">Rp {{ number_format($income, 0, ',', '.') }}</span></p>
          <p class="mb-1">Biaya: <span class="card-value">Rp {{ number_format($expense, 0, ',', '.') }}</span></p>
          <p>Laba: <span class="card-value">Rp {{ number_format($profit, 0, ',', '.') }}</span></p>
          <span class="badge badge-soft">Tahunan</span>
        </div>
      </div>
    </div>

    <!-- Balance Sheet -->
    <div class="col-md-4">
      <div class="card card-custom shadow-sm">
        <div class="card-title">Balance Sheet</div>
        <div class="card-body px-0">
          <p class="mb-1">Jumlah Aset: <span class="card-value">Rp {{ number_format($assets, 0, ',', '.') }}</span></p>
          <p class="mb-1">Jumlah Kewajiban: <span class="card-value">Rp {{ number_format($liabilities, 0, ',', '.') }}</span></p>
          <p>Ekuitas: <span class="card-value">Rp {{ number_format($equity, 0, ',', '.') }}</span></p>
          <span class="badge badge-soft">Posisi Saat Ini</span>
        </div>
      </div>
    </div>

    <!-- COA -->
    <div class="col-md-4">
      <div class="card card-custom shadow-sm">
        <div class="card-title">Chart of Accounts</div>
        <div class="card-body px-0">
          <p class="mb-1">Total Akun: <span class="card-value">{{ $totalAccounts }}</span></p>
          <p class="mb-1">Tipe utama: Aset, Kewajiban, Ekuitas, Pendapatan, Biaya</p>
          <span class="badge badge-soft">Custom COA</span>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
