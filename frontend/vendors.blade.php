<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Vendor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .navbar-custom { background-color: #f8c8dc; }
    .btn-pink {
      background-color: #f8c8dc;
      border: none;
      color: black;
    }
    .card-pink {
  background-color:rgb(254, 214, 230);
  border: none;
}

    .btn-pink:hover { background-color: #f5b2cd; }
  </style>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold text-dark" href="#">
    <img src="https://omah-rynata-homemade2.odoo.com/web/image/res.company/1/logo" alt="Logo" height="40" class="rounded-circle">
    Purchase</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link text-dark fw-semibold active" href="/purchase/vendors">Vendors</a></li>
        <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="/purchase/products-to-buy">Product To Buy</a></li>
      </ul>
      <div class="ms-3">
        <a href="/" class="btn btn-outline-dark btn-sm">Dashboard</a>
      </div>
    </div>
  </div>
</nav>

<!-- Content -->
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
  <div class="d-flex align-items-center">
    <a href="/purchase" class="me-2 fs-4 fw-bold text-dark" title="Kembali ke Purchase">
      <i class="bi bi-chevron-left"></i>
    </a>
    <h3 class="fw-bold mb-0">Vendors List</h3>
  </div>
</div>

  @if(isset($error))
    <div class="alert alert-danger">{{ $error }}</div>
  @elseif(count($vendors) === 0)
    <div class="alert alert-warning">Belum ada vendor yang terdaftar.</div>
  @else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      @foreach($vendors as $vendor)
        <div class="col">
<div class="card h-100 shadow-sm card-pink">
            <div class="card-body">
              <h5 class="card-title fw-bold text-dark"><i class="bi bi-person-circle me-2"></i>{{ $vendor['name'] }}</h5>
              <p class="card-text text-muted mb-0"><i class="bi bi-envelope-fill me-2"></i>{{ $vendor['email'] ?? '-' }}</p>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
